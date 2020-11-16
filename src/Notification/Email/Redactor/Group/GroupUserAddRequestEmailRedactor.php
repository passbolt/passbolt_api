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
use App\Model\Table\GroupsUsersTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class GroupUserAddRequestEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'GM/group_user_request';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @param \App\Model\Table\UsersTable|null $usersTable Users Table
     * @param \App\Model\Table\GroupsUsersTable|null $groupsUsersTable Groups Users Table
     */
    public function __construct(?UsersTable $usersTable = null, ?GroupsUsersTable $groupsUsersTable = null)
    {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
        $this->groupsUsersTable = $groupsUsersTable ?? TableRegistry::getTableLocator()->get('GroupsUsers');
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            'Model.Groups.requestGroupUsers.success',
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
        $accessControl = $event->getData('requester');
        $requestedGroupUsers = $event->getData('groupUsers');

        foreach ($requestedGroupUsers as $key => $groupUser) {
            $requestedGroupUsers[$key]->user = $this->_getSummaryUser([$groupUser->user_id])[0];
        }

        // Get group managers of group.
        $groupManagers = $this->getGroupManagers($group->id);
        $admin = $this->usersTable->findFirstForEmail($accessControl->getId());

        // Send to all group managers.
        foreach ($groupManagers as $groupManager) {
            $emailCollection->addEmail(
                $this->createGroupUserAddEmail($groupManager->user->username, $admin, $group, $requestedGroupUsers)
            );
        }

        return $emailCollection;
    }

    /**
     * @param string $groupId Group for which to get group managers
     * @return \Cake\Datasource\ResultSetInterface
     */
    private function getGroupManagers(string $groupId): ResultSetInterface
    {
        return $this->groupsUsersTable->find()
            ->where(['group_id' => $groupId, 'is_admin' => true])
            ->contain(['Users'])
            ->all();
    }

    /**
     * @param string $recipient Email of the group manager to send the notification to
     * @param \App\Model\Entity\User $admin the admin that requested the action
     * @param \App\Model\Entity\Group $group the group on which to add groupUsers
     * @param array  $groupUsers the list of groupUsers entity to request to add
     * @return \App\Notification\Email\Email
     */
    private function createGroupUserAddEmail(string $recipient, User $admin, Group $group, array $groupUsers): Email
    {
        $subject = __('{0} requested you to add members to {1}', $admin->profile->first_name, $group->name);
        $data = ['body' => [
            'admin' => $admin,
            'group' => $group,
            'groupUsers' => $groupUsers,
        ], 'title' => $subject];

        return new Email($recipient, $subject, $data, self::TEMPLATE);
    }

    /**
     * Retrieve the information of a list of users that will be used in the summary email.
     *
     * @param array|null $usersIds The list of users to retrieve the information for.
     * @return array
     */
    private function _getSummaryUser(?array $usersIds = []): array
    {
        return !empty($usersIds)
            ? $this->usersTable->find()
                ->contain('Profiles')
                ->select(['Users.id', 'Profiles.first_name', 'Profiles.last_name'])
                ->where(['Users.id IN' => $usersIds])
                ->toArray()
            : [];
    }
}
