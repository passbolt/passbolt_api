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

namespace App\Controller\Comments;

use App\Controller\AppController;
use App\Model\Entity\Comment;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

class CommentsAddController extends AppController
{
    /**
     * Create a new comment for a resource.
     *
     * @param string $foreignKey The identifier of the resource to add a comment to
     * @throws BadRequestException
     * @throws NotFoundException
     * @return void
     */
    public function addPost($foreignKey)
    {
        if (!Validation::uuid($foreignKey)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }
        $this->loadModel('Comments');

        $comment = $this->_buildAndValidateCommentEntity($foreignKey);
        $this->_handleValidationErrors($comment);

        if (!$this->Comments->save($comment)) {
            $this->_handleValidationErrors($comment);
            $oops = __('Could not save the comment, please try again later.');
            throw new InternalErrorException($oops);
        }
        $this->_notifyUsers($comment);
        $this->success(__('The comment was successfully added.'), $comment);
    }

    /**
     * Manage validation errors.
     *
     * @param \App\Model\Entity\Comment $comment comment
     * @throws BadRequestException
     * @throws NotFoundException
     * @return void
     */
    protected function _handleValidationErrors(Comment $comment)
    {
        $errors = $comment->getErrors();
        if (!empty($errors)) {
            if (!empty($errors['foreign_key']) &&
                (
                    !empty($errors['foreign_key']['resource_exists'])
                 || !empty($errors['foreign_key']['resource_is_soft_deleted'])
                 || !empty($errors['foreign_key']['has_resource_access'])
                )) {
                throw new NotFoundException(__('The resource does not exist.'));
            }
            throw new BadRequestException(__('Could not validate comment data.'));
        }
    }

    /**
     * Build and validate comment entity from user input.
     *
     * @param string $foreignKey The identifier of the instance the comment belongs to.
     * @return \App\Model\Entity\Comment $comment comment entity
     */
    protected function _buildAndValidateCommentEntity(string $foreignKey = null)
    {
        $data = $this->_formatRequestData();

        // Build entity and perform basic check.
        $comment = $this->Comments->newEntity(
            [
                'user_id' => $this->User->id(),
                'foreign_key' => $foreignKey,
                'foreign_model' => 'Resource',
                'parent_id' => Hash::get($data, 'parent_id'),
                'content' => Hash::get($data, 'content'),
                'created_by' => $this->User->id(),
                'modified_by' => $this->User->id(),
            ],
            [
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_key' => true,
                    'foreign_model' => true,
                    'parent_id' => true,
                    'content' => true,
                    'created_by' => true,
                    'modified_by' => true
                ],
            ]
        );

        $this->_handleValidationErrors($comment);

        return $comment;
    }

    /**
     * Format request data formatted for API v1 to API v2 format
     *
     * @return array data
     */
    protected function _formatRequestData()
    {
        $output = [];
        $data = $this->request->getData();

        // API v2 additional checks and error (was silent before)
        if ($this->getApiVersion() == 'v2') {
            $output = $data;
        } else {
            if (isset($data['Comment'])) {
                $output = $data['Comment'];
            }
        }

        return $output;
    }

    /**
     * Notify users about this new comment
     *
     * @param \App\Model\Entity\Comment $comment comment entity
     * @return void
     */
    protected function _notifyUsers($comment)
    {
        $event = new Event('CommentAddController.addPost.success', $this, [
            'comment' => $comment
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
