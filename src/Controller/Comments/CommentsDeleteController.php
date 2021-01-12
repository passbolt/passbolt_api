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
use App\Model\Entity\Comment;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

/**
 * @property \App\Model\Table\CommentsTable $Comments
 */
class CommentsDeleteController extends AppController
{
    /**
     * Delete a comment.
     *
     * @param string $id The identifier of comment to delete.
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Http\Exception\NotFoundException
     * @return void
     */
    public function delete(string $id)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The comment id is not valid.'));
        }
        $this->loadModel('Comments');

        // Retrieve the comment.
        try {
            $comment = $this->Comments->get($id);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The comment does not exist.'));
        }

        // Delete the comment.
        $this->Comments->delete($comment, ['Comments.user_id' => $this->User->id()]);
        $this->_handleDeleteErrors($comment);

        $this->success(__('The comment was deleted.'));
    }

    /**
     * Manage delete errors
     *
     * @param \App\Model\Entity\Comment $comment comment
     * @return void
     */
    private function _handleDeleteErrors(Comment $comment)
    {
        $errors = $comment->getErrors();
        if (!empty($errors)) {
            if (isset($errors['user_id']['is_owner'])) {
                throw new NotFoundException(__('The comment does not exist.'));
            }
            throw new BadRequestException(__('Could not delete comment.'));
        }
    }
}
