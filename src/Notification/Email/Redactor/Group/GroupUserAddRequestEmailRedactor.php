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

    const TEMPLATE = 'GM/group_user_request';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @var GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @param UsersTable            $usersTable Users Table
     * @param GroupsUsersTable|null $groupsUsersTable Groups Users Table
     */
    public function __construct(UsersTable $usersTable = null, GroupsUsersTable $groupsUsersTable = null)
    {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
        $this->groupsUsersTable = $groupsUsersTable ?? TableRegistry::getTableLocator()->get('GroupsUsers');
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'Model.Groups.requestGroupUsers.success',
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
        $accessControl = $event->getData('requester');
        $requestedGroupUsers = $event->getData('groupUsers');

        foreach ($requestedGroupUsers as $key => $groupUser) {
            $requestedGroupUsers[$key]->user = $this->_getSummaryUser([$groupUser->user_id])[0];
        }

        // Get group managers of group.
        $groupManagers = $this->getGroupManagers($group->id);
        $admin = $this->usersTable->findFirstForEmail($accessControl->userId());

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
     * @return ResultSetInterface
     */
    private function getGroupManagers(string $groupId)
    {
        return $this->groupsUsersTable->find()->where(['group_id' => $groupId, 'is_admin' => true])->contain(['Users'])->all();
    }

    /**
     * @param string $emailRecipient Email of the group manager to send the notification to
     * @param User   $admin the admin that requested the action
     * @param Group  $group the group on which to add groupUsers
     * @param array  $requestedGroupUsers the list of groupUsers entity to request to add
     * @return Email
     */
    private function createGroupUserAddEmail(string $emailRecipient, User $admin, Group $group, array $requestedGroupUsers)
    {
        $subject = __("{0} requested you to add members to {1}", $admin->profile->first_name, $group->name);
        $data = ['body' => [
            'admin' => $admin,
            'group' => $group,
            'groupUsers' => $requestedGroupUsers,
        ], 'title' => $subject];

        return new Email($emailRecipient, $subject, $data, self::TEMPLATE);
    }

    /**
     * Retrieve the information of a list of users that will be used in the summary email.
     *
     * @param array $usersIds The list of users to retrieve the information for.
     * @return array
     */
    private function _getSummaryUser(array $usersIds = [])
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
