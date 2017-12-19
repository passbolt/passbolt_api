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

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

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
     * @return void
     */
    public function sendSelfRegisteredEmail(Event $event, User $user, AuthenticationToken $token)
    {
        if (!Configure::read('passbolt.email.send.user.create')) {
            return;
        }

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
        if (Configure::read('passbolt.email.send.user.create') === false) {
            return;
        }

        $Users = TableRegistry::get('Users');
        $admin = $Users->getForEmail($adminId);
        $subject = __("Welcome to passbolt, {0}!", $user->profile->first_name);
        $template = 'AN/user_register_admin';
        $data = ['body' => ['user' => $user, 'token' => $token, 'admin' => $admin], 'title' => $subject];
        $this->_send($user->username, $subject, $data, $template);
    }

    /**
     * Send Register Email
     *
     * @param Event $event event
     * @param \App\Model\Entity\User $user user that was deleted
     * @param array $groupsIds that was deleted
     * @param string $deletedById User uuid of the admin who delete the user
     * @return void
     */
    public function sendUserDeleteEmail(Event $event, User $user, array $groupsIds, string $deletedById)
    {
        if (Configure::read('passbolt.email.send.user.delete') === false) {
            return;
        }
        if (empty($groupsIds)) {
            return;
        }

        $Users = TableRegistry::get('Users');
        $deletedBy = $Users->getForEmail($deletedById);
        $subject = __('{0} deleted user {1}', $deletedBy->profile->first_name, $user->profile->first_name);
        $template = 'GM/user_delete';

        $GroupsUsers = TableRegistry::get('GroupsUsers');
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
