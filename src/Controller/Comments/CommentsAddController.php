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

namespace App\Controller\Comments;

use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

class CommentsAddController extends AppController
{
    /**
     * Create a new comment for a resource.
     *
     * @param string $foreignId The identifier of the resource to add a comment to
     * @throws BadRequestException
     * @throws NotFoundException
     * @return void
     */
    public function add($foreignId = null)
    {
        if (!Validation::uuid($foreignId)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }
        $this->loadModel('Comments');

        $comment = $this->_buildAndValidateCommentEntity($foreignId);
        $this->_handleValidationErrors($comment);

        $this->Comments->save($comment);
        $this->_handleValidationErrors($comment);
        $this->success(__('The comment was successfully added.'), $comment);
    }

    /**
     * Manage validation errors.
     * @param \Cake\Datasource\EntityInterface $comment comment
     * @throws BadRequestException
     * @throws NotFoundException
     * @return void
     */
    protected function _handleValidationErrors($comment)
    {
        $errors = $comment->getErrors();
        if (!empty($errors)) {
            if (!empty($errors['foreign_id']) &&
                (
                    !empty($errors['foreign_id']['resource_exists'])
                 || !empty($errors['foreign_id']['resource_is_soft_deleted'])
                 || !empty($errors['foreign_id']['has_resource_access'])
                )) {
                throw new NotFoundException(__('The resource does not exist.'));
            }
            throw new BadRequestException(__('Could not validate comment data.'));
        }
    }

    /**
     * Build and validate comment entity from user input.
     *
     * @param string $foreignId The identifier of the instance the comment belongs to.
     * @return \Cake\Datasource\EntityInterface $comment comment entity
     */
    protected function _buildAndValidateCommentEntity($foreignId = null)
    {
        // Build entity and perform basic check.
        $comment = $this->Comments->newEntity(
            [
                'user_id' => $this->User->id(),
                'foreign_id' => $foreignId,
                'foreign_model' => 'Resource',
                'parent_id' => Hash::get($this->request->getData(), 'Comment.parent_id'),
                'content' => Hash::get($this->request->getData(), 'Comment.content'),
                'created_by' => $this->User->id(),
                'modified_by' => $this->User->id(),
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_id' => true,
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
}
