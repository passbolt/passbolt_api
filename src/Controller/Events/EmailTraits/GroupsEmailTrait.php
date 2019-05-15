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
 * @since         2.0.0
 */
namespace App\Controller\Events\EmailTraits;

use App\Model\Entity\Group;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

trait GroupsEmailTrait
{
    /**
     * Send an email
     *
     * @param string $to email address
     * @param string $subject email subject
     * @param array $data email data
     * @param string $template email template
     * @return void
     */
    abstract protected function _send(string $to, string $subject, array $data, string $template);

    /**
     * Send Group Add Email
     *
     * @param Event $event event
     * @param \App\Model\Entity\Group $group Group
     * @return void
     */
    public function sendGroupUserAddEmail(Event $event, Group $group)
    {
        if (!EmailNotificationSettings::get('send.group.user.add')) {
            return;
        }

        $Users = TableRegistry::getTableLocator()->get('Users');
        $admin = $Users->findFirstForEmail($group->created_by);

        $userIds = Hash::extract($group->groups_users, '{n}.user_id');
        $userNames = $Users->find()->select(['id', 'username'])->where(['id IN' => $userIds])->all();
        $userNames = Hash::combine($userNames->toArray(), '{n}.id', '{n}.username');
        $template = 'LU/group_user_add';

        foreach ($group->groups_users as $group_user) {
            // Don't send notification if the user added added themselves
            if ($group_user->user_id === $group->created_by) {
                continue;
            }
            $subject = __("{0} added you to the group {1}", $admin->profile->first_name, $group->name);
            $data = ['body' => ['isAdmin' => $group_user->is_admin, 'admin' => $admin, 'group' => $group], 'title' => $subject];
            $this->_send($userNames[$group_user->user_id], $subject, $data, $template);
        }
    }

    /**
     * Send Group delete Email
     *
     * @param Event $event event
     * @param \App\Model\Entity\Group $group Group
     * @param string $deletedBy user uuid
     * @return void
     */
    public function sendGroupDeleteEmail(Event $event, Group $group, string $deletedBy)
    {
        if (!EmailNotificationSettings::get('send.group.delete')) {
            return;
        }

        $Users = TableRegistry::getTableLocator()->get('Users');
        $admin = $Users->findFirstForEmail($deletedBy);
        $usersIds = Hash::extract($group->groups_users, '{n}.user_id');
        $userNames = $Users->find()->select(['id', 'username'])->where(['id IN' => $usersIds])->all();
        $userNames = Hash::combine($userNames->toArray(), '{n}.id', '{n}.username');
        $template = 'LU/group_delete';

        foreach ($usersIds as $userId) {
            // Don't send notification if user is the one who deleted the group
            if ($userId === $deletedBy) {
                continue;
            }
            $subject = __("{0} deleted the group {1}", $admin->profile->first_name, $group->name);
            $data = ['body' => ['admin' => $admin, 'group' => $group], 'title' => $subject];
            $this->_send($userNames[$userId], $subject, $data, $template);
        }
    }

    /**
     * Send Group update Email
     *
     * @param Event $event event
     * @param \App\Model\Entity\Group $group the affected group
     * @param array $addedGroupsUsers the list of added groups users
     * @param array $updatedGroupsUsers the list of updated groups users
     * @param array $removedGroupsUsers the list of removed groups users
     * @param string $modifiedById person who did the change
     * @return void
     */
    public function sendGroupUpdateEmail(
        Event $event,
        Group $group,
        array $addedGroupsUsers,
        array $updatedGroupsUsers,
        array $removedGroupsUsers,
        string $modifiedById
    ) {
        // Get the details of whoever did the changes
        $Users = TableRegistry::getTableLocator()->get('Users');
        $modifiedBy = $Users->findFirstForEmail($modifiedById);

        $this->sendAddUserGroupUpdateEmail($event, $group, $addedGroupsUsers, $modifiedBy);
        $this->sendUpdateMembershipGroupUpdateEmail($event, $group, $updatedGroupsUsers, $modifiedBy);
        $this->sendDeleteUserGroupUpdateEmail($event, $group, $removedGroupsUsers, $modifiedBy);
        $this->sendSummaryAdminGroupUpdateEmail(
            $event,
            $group,
            $addedGroupsUsers,
            $updatedGroupsUsers,
            $removedGroupsUsers,
            $modifiedBy
        );
    }

    /**
     * Send group update email to the new members
     *
     * @param Event $event event
     * @param Group $group the affected group
     * @param array $addedGroupsUsers the list of added groups users
     * @param User $modifiedBy person who did the change
     * @return void
     */
    public function sendAddUserGroupUpdateEmail(Event $event, Group $group, array $addedGroupsUsers, User $modifiedBy)
    {
        if (empty($addedGroupsUsers) || !EmailNotificationSettings::get('send.group.user.add')) {
            return;
        }

        // Retrieve the users to send an email to.
        $usersIds = Hash::extract($addedGroupsUsers, '{n}.user_id');
        $Users = TableRegistry::getTableLocator()->get('Users');
        $users = $Users->find()
            ->select(['id', 'username'])
            ->where(['id IN' => $usersIds])
            ->combine('id', 'username');
        $whoIsAdmin = Hash::combine($addedGroupsUsers, '{n}.user_id', '{n}.is_admin');

        foreach ($users as $userId => $userName) {
            $isAdmin = isset($whoIsAdmin[$userId]) && $whoIsAdmin[$userId];
            $subject = __("{0} added you to the group {1}", $modifiedBy->profile->first_name, $group->name);
            $template = 'LU/group_user_add';

            $data = ['body' => ['admin' => $modifiedBy, 'group' => $group, 'isAdmin' => $isAdmin], 'title' => $subject];
            $this->_send($userName, $subject, $data, $template);
        }
    }

    /**
     * Send group update email to the user whom the membership has changed
     *
     * @param Event $event event
     * @param Group $group the affected group
     * @param array $updatedGroupsUsers the list of updated groups users
     * @param User $modifiedBy person who did the change
     * @return void
     */
    public function sendUpdateMembershipGroupUpdateEmail(Event $event, Group $group, array $updatedGroupsUsers, User $modifiedBy)
    {
        if (empty($updatedGroupsUsers) || !EmailNotificationSettings::get('send.group.user.update')) {
            return;
        }

        // Retrieve the users to send an email to.
        $usersIds = Hash::extract($updatedGroupsUsers, '{n}.user_id');
        $Users = TableRegistry::getTableLocator()->get('Users');
        $users = $Users->find()
            ->select(['id', 'username'])
            ->where(['id IN' => $usersIds])
            ->combine('id', 'username');
        $whoIsAdmin = Hash::combine($updatedGroupsUsers, '{n}.user_id', '{n}.is_admin');

        foreach ($users as $userId => $userName) {
            $isAdmin = isset($whoIsAdmin[$userId]) && $whoIsAdmin[$userId];
            $subject = __("{0} updated your membership in the group {1}", $modifiedBy->profile->first_name, $group->name);
            $template = 'LU/group_user_update';

            $data = ['body' => ['admin' => $modifiedBy, 'group' => $group, 'isAdmin' => $isAdmin], 'title' => $subject];
            $this->_send($userName, $subject, $data, $template);
        }
    }

    /**
     * Send group update email to the deleted members
     *
     * @param Event $event event
     * @param Group $group the affected group
     * @param array $removedGroupsUsers the list of removed groups users
     * @param User $modifiedBy person who did the change
     * @return void
     */
    public function sendDeleteUserGroupUpdateEmail(Event $event, Group $group, array $removedGroupsUsers, User $modifiedBy)
    {
        if (empty($removedGroupsUsers) || !EmailNotificationSettings::get('send.group.user.delete')) {
            return;
        }

        // Retrieve the users to send an email to.
        $usersIds = Hash::extract($removedGroupsUsers, '{n}.user_id');
        $Users = TableRegistry::getTableLocator()->get('Users');
        $users = $Users->find()
            ->select(['id', 'username'])
            ->where(['id IN' => $usersIds])
            ->combine('id', 'username');

        foreach ($users as $userId => $userName) {
            $subject = __("{0} removed you from the group {1}", $modifiedBy->profile->first_name, $group->name);
            $template = 'LU/group_user_delete';

            $data = ['body' => ['admin' => $modifiedBy, 'group' => $group], 'title' => $subject];
            $this->_send($userName, $subject, $data, $template);
        }
    }

    /**
     * Send group update email to the other group managers of the group
     *
     * @param Event $event event
     * @param Group $group the affected group
     * @param array $addedGroupsUsers the list of added groups users
     * @param array $updatedGroupsUsers the list of updated groups users
     * @param array $removedGroupsUsers the list of removed groups users
     * @param User $modifiedBy person who did the change
     * @return void
     */
    public function sendSummaryAdminGroupUpdateEmail(
        Event $event,
        Group $group,
        array $addedGroupsUsers,
        array $updatedGroupsUsers,
        array $removedGroupsUsers,
        User $modifiedBy
    ) {
        if ((empty($addedGroupsUsers) && empty($updatedGroupsUsers) && empty($removedGroupsUsers))
            || !EmailNotificationSettings::get('send.group.manager.update')) {
            return;
        }

        $Users = TableRegistry::getTableLocator()->get('Users');
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
        $groupManagers = $Users->find()
            ->select(['Users.username'])
            ->innerJoinWith('GroupsUsers')
            ->where([
                'GroupsUsers.group_id' => $group->id,
                'GroupsUsers.is_admin' => true,
                'GroupsUsers.user_id NOT IN' => $excludeUsersIds,
            ])
            ->toArray();

        // If no group managers to notify
        if (empty($groupManagers)) {
            return;
        }

        // Retrieve the user information corresponding to the users impacted by the changes.
        $addedUsers = $this->_getSummaryUser($addedUsersIds);
        $removedUsers = $this->_getSummaryUser($removedUsersIds);
        $updatedUsers = $this->_getSummaryUser($updatedUsersIds);

        // Send the email to all the group managers.
        foreach ($groupManagers as $groupManager) {
            $subject = __("{0} updated the group {1}", $modifiedBy->profile->first_name, $group->name);
            $template = 'GM/group_user_update';
            $data = ['body' => [
                'admin' => $modifiedBy,
                'group' => $group,
                'addedUsers' => $addedUsers,
                'updatedUsers' => $updatedUsers,
                'removedUsers' => $removedUsers,
                'whoIsAdmin' => $whoIsAdmin
            ], 'title' => $subject];
            $this->_send($groupManager->username, $subject, $data, $template);
        }
    }

    /**
     * Send a group user add request to the group managers of a group.
     * @param Event $event The event
     *   - requester, the admin that requested the action
     *   - group, the group on which to add groupUsers
     *   - groupUsers, the list of groupUsers entity to request to add
     * @return void
     */
    public function sendGroupUsersRequestEmail(Event $event)
    {
        $data = $event->getData();
        $accessControl = $data['requester'];
        $group = $data['group'];
        $requestedGroupUsers = $data['groupUsers'];

        foreach ($requestedGroupUsers as $key => $groupUser) {
            $requestedGroupUsers[$key]->user = $this->_getSummaryUser([$groupUser->user_id])[0];
        }

        // Get group managers of group.
        $GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $adminGroupUsers = $GroupsUsers->find()->where(['group_id' => $group->id, 'is_admin' => true])->contain(['Users'])->all();

        $Users = TableRegistry::getTableLocator()->get('Users');
        $admin = $Users->findFirstForEmail($accessControl->userId());

        $subject = __("{0} requested you to add members to {1}", $admin->profile->first_name, $group->name);
        $template = 'GM/group_user_request';
        $data = ['body' => [
            'admin' => $admin,
            'group' => $group,
            'groupUsers' => $requestedGroupUsers,
        ], 'title' => $subject];

        // Send to all group managers.
        foreach ($adminGroupUsers as $adminGroupUser) {
            $this->_send($adminGroupUser->user->username, $subject, $data, $template);
        }
    }

    /**
     * Retrieve the information of a list of users that will be used in the summary email.
     *
     * @param array $usersIds The list of users to retrieve the information for.
     * @return array
     */
    protected function _getSummaryUser(array $usersIds = [])
    {
        if (empty($usersIds)) {
            return [];
        }

        $Users = TableRegistry::getTableLocator()->get('Users');

        return $Users->find()
            ->contain('Profiles')
            ->select(['Users.id', 'Profiles.first_name', 'Profiles.last_name'])
            ->where(['Users.id IN' => $usersIds])
            ->toArray();
    }
}
