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
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class CommentsDeleteService
{
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
     * Delete a comment.
     *
     * @param string $id The identifier of comment to delete.
     * @param string|null $userId The user identifier who comments
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Http\Exception\NotFoundException
     * @return void
     */
    public function delete(string $id, ?string $userId = null)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The comment id is not valid.'));
        }
        if (is_null($userId) || empty($userId)) {
            throw new BadRequestException(__('The comment userId is not valid.'));
        }

        // Retrieve the comment.
        try {
            /**
             * @var \App\Model\Entity\Comment $comment
             */
            $comment = $this->Comments->get($id);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The comment does not exist.'));
        }

        // Delete the comment.
        $this->Comments->delete($comment, ['Comments.user_id' => $userId]);
        $this->_handleDeleteErrors($comment);
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
