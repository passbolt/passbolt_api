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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Controller\FoldersRelations;

use App\Controller\AppController;
use App\Error\Exception\CustomValidationException;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsMoveItemInUserTreeService;

class FoldersRelationsMoveController extends AppController
{
    /**
     * Move action
     *
     * @param string $foreignModel The item model to move
     * @param string $foreignId The item id to move
     * @return void
     */
    public function move(string $foreignModel, string $foreignId)
    {
        $foreignModel = ucfirst(strtolower($foreignModel));
        if (!in_array($foreignModel, FoldersRelationsTable::ALLOWED_FOREIGN_MODELS)) {
            $msg = __(
                'The object type should be one of the following: {0}.',
                implode(', ', FoldersRelationsTable::ALLOWED_FOREIGN_MODELS)
            );
            throw new BadRequestException($msg);
        }
        if (!Validation::uuid($foreignId)) {
            $msg = __('The object identifier should be a valid UUID.', strtolower($foreignModel));
            throw new BadRequestException($msg);
        }

        $moveItemInUserTree = new FoldersRelationsMoveItemInUserTreeService();
        $uac = $this->User->getAccessControl();

        $folderParentId = $this->getAndValidateFolderParentId($this->getRequest()->getParsedBody());
        $moveItemInUserTree->move($uac, $foreignModel, $foreignId, $folderParentId);

        $this->success(__('The object has been moved successfully.', strtolower($foreignModel)));
    }

    /**
     * Get and validate the folder parent id from the data.
     *
     * @param array|null $data The data
     * @return string|null
     */
    private function getAndValidateFolderParentId(?array $data = [])
    {
        if (!array_key_exists('folder_parent_id', $data)) {
            $errors = ['folder_parent_id' => ['_required' => __('A folder parent identifier is required.')]];
            $this->handleValidationErrors($errors);
        }

        $folderParentId = $data['folder_parent_id'];

        if (!is_null($folderParentId) && !Validation::uuid($folderParentId)) {
            $errors = ['folder_parent_id' => ['uuid' => __('The folder parent identifier should be a valid UUID.')]];
            $this->handleValidationErrors($errors);
        }

        return $folderParentId;
    }

    /**
     * Handle folder validation errors.
     *
     * @param array $errors The errors
     * @return void
     * @throws \App\Error\Exception\CustomValidationException If errors
     */
    private function handleValidationErrors(array $errors)
    {
        if (!empty($errors)) {
            $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
            throw new CustomValidationException(__('Could not validate move data.'), $errors, $foldersRelationsTable);
        }
    }
}
