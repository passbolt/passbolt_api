<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Controller\Events\EmailTraits;

use App\Model\Entity\Group;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

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
        if (!Configure::read('passbolt.email.send.group.user.add')) {
            return;
        }
        $Users = TableRegistry::get('Users');
        $admin = $Users->getForEmail($group->created_by);

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
            $data = ['body' => ['group_user' => $group_user, 'admin' => $admin, 'group' => $group], 'title' => $subject];
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
        if (!Configure::read('passbolt.email.send.group.delete')) {
            return;
        }

        $Users = TableRegistry::get('Users');
        $admin = $Users->getForEmail($deletedBy);
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
}
