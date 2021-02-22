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

use App\Controller\Groups\GroupsDeleteController;
use App\Model\Entity\Group;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupDeleteEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'LU/group_delete';

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
            GroupsDeleteController::DELETE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Model\Entity\Group $resource */
        $group = $event->getData('group');
        $deletedBy = $event->getData('userId');

        $admin = $this->usersTable->findFirstForEmail($deletedBy);
        $usersIds = Hash::extract($group->groups_users, '{n}.user_id');
        $userNames = $this->usersTable->find()->select(['id', 'username'])->where(['id IN' => $usersIds])->all();
        $userNames = Hash::combine($userNames->toArray(), '{n}.id', '{n}.username');

        foreach ($usersIds as $userId) {
            // Don't send notification if user is the one who deleted the group
            if ($userId === $deletedBy) {
                continue;
            }
            $emailCollection->addEmail($this->createGroupDeleteEmail($userNames[$userId], $admin, $group));
        }

        return $emailCollection;
    }

    /**
     * @param string $emailRecipient Email recipient
     * @param \App\Model\Entity\User $admin Admin
     * @param \App\Model\Entity\Group $group Group
     * @return \App\Notification\Email\Email
     */
    private function createGroupDeleteEmail(string $emailRecipient, User $admin, Group $group): Email
    {
        $subject = __('{0} deleted the group {1}', $admin->profile->first_name, $group->name);
        $data = ['body' => ['admin' => $admin, 'group' => $group], 'title' => $subject];

        return new Email($emailRecipient, $subject, $data, self::TEMPLATE);
    }
}
