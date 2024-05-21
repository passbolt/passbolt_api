<?php
declare(strict_types=1);

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
use App\Service\Comments\CommentsAddService;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Service\LocaleService;

class CommentAddEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'LU/comment_add';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $resourcesTable;

    /**
     * @param array|null $config Configuration for the redactor
     * @param \App\Model\Table\UsersTable|null $usersTable Users Table
     * @param \App\Model\Table\ResourcesTable|null $resourcesTable Resources Table
     */
    public function __construct(
        ?array $config = [],
        ?UsersTable $usersTable = null,
        ?ResourcesTable $resourcesTable = null
    ) {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
        $this->resourcesTable = $resourcesTable ?? TableRegistry::getTableLocator()->get('Resources');
        $this->setConfig($config);
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            CommentsAddService::ADD_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Model\Entity\Comment $comment */
        $comment = $event->getData('comment');

        // Find the users that have access to the resource (including via their groups)
        $options = ['contain' => ['role'], 'filter' => ['has-access' => [$comment->foreign_key]]];
        $users = $this->usersTable
            ->findIndex(Role::USER, $options)
            ->find('locale')
            ->find('notDisabled')
            ->all();

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
     * @param \App\Model\Entity\User $recipient User to notify
     * @param \App\Model\Entity\User $creator Creator of the comment
     * @param \App\Model\Entity\Resource $resource Resource on which a comment was added
     * @param \App\Model\Entity\Comment $comment Comment added
     * @return \App\Notification\Email\Email
     */
    private function createCommentAddEmail(User $recipient, User $creator, Resource $resource, Comment $comment): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($creator, $resource) {
                return __('{0} commented on {1}', $creator->profile->first_name, $resource->name);
            }
        );
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

        return new Email($recipient, $subject, $data, self::TEMPLATE);
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.comment.add';
    }
}
