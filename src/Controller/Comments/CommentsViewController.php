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
use App\Service\Comments\CommentsViewService;

/**
 * @property \App\Model\Table\CommentsTable $Comments
 */
class CommentsViewController extends AppController
{
    /**
     * Comments View action
     *
     * @param string $foreignModelName name of the foreign model used for the comment
     * @param string $foreignKey uuid Identifier of the model
     * @return void
     */
    public function view(string $foreignModelName, string $foreignKey): void
    {
        $this->assertJson();

        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => ['creator', 'modifier'],
        ];
        $options = $this->QueryString->get($whitelist);

        $comments = (new CommentsViewService())->view(
            $this->User->id(),
            $foreignModelName,
            $foreignKey,
            $options
        );

        $this->success(__('The operation was successful.'), $comments);
    }
}
