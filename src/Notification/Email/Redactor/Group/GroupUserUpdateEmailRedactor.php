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

class GroupUserUpdateEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'LU/group_user_update';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * @param \App\Model\Table\UsersTable $usersTable Users Table
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
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $group = $event->getData('group');
        $updatedGroupsUsers = $event->getData('updatedGroupsUsers');
        $modifiedById = $event->getData('userId');
        $modifiedBy = $this->usersTable->findFirstForEmail($modifiedById);

        if (empty($updatedGroupsUsers)) {
            return $emailCollection;
        }

        $emails = $this->createUpdateMembershipGroupUpdateEmails($updatedGroupsUsers, $modifiedBy, $group);

        foreach ($emails as $email) {
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * @param array $updatedGroupsUsers List of updated users
     * @param \App\Model\Entity\User $modifiedBy User who modified
     * @param \App\Model\Entity\Group $group Group
     * @return array
     */
    public function createUpdateMembershipGroupUpdateEmails(array $updatedGroupsUsers, User $modifiedBy, Group $group)
    {
        // Retrieve the users to send an email to.
        $usersIds = Hash::extract($updatedGroupsUsers, '{n}.user_id');
        $users = $this->usersTable->find()
            ->select(['id', 'username'])
            ->where(['id IN' => $usersIds])
            ->combine('id', 'username');
        $whoIsAdmin = Hash::combine($updatedGroupsUsers, '{n}.user_id', '{n}.is_admin');

        $emails = [];
        foreach ($users as $userId => $name) {
            $isAdmin = isset($whoIsAdmin[$userId]) && $whoIsAdmin[$userId];
            $emails[] = $this->createUpdateMembershipGroupUpdateEmail($name, $isAdmin, $modifiedBy, $group);
        }

        return $emails;
    }

    /**
     * Create group update email for the user whom the membership has changed
     *
     * @param string $recipient Email recipient
     * @param bool $isAdmin Is user admin
     * @param \App\Model\Entity\User $modifiedBy person who did the change
     * @param \App\Model\Entity\Group $group Group the affected group
     * @return \App\Notification\Email\Email
     */
    public function createUpdateMembershipGroupUpdateEmail(
        string $recipient,
        bool $isAdmin,
        User $modifiedBy,
        Group $group
    ): Email {
        $msg = '{0} updated your membership in the group {1}';
        $subject = __($msg, $modifiedBy->profile->first_name, $group->name);
        $data = ['body' => ['admin' => $modifiedBy, 'group' => $group, 'isAdmin' => $isAdmin], 'title' => $subject];

        return new Email($recipient, $subject, $data, self::TEMPLATE);
    }
}
