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

use App\Model\Entity\Comment;
use App\Model\Entity\Role;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

trait CommentsEmailTrait
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
     * Send comment add email
     *
     * @param Event $event event
     * @param \App\Model\Entity\Comment $comment comment
     * @return void
     */
    public function sendCommentAddEmail(Event $event, Comment $comment)
    {
        if (!EmailNotificationSettings::get('send.comment.add')) {
            return;
        }

        // Find the users that have access to the resource (including via their groups)
        $Users = TableRegistry::getTableLocator()->get('Users');
        $options = ['contain' => ['Roles'], 'filter' => ['has-access' => [$comment->foreign_key]]];
        $users = $Users->findIndex(Role::USER, $options)->all();
        if (count($users) < 2) {
            // if there is nobody or just one user, give it up
            return;
        }

        $Resources = TableRegistry::getTableLocator()->get('Resources');
        $creator = $Users->findFirstForEmail($comment->created_by);
        $resource = $Resources->get($comment->foreign_key);
        $showComment = EmailNotificationSettings::get('show.comment');

        foreach ($users as $user) {
            if ($user->id === $comment->created_by) {
                // Don't send notification to user that added the comment
                continue;
            }
            $subject = __("{0} commented on {1}", $creator->profile->first_name, $resource->name);
            $template = 'LU/comment_add';
            $body = ['creator' => $creator, 'comment' => $comment, 'resource' => $resource, 'showComment' => $showComment];
            $data = ['body' => $body, 'title' => $subject];
            $this->_send($user->username, $subject, $data, $template);
        }
    }
}
