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
namespace App\Controller\Events;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\InternalErrorException;
use EmailQueue\EmailQueue;

class EmailsListener implements EventListenerInterface
{
    /**
     * Returns a list of events this object is implementing. When the class is registered
     * in an event manager, each individual method will be associated with the respective event.
     *
     * @return array associative array or event key names pointing to the function
     * that should be called in the object when the respective event is fired
     */
    public function implementedEvents()
    {
        return [
            'UsersRegisterController.registerPost.success' => 'sendSelfRegisteredEmail',
            'UsersRecoverController.registerPost.success' => 'sendSelfRegisteredEmail',
            'UsersRecoverController.recoverPost.success' => 'sendRecoverEmail',
            'UsersAddController.addPost.success' => 'sendAdminRegisteredEmail',
            'GroupsAddController.addPost.success' => 'sendGroupUserAddEmail',
        ];
    }

    /**
     * Send Register Email
     *
     * @param Event $event event
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\AuthenticationToken $token AuthenticationToken
     * @return void
     */
    public function sendSelfRegisteredEmail(Event $event, User $user = null, AuthenticationToken $token = null)
    {
        // Notification toggle and baseline check
        if (!Configure::read('passbolt.email.send.user.create')) {
            return;
        }
        if (!isset($user)) {
            throw new InternalErrorException('User should not be empty when sending registration emails');
        }
        if (!isset($token)) {
            throw new InternalErrorException('Authentication token should not be empty when sending registration emails');
        }

        // Send notification
        $subject = __("Welcome to passbolt, {0}!", $user->profile->first_name);
        $template = 'user_register_self';
        $data = ['body' => ['user' => $user, 'token' => $token], 'title' => $subject];
        $this->_send($user->username, $subject, $data, $template);
    }

    /**
     * Send Register Email
     *
     * @param Event $event event
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\AuthenticationToken $token AuthenticationToken
     * @param \App\Model\Entity\User $admin User
     * @return void
     */
    public function sendAdminRegisteredEmail(Event $event, User $user = null, AuthenticationToken $token = null, User $admin = null)
    {
        // Notification toggle and baseline check
        if (!Configure::read('passbolt.email.send.user.create')) {
            return;
        }
        if (!isset($user)) {
            throw new InternalErrorException('User should not be empty when sending registration emails');
        }
        if (!isset($token)) {
            throw new InternalErrorException('Authentication token should not be empty when sending registration emails');
        }
        if (!isset($admin)) {
            throw new InternalErrorException('Administrator info not be empty when sending registration emails');
        }

        // Send notification
        $subject = __("Welcome to passbolt, {0}!", $user->profile->first_name);
        $template = 'user_register_admin';
        $data = ['body' => ['user' => $user, 'token' => $token, 'admin' => $admin], 'title' => $subject];
        $this->_send($user->username, $subject, $data, $template);
    }

    /**
     * Send recover Email
     *
     * @param Event $event event
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\AuthenticationToken $token AuthenticationToken
     * @return void
     */
    public function sendRecoverEmail(Event $event, User $user = null, AuthenticationToken $token = null)
    {
        // Notification toggle and baseline check
        if (!Configure::read('passbolt.email.send.user.recover')) {
            return;
        }
        if (!isset($user)) {
            throw new InternalErrorException('User should not be empty when sending recovery emails');
        }
        if (!isset($token)) {
            throw new InternalErrorException('Authentication token should not be empty when sending recovery emails');
        }

        // Send notification.
        $subject = __("Your account recovery, {0}!", $user->profile->first_name);
        $template = 'user_recover';
        $data = ['body' => ['user' => $user, 'token' => $token], 'title' => $subject];
        $this->_send($user->username, $subject, $data, $template);
    }

    /**
     * Send Group Add Email
     *
     * @param Event $event event
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\User $admin Admin
     * @param \App\Model\Entity\Group $group Group
     * @return void
     */
    public function sendGroupUserAddEmail(Event $event, User $user = null, User $admin = null, \App\Model\Entity\Group $group = null)
    {
        // Notification toggle and baseline check
        if (!Configure::read('passbolt.email.send.group.user.add')) {
            return;
        }
        if (!isset($user)) {
            throw new InternalErrorException('User should not be empty when group add notification email.');
        }
        if (!isset($admin)) {
            throw new InternalErrorException('Admin should not be empty when group add notification email.');
        }

        // Send notification.
        $subject = __("{0} added you to the group {1}", $admin->profile->first_name, $group->name);

        $template = 'group_user_add';
        $data = ['body' => ['user' => $user, 'admin' => $admin, 'group' => $group], 'title' => $subject];
        $this->_send($user->username, $subject, $data, $template);
    }

    /**
     * Send an email
     *
     * @param string $to email address
     * @param string $subject email subject
     * @param array $data email data
     * @param string $template email template
     * @return void
     */
    protected function _send(string $to, string $subject, array $data, string $template)
    {
        $options = [
            'template' => $template,
            'subject' => $subject,
            'format' => 'html',
            'config' => 'default'
        ];
        EmailQueue::enqueue($to, $data, $options);
    }
}
