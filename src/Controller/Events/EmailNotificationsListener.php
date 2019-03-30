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
namespace App\Controller\Events;

use App\Controller\Events\EmailTraits\CommentsEmailTrait;
use App\Controller\Events\EmailTraits\GroupsEmailTrait;
use App\Controller\Events\EmailTraits\RecoveryEmailTrait;
use App\Controller\Events\EmailTraits\ResourcesEmailTrait;
use App\Controller\Events\EmailTraits\ShareEmailTrait;
use App\Controller\Events\EmailTraits\UsersEmailTrait;
use App\Utility\Purifier;
use Cake\Core\Configure;
use Cake\Event\EventListenerInterface;
use EmailQueue\EmailQueue;

class EmailNotificationsListener implements EventListenerInterface
{
    use CommentsEmailTrait;
    use GroupsEmailTrait;
    use RecoveryEmailTrait;
    use ResourcesEmailTrait;
    use ShareEmailTrait;
    use UsersEmailTrait;

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
            'CommentAddController.addPost.success' => 'sendCommentAddEmail',
            'Model.Groups.create.success' => 'sendGroupUserAddEmail',
            'Model.Groups.requestGroupUsers.success' => 'sendGroupUsersRequestEmail',
            'GroupsDeleteController.delete.success' => 'sendGroupDeleteEmail',
            'GroupsUpdateController.update.success' => 'sendGroupUpdateEmail',
            'UsersDeleteController.delete.success' => 'sendUserDeleteEmail',
            'UsersRecoverController.recoverPost.success' => 'sendRecoverEmail',
            'UsersRecoverController.registerPost.success' => 'sendSelfRegisteredEmail',
            'Model.Users.afterRegister.success' => 'sendRegisteredEmail',
            'ResourcesAddController.addPost.success' => 'sendResourceCreateEmail',
            'ResourcesDeleteController.delete.success' => 'sendResourceDeleteEmail',
            'ResourcesUpdateController.update.success' => 'sendResourceUpdateEmail',
            'ShareController.share.success' => 'sendShareEmails',
        ];
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
        $data['body']['fullBaseUrl'] = Configure::read('App.fullBaseUrl');
        $options = [
            'template' => $template,
            'subject' => Purifier::clean($subject),
            'format' => 'html',
            'config' => 'default',
            'headers' => ['Auto-Submitted' => 'auto-generated']
        ];
        EmailQueue::enqueue($to, $data, $options);
    }
}
