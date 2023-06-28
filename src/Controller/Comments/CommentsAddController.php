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
use App\Service\Comments\CommentsAddService;

/**
 * @property \App\Model\Table\CommentsTable $Comments
 */
class CommentsAddController extends AppController
{
    /**
     * Create a new comment for a resource.
     *
     * @param string $foreignKey The identifier of the resource to add a comment to
     * @throws \Cake\Http\Exception\BadRequestException
     * @return void
     */
    public function addPost(string $foreignKey)
    {
        $this->assertJson();

        $comment = (new CommentsAddService())->add(
            $this->User->getAccessControl(),
            $foreignKey,
            $this->getRequest()->getData()
        );
        $this->success(__('The comment was successfully added.'), $comment);
    }
}
