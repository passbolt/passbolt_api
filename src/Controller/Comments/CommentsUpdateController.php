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
 * @since         2.0.0
 */

namespace App\Controller\Comments;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Comment;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

/**
 * @property \App\Model\Table\CommentsTable $Comments
 */
class CommentsUpdateController extends AppController
{
    /**
     * Update a comment.
     *
     * @param string $commentId The identifier of the comment to update
     * @throws \Cake\Http\Exception\ForbiddenException
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \App\Error\Exception\ValidationException
     * @return void
     */
    public function update(string $commentId)
    {
        if (!Validation::uuid($commentId)) {
            throw new BadRequestException(__('The comment id is not valid.'));
        }

        $this->loadModel('Comments');

        $comment = $this->_patchAndValidateCommentEntity($commentId);
        $this->_handleValidationErrors($comment);

        $this->Comments->save($comment, ['Comments.user_id' => $this->User->id()]);
        $this->_handleValidationErrors($comment);
        $this->success(__('The comment was successfully updated.'), $comment);
    }

    /**
     * Manage validation errors.
     *
     * @param \App\Model\Entity\Comment $comment comment
     * @throws \Cake\Http\Exception\ForbiddenException
     * @throws \App\Error\Exception\ValidationException
     * @return void
     */
    protected function _handleValidationErrors(Comment $comment)
    {
        $errors = $comment->getErrors();
        if (!empty($errors)) {
            if (!empty(Hash::get($errors, 'user_id.is_owner'))) {
                throw new ForbiddenException(__('You are not allowed to edit this comment.'));
            }
            throw new ValidationException(__('Could not validate comment data.'), $comment, $this->Comments);
        }
    }

    /**
     * Patch and validate comment entity from user input.
     *
     * @param string $commentId The comment id.
     * @return \App\Model\Entity\Comment $comment comment entity
     */
    protected function _patchAndValidateCommentEntity(string $commentId)
    {
        try {
            $comment = $this->Comments->get($commentId);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The comment does not exist.'));
        }

        $data = $this->request->getData();

        $comment = $this->Comments->patchEntity(
            $comment,
            [
                'content' => Hash::get($data, 'content'),
                'modified_by' => $this->User->id(),
            ],
            [
                'accessibleFields' => [
                    'content' => true,
                    'modified_by' => true,
                ],
            ]
        );

        $this->_handleValidationErrors($comment);

        return $comment;
    }
}
