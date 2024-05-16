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
use App\Model\Table\GroupsTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Groups\GroupsUpdateService;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Locale\Service\LocaleService;

class GroupUserAddEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'LU/group_user_add';

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
            GroupsTable::GROUP_CREATE_SUCCESS_EVENT_NAME,
            GroupsUpdateService::UPDATE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.group.user.add';
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        switch ($event->getName()) {
            case GroupsUpdateService::UPDATE_SUCCESS_EVENT_NAME:
                /** @var \App\Model\Entity\Group $group */
                $group = $event->getData('group');
                /** @var \App\Model\Dto\EntitiesChangesDto $entitiesChanges */
                $entitiesChanges = $event->getData('entitiesChanges');
                $addedGroupsUsers = $entitiesChanges->getAddedEntities(GroupsUser::class);
                $modifiedBy = $this->usersTable->findFirstForEmail($event->getData('userId'));
                $emails = $this->createGroupUserAddedUpdateEmails($group, $addedGroupsUsers, $modifiedBy);
                break;
            default:
                /** @var \App\Model\Entity\Group $group */
                $group = $event->getData('group');
                $emails = $this->createGroupCreatedEmail($group);
                break;
        }

        foreach ($emails as $email) {
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * Return a list of recipients
     *
     * @param array $userIds List of user ids
     * @return \Cake\ORM\Query
     */
    private function getRecipients(array $userIds): Query
    {
        return $this->usersTable->find('locale')
            ->find('notDisabled')
            ->where(['Users.id IN' => $userIds]);
    }

    /**
     * @param \App\Model\Entity\Group $group Group which was created
     * @return array
     */
    private function createGroupCreatedEmail(Group $group)
    {
        $emails = [];

        $admin = $this->usersTable->findFirstForEmail($group->created_by);
        $userIds = Hash::extract($group->groups_users, '{n}.user_id');

        // Don't send notification if the user added themselves
        $recipients = $this->getRecipients($userIds)
            ->where(['Users.id !=' => $group->created_by])
            ->all();

        foreach ($group->groups_users as $group_user) {
            if ($group_user->user_id === $group->created_by) {
                continue;
            }

            $recipient = $recipients->firstMatch(['id' => $group_user->user_id]);

            // skip disabled group members
            if (isset($recipient)) {
                $emails[] = $this->createGroupUserAddEmail($recipient, $admin, $group, $group_user->is_admin);
            }
        }

        return $emails;
    }

    /**
     * Send group update email to the new members
     *
     * @param \App\Model\Entity\Group $group the affected group
     * @param array $addedGroupsUsers Users added to the group
     * @param \App\Model\Entity\User $modifiedBy person who did the change
     * @return array
     */
    public function createGroupUserAddedUpdateEmails(Group $group, array $addedGroupsUsers, User $modifiedBy)
    {
        $emails = [];

        if (empty($addedGroupsUsers)) {
            return $emails;
        }

        // Retrieve the users to send an email to.
        $usersIds = Hash::extract($addedGroupsUsers, '{n}.user_id');
        $users = $this->getRecipients($usersIds);
        $whoIsAdmin = Hash::combine($addedGroupsUsers, '{n}.user_id', '{n}.is_admin');

        foreach ($users as $user) {
            $isAdmin = isset($whoIsAdmin[$user->id]) && $whoIsAdmin[$user->id];
            $emails[] = $this->createGroupUserAddEmail($user, $modifiedBy, $group, $isAdmin);
        }

        return $emails;
    }

    /**
     * @param \App\Model\Entity\User $recipient User recipient
     * @param \App\Model\Entity\User $admin Admin
     * @param \App\Model\Entity\Group $group Group
     * @param bool $isAdmin Is user admin
     * @return \App\Notification\Email\Email
     */
    private function createGroupUserAddEmail(User $recipient, User $admin, Group $group, bool $isAdmin): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($admin, $group) {
                return __('{0} added you to the group {1}', $admin->profile->first_name, $group->name);
            }
        );
        $data = ['body' => ['isAdmin' => $isAdmin, 'admin' => $admin, 'group' => $group], 'title' => $subject];

        return new Email($recipient, $subject, $data, self::TEMPLATE);
    }
}
