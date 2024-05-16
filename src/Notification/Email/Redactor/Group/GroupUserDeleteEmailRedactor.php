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
use App\Utility\Purifier;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Locale\Service\LocaleService;

/**
 * Class GroupUserDeleteEmailRedactor
 * Email sent to the user when they are removed from a group
 */
class GroupUserDeleteEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'LU/group_user_delete';

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
        return 'send.group.user.delete';
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
        $entitiesChanges = $event->getData('entitiesChanges');
        $removedGroupsUsers = $entitiesChanges->getDeletedEntities(GroupsUser::class);
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
     * @param \App\Model\Entity\Group $group the affected group
     * @param array $removedGroupsUsers List of removed users
     * @param \App\Model\Entity\User $modifiedBy person who did the change
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
        $users = $this->usersTable->find('locale')
            ->find('notDisabled')
            ->where(['Users.id IN' => $usersIds]);

        foreach ($users as $user) {
            $emails[] = $this->createGroupUserDeleteEmail($user, $modifiedBy, $group);
        }

        return $emails;
    }

    /**
     * @param \App\Model\Entity\User $recipient User recipient
     * @param \App\Model\Entity\User $admin Admin
     * @param \App\Model\Entity\Group $group Group
     * @return \App\Notification\Email\Email
     */
    private function createGroupUserDeleteEmail(User $recipient, User $admin, Group $group): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($admin, $group) {
                return __(
                    '{0} removed you from the group {1}',
                    Purifier::clean($admin['profile']['first_name']),
                    Purifier::clean($group['name'])
                );
            }
        );
        $data = ['body' => ['admin' => $admin, 'group' => $group], 'title' => $subject];

        return new Email($recipient, $subject, $data, self::TEMPLATE);
    }
}
