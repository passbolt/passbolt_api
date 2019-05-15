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

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

trait UsersEmailTrait
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
     * Send Register Email
     *
     * @param Event $event event
     * @param \App\Model\Entity\User $user user to send the mail to
     * @param \App\Model\Entity\AuthenticationToken $token AuthenticationToken
     * @param string $adminId admin identifier
     * @return void
     */
    public function sendRegisteredEmail(Event $event, User $user, AuthenticationToken $token, string $adminId = null)
    {
        if (!isset($adminId)) {
            $this->sendSelfRegisteredEmail($event, $user, $token);
        } else {
            $this->sendAdminRegisteredEmail($event, $user, $token, $adminId);
        }
    }

    /**
     * Send Register Email
     *
     * @param Event $event event
     * @param \App\Model\Entity\User $user user to send the mail to
     * @param \App\Model\Entity\AuthenticationToken $token AuthenticationToken
     * @return void
     */
    public function sendSelfRegisteredEmail(Event $event, User $user, AuthenticationToken $token)
    {
        if (!EmailNotificationSettings::get('send.user.create')) {
            return;
        }
        $Users = TableRegistry::getTableLocator()->get('Users');
        $user = $Users->findFirstForEmail($user->id);
        $subject = __("Welcome to passbolt, {0}!", $user->profile->first_name);
        $template = 'AN/user_register_self';
        $data = ['body' => ['user' => $user, 'token' => $token], 'title' => $subject];
        $this->_send($user->username, $subject, $data, $template);
    }

    /**
     * Send Register Email
     *
     * @param Event $event event
     * @param \App\Model\Entity\User $user user to send the mail to
     * @param \App\Model\Entity\AuthenticationToken $token AuthenticationToken
     * @param string $adminId User uuid
     * @return void
     */
    public function sendAdminRegisteredEmail(Event $event, User $user, AuthenticationToken $token, string $adminId)
    {
        if (!EmailNotificationSettings::get('send.user.create')) {
            return;
        }

        $Users = TableRegistry::getTableLocator()->get('Users');
        $admin = $Users->findFirstForEmail($adminId);
        $subject = __("Welcome to passbolt, {0}!", $user->profile->first_name);
        $template = 'AN/user_register_admin';
        $data = ['body' => ['user' => $user, 'token' => $token, 'admin' => $admin], 'title' => $subject];
        $this->_send($user->username, $subject, $data, $template);
    }

    /**
     * Send user delete email
     *
     * @param Event $event event
     * @param User $user user that was deleted
     * @param array $groupsIds that was deleted
     * @param string $deletedById User uuid of the admin who delete the user
     * @return void
     */
    public function sendUserDeleteEmail(Event $event, User $user, array $groupsIds, string $deletedById)
    {
        if (!EmailNotificationSettings::get('send.group.user.delete')) {
            return;
        }
        if (empty($groupsIds)) {
            return;
        }

        $Users = TableRegistry::getTableLocator()->get('Users');
        $deletedBy = $Users->findFirstForEmail($deletedById);
        $subject = __('{0} deleted user {1}', $deletedBy->profile->first_name, $user->profile->first_name);
        $template = 'GM/user_delete';

        $GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $groupManagers = $GroupsUsers->find()
            ->select()
            ->contain(['Users', 'Groups'])
            ->where(['group_id IN' => $groupsIds, 'is_admin' => 1])
            ->all();

        $toNotify = [];
        foreach ($groupManagers as $groupManager) {
            $toNotify[$groupManager->user->username][] = $groupManager->group;
        }
        foreach ($toNotify as $username => $groups) {
            $data = ['body' => ['user' => $user, 'groups' => $groups, 'admin' => $deletedBy], 'title' => $subject];
            $this->_send($username, $subject, $data, $template);
        }
    }
}
