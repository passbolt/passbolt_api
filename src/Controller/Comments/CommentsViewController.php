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
use App\Model\Table\CommentsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;
use InvalidArgumentException;

/**
 * @property \App\Model\Table\CommentsTable $Comments
 */
class CommentsViewController extends AppController
{
    /**
     * Comments View action
     *
     * @throws \InvalidArgumentException if the foreignModelName of foreignKey is not correct
     * @throws \Cake\Http\Exception\NotFoundException if the foreignKey can't be found
     * @throws \Cake\Http\Exception\InternalErrorException if the comments can't be retrieved
     * @param string $foreignModelName name of the foreign model used for the comment
     * @param string $foreignKey uuid Identifier of the model
     * @return void
     */
    public function view(string $foreignModelName, string $foreignKey): void
    {
        $foreignModelName = ucfirst($foreignModelName);
        // Check model sanity.
        if (!in_array($foreignModelName, CommentsTable::ALLOWED_FOREIGN_MODELS)) {
            throw new InvalidArgumentException(__('Invalid model name'));
        }

        // Check uuid sanity.
        if (!Validation::uuid($foreignKey)) {
            throw new InvalidArgumentException(__('Invalid id'));
        }

        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => ['creator', 'modifier'],
        ];
        $options = $this->QueryString->get($whitelist);

        $this->loadModel('Comments');
        try {
            $comments = $this->Comments->findViewForeignComments(
                $this->User->id(),
                $foreignModelName,
                $foreignKey,
                $options
            );
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('Could not find comments for the requested model.'));
        }

        $this->success(__('The operation was successful.'), $comments);
    }
}
