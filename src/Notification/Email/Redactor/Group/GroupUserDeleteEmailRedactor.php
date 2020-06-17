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

class GroupUserDeleteEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE = 'LU/group_user_delete';

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

        /** @var Group $resource */
        $group = $event->getData('group');
        $removedGroupsUsers = $event->getData('removedGroupsUsers');
        $modifiedBy = $this->usersTable->findFirstForEmail($event->getData('userId'));

        $emails = $this->createGroupUserAddedUpdateEmails($group, $removedGroupsUsers, $modifiedBy);

        foreach ($emails as $email) {
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * Send group update email to the new members
     *
     * @param Group $group the affected group
     * @param array $removedGroupsUsers List of removed users
     * @param User $modifiedBy person who did the change
     * @return array
     */
    public function createGroupUserAddedUpdateEmails(Group $group, array $removedGroupsUsers, User $modifiedBy)
    {
        $emails = [];

        if (empty($removedGroupsUsers)) {
            return $emails;
        }

        // Retrieve the users to send an email to.
        $usersIds = Hash::extract($removedGroupsUsers, '{n}.user_id');
        $users = $this->usersTable->find()
            ->select(['id', 'username'])
            ->where(['id IN' => $usersIds])
            ->combine('id', 'username');

        foreach ($users as $userId => $userName) {
            $emails[] = $this->createGroupUserDeleteEmail($userName, $modifiedBy, $group);
        }

        return $emails;
    }

    /**
     * @param string $emailRecipient Email recipient
     * @param User $admin Admin
     * @param Group $group Group
     * @return Email
     */
    private function createGroupUserDeleteEmail(string $emailRecipient, User $admin, Group $group)
    {
        $subject = __("{0} removed you from the group {1}", $admin->profile->first_name, $group->name);
        $data = ['body' => ['admin' => $admin, 'group' => $group], 'title' => $subject];

        return new Email($emailRecipient, $subject, $data, self::TEMPLATE);
    }
}
