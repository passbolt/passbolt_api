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

use App\Error\Exception\ValidationException;
use App\Model\Entity\Comment;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

class CommentsUpdateService
{
    use \Cake\ORM\Locator\LocatorAwareTrait;
    use \Cake\Event\EventDispatcherTrait;

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
     * @param string $userId The currently logged in user ID
     * @param string $commentId The comment ID
     * @param string $requestDataContent The comment 'content' data
     * @return \App\Model\Entity\Comment $comment comment entity
     * @throws \Cake\Http\Exception\BadRequestException if the validation failed
     */
    public function update(string $userId, string $commentId, string $requestDataContent)
    {
        if (!Validation::uuid($commentId)) {
            throw new BadRequestException(__('The comment id is not valid.'));
        }

        $comment = $this->_patchAndValidateCommentEntity($userId, $commentId, $requestDataContent);
        $this->_handleValidationErrors($comment);

        $this->Comments->save($comment, ['Comments.user_id' => $userId]);
        $this->_handleValidationErrors($comment);

        return $comment;
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
     * @param string $userId The currently logged in user ID
     * @param string $commentId The comment ID
     * @param string $requestDataContent The comment 'content' data
     * @return \App\Model\Entity\Comment $comment comment entity
     */
    protected function _patchAndValidateCommentEntity(string $userId, string $commentId, string $requestDataContent)
    {
        try {
            $comment = $this->Comments->get($commentId);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The comment does not exist.'));
        }

        /**
         * @var \App\Model\Entity\Comment $comment
         */
        $comment = $this->Comments->patchEntity(
            $comment,
            [
                'content' => $requestDataContent,
                'modified_by' => $userId,
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
