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
 * @since         3.8.0
 */

namespace App\Service\Comments;

use App\Model\Entity\Comment;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class CommentsAddService
{
    use \Cake\ORM\Locator\LocatorAwareTrait;
    use \Cake\Event\EventDispatcherTrait;

    public const ADD_SUCCESS_EVENT_NAME = 'CommentAddService.addPost.success';

    /**
     * @var \Cake\ORM\Table
     */
    private $Comments;

    /**
     * CommentsAddService constructor.
     */
    public function __construct()
    {
        $this->Comments = TableRegistry::getTableLocator()->get('Comments');
    }

    /**
     * Create a new comment for a resource.
     *
     * @param \App\Utility\UserAccessControl $uac The user access control
     * @param string $foreignKey The identifier of the resource to add a comment to
     * @param array $data The comment data
     * @return \App\Model\Entity\Comment $comment comment entity
     * @throws \Cake\Http\Exception\InternalErrorException if the comment couldn't be saved
     */
    public function add(UserAccessControl $uac, string $foreignKey, array $data): Comment
    {
        if (!Validation::uuid($foreignKey)) {
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }

        $comment = $this->_buildAndValidateCommentEntity($uac, $foreignKey, $data);
        $this->_handleValidationErrors($comment);

        if (!$this->Comments->save($comment)) {
            $this->_handleValidationErrors($comment);
            $oops = __('Could not save the comment, please try again later.');
            throw new InternalErrorException($oops);
        }
        $this->_notifyUsers($comment);

        return $comment;
    }

    /**
     * Manage validation errors.
     *
     * @param \App\Model\Entity\Comment $comment comment
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Http\Exception\NotFoundException
     * @return void
     */
    protected function _handleValidationErrors(Comment $comment)
    {
        $errors = $comment->getErrors();
        if (!empty($errors)) {
            if (
                !empty($errors['foreign_key']) &&
                (
                    !empty($errors['foreign_key']['resource_exists']) ||
                 !empty($errors['foreign_key']['resource_is_soft_deleted']) ||
                 !empty($errors['foreign_key']['has_resource_access'])
                )
            ) {
                throw new NotFoundException(__('The resource does not exist.'));
            }
            throw new BadRequestException(__('Could not validate comment data.'));
        }
    }

    /**
     * Build and validate comment entity from user input.
     *
     * @param \App\Utility\UserAccessControl $uac The user access control
     * @param string $foreignKey The identifier of the instance the comment belongs to.
     * @param array $data The comment data
     * @return \App\Model\Entity\Comment $comment comment entity
     */
    protected function _buildAndValidateCommentEntity(UserAccessControl $uac, string $foreignKey, array $data)
    {
        // Build entity and perform basic check.
        /**
         * @var \App\Model\Entity\Comment $comment
         */
        $comment = $this->Comments->newEntity(
            [
                'user_id' => $uac->getId(),
                'foreign_key' => $foreignKey,
                'foreign_model' => 'Resource',
                'parent_id' => $data['parent_id'] ?? null,
                'content' => $data['content'] ?? null,
                'created_by' => $uac->getId(),
                'modified_by' => $uac->getId(),
            ],
            [
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_key' => true,
                    'foreign_model' => true,
                    'parent_id' => true,
                    'content' => true,
                    'created_by' => true,
                    'modified_by' => true,
                ],
            ]
        );

        $this->_handleValidationErrors($comment);

        return $comment;
    }

    /**
     * Notify users about this new comment
     *
     * @param \App\Model\Entity\Comment $comment comment entity
     * @return void
     */
    protected function _notifyUsers(Comment $comment)
    {
        $event = new Event(static::ADD_SUCCESS_EVENT_NAME, $this, [
            'comment' => $comment,
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
