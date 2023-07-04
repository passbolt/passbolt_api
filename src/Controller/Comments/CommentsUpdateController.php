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
use App\Service\Comments\CommentsUpdateService;
use Cake\Http\Exception\BadRequestException;
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
        $this->assertJson();

        if (!Validation::uuid($commentId)) {
            throw new BadRequestException(__('The comment id is not valid.'));
        }

        $comment = (new CommentsUpdateService())->update(
            $this->User->id(),
            $commentId,
            Hash::get($this->request->getData(), 'content')
        );

        $this->success(__('The comment was successfully updated.'), $comment);
    }
}
