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

use App\Model\Entity\Resource;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

trait ShareEmailTrait
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
     * Send all share related notifications
     *
     * @param Event $event event
     * @param resource $resource resource
     * @param array $changes as provided by user
     * @param string $ownerId uuid of user making the changes
     * @return void
     */
    public function sendShareEmails(Event $event, Resource $resource, array $changes, string $ownerId)
    {
        // for now only handle the new share
        // e.g. we don't notify when permission changes or are removed
        $userIds = Hash::extract($changes['secrets'], '{n}.user_id');
        if (!empty($userIds)) {
            // Get the details of whoever did the changes
            $Users = TableRegistry::get('Users');
            $owner = $Users->getForEmail($ownerId);
            $this->sendNewShareEmail($event, $resource, $changes['secrets'], $userIds, $owner);
        }
    }

    /**
     * Send resource update email
     *
     * @param Event $event event
     * @param resource $resource affected resources
     * @param array $secrets new secrets
     * @param array $userIds uuids
     * @param User $owner person who did the change
     * @return void
     */
    public function sendNewShareEmail(Event $event, Resource $resource, array $secrets, array $userIds, \App\Model\Entity\User $owner)
    {
        if (!Configure::read('passbolt.email.send.password.share')) {
            return;
        }

        $Users = TableRegistry::get('Users');
        $users = $Users->find()
            ->select(['id', 'username'])
            ->where(['id IN' => $userIds])
            ->all()
            ->toArray();

        $users = Hash::combine($users, '{n}.id', '{n}.username');
        $secrets = Hash::combine($secrets, '{n}.user_id', '{n}.data');

        foreach ($users as $userId => $userName) {
            $secret = $secrets[$userId];
            $subject = __("{0} shared the password {1}", $owner->profile->first_name, $resource->name);
            $template = 'LU/resource_share';

            $data = ['body' => ['owner' => $owner, 'resource' => $resource, 'secret' => $secret], 'title' => $subject];
            $this->_send($userName, $subject, $data, $template);
        }
    }
}
