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
 * @since         2.13.0
 */

namespace App\Notification\Email\Redactor\Comment;

use App\Controller\Comments\CommentsAddController;
use App\Model\Entity\Comment;
use App\Model\Entity\Resource;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Table\ResourcesTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class CommentAddEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE = 'LU/comment_add';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @var ResourcesTable
     */
    private $resourcesTable;

    /**
     * @param array               $config Configuration for the redactor
     * @param UsersTable|null     $usersTable Users Table
     * @param ResourcesTable|null $resourcesTable Resources Table
     */
    public function __construct(array $config = [], UsersTable $usersTable = null, ResourcesTable $resourcesTable = null)
    {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
        $this->resourcesTable = $resourcesTable ?? TableRegistry::getTableLocator()->get('Resources');
        $this->setConfig($config);
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            CommentsAddController::ADD_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param Event $event User delete event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();

        $comment = $event->getData('comment');

        // Find the users that have access to the resource (including via their groups)
        $options = ['contain' => ['Roles'], 'filter' => ['has-access' => [$comment->foreign_key]]];
        $users = $this->usersTable->findIndex(Role::USER, $options)->all();
        if (count($users) < 2) {
            // if there is nobody or just one user, give it up
            return $emailCollection;
        }

        $creator = $this->usersTable->findFirstForEmail($comment->created_by);
        $resource = $this->resourcesTable->get($comment->foreign_key);

        foreach ($users as $user) {
            if ($user->id === $comment->created_by) {
                // Don't send the notification to user that added the comment
                continue;
            }

            $emailCollection->addEmail($this->createCommentAddEmail($user, $creator, $resource, $comment));
        }

        return $emailCollection;
    }

    /**
     * @param User     $user User to notify
     * @param User     $creator Creator of the comment
     * @param resource $resource Resource on which a comment was added
     * @param Comment  $comment Comment added
     * @return Email
     */
    private function createCommentAddEmail(User $user, User $creator, Resource $resource, Comment $comment)
    {
        $subject = __("{0} commented on {1}", $creator->profile->first_name, $resource->name);
        $body = [
            'creator' => $creator,
            'comment' => $comment,
            'resource' => $resource,
            'showComment' => $this->getConfig('show.comment'),
        ];
        $data = [
            'body' => $body,
            'title' => $subject,
        ];

        return new Email($user->username, $subject, $data, self::TEMPLATE);
    }
}
