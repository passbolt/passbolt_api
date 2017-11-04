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

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use EmailQueue\EmailQueue;

class EmailsListener implements EventListenerInterface
{

    public function implementedEvents()
    {
        return [
            'UsersRegisterController.registerPost.success' => 'sendRegisterEmail',
            'UserRecoverController.registerPost.success' => 'sendRegisterEmail',
            'UserRecoverController.recoverPost.success' => 'sendRecoverEmail'
        ];
    }

    /**
     * Send Register Email
     *
     * @param Event $event
     * @param null $user
     * @param null $token
     * @return void
     */
    public function sendRegisterEmail(Event $event, $user = null, $token = null)
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
        $template = 'user_register';
        $data = ['body' => ['user' => $user, 'token' => $token], 'title' => $subject];
        $this->_send($user->username, $subject, $data, $template);
    }

    /**
     * Send recover Email
     *
     * @param Event $event
     * @param null $user
     * @param null $token
     * @return void
     */
    public function sendRecoverEmail(Event $event, $user = null, $token = null)
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
     * Send an email
     *
     * @param $to
     * @param $subject
     * @param $data
     * @param $template
     * @return void
     */
    protected function _send($to, $subject, $data, $template)
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
