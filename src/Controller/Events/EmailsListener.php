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
use Cake\Log\Log;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use EmailQueue\EmailQueue;

class EmailsListener implements EventListenerInterface
{

    public function implementedEvents()
    {
        return [
            'UsersRegisterController.registerPost.success' => 'sendRegisterEmail',
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
        // Notification toggle check
        if (!Configure::read('passbolt.email.send.user.create')) {
            return;
        }

        // Check all needed data are passed from event trigger
        if (!isset($user)) {
            throw new InternalErrorException('User should not be empty when sending registration emails');
        }
        if (!isset($token)) {
            throw new InternalErrorException('Authentication token should not be empty when sending registration emails');
        }

        // Subject and templates
        $subject = __("Welcome to passbolt, {0}!", $user->profile->first_name);
        $template = 'user_register';

        // Send notification.
        $this->_send(
            $user->username,
            $subject,
            [
                'body' => [
                    'user' => $user,
                    'token' => $token
                ],
                'title' => $subject
            ],
            $template
        );
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
    protected function _send($to, $subject, $data, $template) {
        $options = [
            'template' => $template,
            'subject' => $subject,
            'format' => 'html',
            'config' => 'default'
        ];
        EmailQueue::enqueue($to, $data, $options);
    }
}