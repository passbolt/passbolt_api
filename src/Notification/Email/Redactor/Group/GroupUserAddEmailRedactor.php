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
use App\Model\Table\GroupsTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Groups\GroupsUpdateService;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupUserAddEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE = 'LU/group_user_add';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @param UsersTable|null $usersTable Users Table
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
            GroupsTable::GROUP_CREATE_SUCCESS_EVENT_NAME,
            GroupsUpdateService::UPDATE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param Event $event User delete event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();

        switch ($event->getName()) {
            case GroupsUpdateService::UPDATE_SUCCESS_EVENT_NAME:
                /** @var Group $resource */
                $group = $event->getData('group');
                $addedGroupsUsers = $event->getData('addedGroupsUsers'); // the list of added groups users
                $modifiedBy = $this->usersTable->findFirstForEmail($event->getData('userId'));
                $emails = $this->createGroupUserAddedUpdateEmails($group, $addedGroupsUsers, $modifiedBy);
                break;
            default:
                /** @var Group $resource */
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
     * Return a list of user ids
     *
     * @param array $userIds List of user ids
     * @return ResultSetInterface
     */
    private function getUserNames(array $userIds)
    {
        return $this->usersTable->find()
            ->select(['id', 'username'])
            ->where(['id IN' => $userIds])->all();
    }

    /**
     * @param Group $group Group which was created
     *
     * @return array
     */
    private function createGroupCreatedEmail(Group $group)
    {
        $emails = [];
        $admin = $this->usersTable->findFirstForEmail($group->created_by);
        $userIds = Hash::extract($group->groups_users, '{n}.user_id');
        $userNames = $this->getUserNames($userIds);
        $userNames = Hash::combine($userNames->toArray(), '{n}.id', '{n}.username');

        foreach ($group->groups_users as $group_user) {
            // Don't send notification if the user added added themselves
            if ($group_user->user_id === $group->created_by) {
                continue;
            }

            $emails[] = $this->createGroupUserAddEmail($userNames[$group_user->user_id], $admin, $group, $group_user->is_admin);
        }

        return $emails;
    }

    /**
     * Send group update email to the new members
     *
     * @param Group $group the affected group
     * @param array $addedGroupsUsers Users added to the group
     * @param User  $modifiedBy person who did the change
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
        $users = $this->getUserNames($usersIds)->combine('id', 'username');
        $whoIsAdmin = Hash::combine($addedGroupsUsers, '{n}.user_id', '{n}.is_admin');

        foreach ($users as $userId => $userName) {
            $isAdmin = isset($whoIsAdmin[$userId]) && $whoIsAdmin[$userId];
            $emails[] = $this->createGroupUserAddEmail($userName, $modifiedBy, $group, $isAdmin);
        }

        return $emails;
    }

    /**
     * @param string $emailRecipient Email recipient
     * @param User   $admin Admin
     * @param Group  $group Group
     * @param bool   $isAdmin Is user admin
     * @return Email
     */
    private function createGroupUserAddEmail(string $emailRecipient, User $admin, Group $group, bool $isAdmin)
    {
        $subject = __("{0} added you to the group {1}", $admin->profile->first_name, $group->name);
        $data = ['body' => ['isAdmin' => $isAdmin, 'admin' => $admin, 'group' => $group], 'title' => $subject];

        return new Email($emailRecipient, $subject, $data, self::TEMPLATE);
    }
}
