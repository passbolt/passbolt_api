<?php
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

class GroupUpdateAdminSummaryEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE = 'GM/group_user_update';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @param UsersTable $usersTable Users Table
     */
    public function __construct(UsersTable $usersTable = null)
    {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            GroupsUpdateService::UPDATE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param Event $event User delete event
     *
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();

        /** @var Group $resource */
        $group = $event->getData('group');
        $addedGroupsUsers = $event->getData('addedGroupsUsers');
        $updatedGroupsUsers = $event->getData('updatedGroupsUsers');
        $removedGroupsUsers = $event->getData('removedGroupsUsers');
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
                $groupManager->username,
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
     * @param string $emailRecipient Email recipient
     * @param Group $group Group
     * @param array $addedUsers List of added users
     * @param array $updatedUsers List of updated users
     * @param array $removedUsers List of removed users
     * @param array $whoIsAdmin List of users
     * @param User $modifiedBy User who modified
     *
     * @return Email
     */
    private function createSummaryEmail(
        string $emailRecipient,
        Group $group,
        array $addedUsers,
        array $updatedUsers,
        array $removedUsers,
        array $whoIsAdmin,
        User $modifiedBy
    ) {
        $subject = __("{0} updated the group {1}", $modifiedBy->profile->first_name, $group->name);
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

        return new Email($emailRecipient, $subject, $data, self::TEMPLATE);
    }

    /**
     * Retrieve the information of a list of users that will be used in the summary email.
     *
     * @param array $usersIds The list of users to retrieve the information for.
     *
     * @return array
     */
    private function _getSummaryUser(array $usersIds = [])
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
     * @param Group $group Group
     * @param array $excludeUsersIds ID of the users to exclude from the managers to retrieve
     *
     * @return User[]
     */
    private function getGroupManagers(Group $group, array $excludeUsersIds)
    {
        return $this->usersTable->find()
            ->select(['Users.username'])
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
