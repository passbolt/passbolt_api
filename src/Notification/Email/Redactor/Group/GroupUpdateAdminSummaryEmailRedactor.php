<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.13.0
 */

namespace App\Notification\Email\Redactor\Group;

use App\Model\Entity\Group;
use App\Model\Entity\GroupsUser;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Groups\GroupsUpdateService;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Locale\Service\LocaleService;

class GroupUpdateAdminSummaryEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'GM/group_user_update';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * @param \App\Model\Table\UsersTable|null $usersTable Users Table
     */
    public function __construct(?UsersTable $usersTable = null)
    {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            GroupsUpdateService::UPDATE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.group.manager.update';
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Model\Entity\Group $group */
        $group = $event->getData('group');
        /** @var \App\Model\Dto\EntitiesChangesDto $entitiesChanges */
        $entitiesChanges = $event->getData('entitiesChanges');
        $addedGroupsUsers = $entitiesChanges->getAddedEntities(GroupsUser::class);
        $updatedGroupsUsers = $entitiesChanges->getUpdatedEntities(GroupsUser::class);
        $removedGroupsUsers = $entitiesChanges->getDeletedEntities(GroupsUser::class);
        $modifiedBy = $this->usersTable->findFirstForEmail($event->getData('userId'));

        if ((empty($addedGroupsUsers) && empty($updatedGroupsUsers) && empty($removedGroupsUsers))) {
            return $emailCollection;
        }

        $whoIsAdmin = [];
        $usersIds = [];
        $addedUsersIds = $removedUsersIds = $updatedUsersIds = [];

        if (!empty($addedGroupsUsers)) {
            $addedUsersIds = Hash::extract($addedGroupsUsers, '{n}.user_id');
            $usersIds = array_merge($usersIds, $addedUsersIds);
            $whoIsAdmin = array_merge($whoIsAdmin, Hash::combine($addedGroupsUsers, '{n}.user_id', '{n}.is_admin'));
        }
        if (!empty($removedGroupsUsers)) {
            $removedUsersIds = Hash::extract($removedGroupsUsers, '{n}.user_id');
            $usersIds = array_merge($usersIds, $removedUsersIds);
            $whoIsAdmin = array_merge($whoIsAdmin, Hash::combine($removedGroupsUsers, '{n}.user_id', '{n}.is_admin'));
        }
        if (!empty($updatedGroupsUsers)) {
            $updatedUsersIds = Hash::extract($updatedGroupsUsers, '{n}.user_id');
            $usersIds = array_merge($usersIds, $updatedUsersIds);
            $whoIsAdmin = array_merge($whoIsAdmin, Hash::combine($updatedGroupsUsers, '{n}.user_id', '{n}.is_admin'));
        }

        // Retrieve the other group managers.
        // Exclude from list the new managers, the removed managers and the user who did the changes.
        $excludeUsersIds = array_merge($usersIds, [$modifiedBy->id]);
        $groupManagers = $this->getGroupManagers($group, $excludeUsersIds);

        // If no group managers to notify
        if (empty($groupManagers)) {
            return $emailCollection;
        }

        // Send the email to all the group managers.
        foreach ($groupManagers as $groupManager) {
            $emailCollection->addEmail($this->createSummaryEmail(
                $groupManager,
                $group,
                $this->_getSummaryUser($addedUsersIds),
                // Retrieve the user information corresponding to the users impacted by the changes.
                $this->_getSummaryUser($updatedUsersIds),
                $this->_getSummaryUser($removedUsersIds),
                $whoIsAdmin,
                $modifiedBy
            ));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient User recipient
     * @param \App\Model\Entity\Group $group Group
     * @param array $addedUsers List of added users
     * @param array $updatedUsers List of updated users
     * @param array $removedUsers List of removed users
     * @param array $whoIsAdmin List of users
     * @param \App\Model\Entity\User $modifiedBy User who modified
     * @return \App\Notification\Email\Email
     */
    private function createSummaryEmail(
        User $recipient,
        Group $group,
        array $addedUsers,
        array $updatedUsers,
        array $removedUsers,
        array $whoIsAdmin,
        User $modifiedBy
    ): Email {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($modifiedBy, $group) {
                return __('{0} updated the group {1}', $modifiedBy->profile->first_name, $group->name);
            }
        );
        $data = [
            'body' => [
                'admin' => $modifiedBy,
                'group' => $group,
                'addedUsers' => $addedUsers,
                'updatedUsers' => $updatedUsers,
                'removedUsers' => $removedUsers,
                'whoIsAdmin' => $whoIsAdmin,
            ],
            'title' => $subject,
        ];

        return new Email($recipient, $subject, $data, self::TEMPLATE);
    }

    /**
     * Retrieve the information of a list of users that will be used in the summary email.
     *
     * @param array $usersIds The list of users to retrieve the information for.
     * @return array
     */
    private function _getSummaryUser(array $usersIds = []): array
    {
        if (empty($usersIds)) {
            return [];
        }

        return $this->usersTable->find()
            ->contain('Profiles')
            ->select(['Users.id', 'Profiles.first_name', 'Profiles.last_name'])
            ->where(['Users.id IN' => $usersIds])
            ->toArray();
    }

    /**
     * Get a list of groupo managers
     *
     * @param \App\Model\Entity\Group $group Group
     * @param array $excludeUsersIds ID of the users to exclude from the managers to retrieve
     * @return \App\Model\Entity\User[]
     */
    private function getGroupManagers(Group $group, array $excludeUsersIds): array
    {
        return $this->usersTable->find('locale')
            ->find('notDisabled')
            ->select([
                'Users.username',
                'Users.disabled',
            ])
            ->innerJoinWith('GroupsUsers')
            ->where(
                [
                    'GroupsUsers.group_id' => $group->id,
                    'GroupsUsers.is_admin' => true,
                    'GroupsUsers.user_id NOT IN' => $excludeUsersIds,
                ]
            )
            ->toArray();
    }
}
