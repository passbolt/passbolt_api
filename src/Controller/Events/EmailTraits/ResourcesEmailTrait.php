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

use App\Model\Entity\Resource;
use App\Model\Entity\Role;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

trait ResourcesEmailTrait
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
     * Send resource create email
     *
     * @param Event $event event
     * @param \App\Model\Entity\Resource $resource resource
     * @return void
     */
    public function sendResourceCreateEmail(Event $event, Resource $resource)
    {
        if (!EmailNotificationSettings::get('send.password.create')) {
            return;
        }
        $Users = TableRegistry::getTableLocator()->get('Users');
        $user = $Users->findFirstForEmail($resource->created_by);
        $showUsername = EmailNotificationSettings::get('show.username');
        $showUri = EmailNotificationSettings::get('show.uri');
        $showDescription = EmailNotificationSettings::get('show.description');
        $showSecret = EmailNotificationSettings::get('show.secret');
        $subject = __("You added the password {0}", $resource->name);
        $template = 'LU/resource_create';
        $data = [
            'body' => [
                'user' => $user,
                'resource' => $resource,
                'showUsername' => $showUsername,
                'showUri' => $showUri,
                'showDescription' => $showDescription,
                'showSecret' => $showSecret
            ], 'title' => $subject
        ];
        $this->_send($user->username, $subject, $data, $template);
    }
    /**
     * Send resource update email
     *
     * @param Event $event event
     * @param \App\Model\Entity\Resource $resource resource
     * @return void
     */
    public function sendResourceUpdateEmail(Event $event, Resource $resource)
    {
        if (!EmailNotificationSettings::get('send.password.update')) {
            return;
        }
        $Users = TableRegistry::getTableLocator()->get('Users');
        $owner = $Users->findFirstForEmail($resource->modified_by);
        $showUsername = EmailNotificationSettings::get('show.username');
        $showUri = EmailNotificationSettings::get('show.uri');
        $showDescription = EmailNotificationSettings::get('show.description');
        $showSecret = EmailNotificationSettings::get('show.secret');
        $subject = __("{0} edited the password {1}", $owner->profile->first_name, $resource->name);
        $template = 'LU/resource_update';

        // Get the users that can access this resource
        $Users = TableRegistry::getTableLocator()->get('Users');
        $options = ['contain' => ['Roles'], 'filter' => ['has-access' => [$resource->id]]];
        $users = $Users->findIndex(Role::USER, $options)->all();

        // Send emails to everybody that can see the resource
        foreach ($users as $user) {
            $data = ['body' => [
                'user' => $owner,
                'resource' => $resource,
                'showUsername' => $showUsername,
                'showUri' => $showUri,
                'showDescription' => $showDescription,
                'showSecret' => $showSecret
            ], 'title' => $subject];
            $this->_send($user->username, $subject, $data, $template);
        }
    }

    /**
     * Send resource delete email
     *
     * @param Event $event event
     * @param \App\Model\Entity\Resource $resource resource
     * @param string $deletedBy uuid of the user who deleted the resource
     * @param ResultSetInterface $users list of users who had access to the resource before
     * @return void
     */
    public function sendResourceDeleteEmail(Event $event, Resource $resource, string $deletedBy, ResultSetInterface $users)
    {
        if (!EmailNotificationSettings::get('send.password.delete')) {
            return;
        }
        $Users = TableRegistry::getTableLocator()->get('Users');
        $admin = $Users->findFirstForEmail($deletedBy);

        // if there is nobody or just one user, give it up
        if (count($users) < 2) {
            return;
        }

        $showUsername = EmailNotificationSettings::get('show.username');
        $showUri = EmailNotificationSettings::get('show.uri');
        $showDescription = EmailNotificationSettings::get('show.description');
        $subject = __("{0} deleted the password {1}", $admin->profile->first_name, $resource->name);
        $template = 'LU/resource_delete';
        foreach ($users as $user) {
            if ($user->id === $deletedBy) {
                continue;
            }
            $data = ['body' => [
                'user' => $admin,
                'resource' => $resource,
                'showUsername' => $showUsername,
                'showUri' => $showUri,
                'showDescription' => $showDescription,
            ], 'title' => $subject];
            $this->_send($user->username, $subject, $data, $template);
        }
    }
}
