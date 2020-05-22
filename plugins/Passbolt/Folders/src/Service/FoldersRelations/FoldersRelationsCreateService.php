<?php
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

namespace Passbolt\Folders\Service\FoldersRelations;

use App\Error\Exception\ValidationException;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;

class FoldersRelationsCreateService
{
    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
    }

    /**
     * Create a folder relation for the current user.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The target foreign model
     * @param string $foreignId The target foreign instance id
     * @param string $userId The target user id
     * @param string|null $folderParentId (optional) The target folder destination.
     * @return Folder
     * @throws Exception If an unexpected error occurred
     */
    public function create(UserAccessControl $uac, string $foreignModel, string $foreignId, string $userId, string $folderParentId = null)
    {
        $folderRelation = null;

        $this->foldersRelationsTable->getConnection()->transactional(function () use (&$folderRelation, $foreignModel, $foreignId, $userId, $folderParentId) {
            $folderRelation = $this->createUserFolderRelation($foreignModel, $foreignId, $userId, $folderParentId);
        });

        return $folderRelation;
    }

    /**
     * Create and save the folder relation in database.
     *
     * @param string $foreignModel The target foreign model
     * @param string $foreignId The target foreign instance id
     * @param string $userId The target user id
     * @param string|null $folderParentId (optional) The target folder destination.
     * @return FoldersRelation
     */
    private function createUserFolderRelation(string $foreignModel, string $foreignId, string $userId, string $folderParentId = null)
    {
        $folderRelation = $this->buildFolderRelationEntity($foreignModel, $foreignId, $userId, $folderParentId);
        $this->handleFolderRelationValidationErrors($folderRelation);
        $this->foldersRelationsTable->save($folderRelation);
        $this->handleFolderRelationValidationErrors($folderRelation);

        return $folderRelation;
    }

    /**
     * Build the folder relation entity.
     *
     * @param string $foreignModel The target foreign model
     * @param string $foreignId The target foreign instance id
     * @param string $userId The target user id
     * @param string|null $folderParentId (optional) The target folder destination.
     * @return FoldersRelation
     */
    private function buildFolderRelationEntity(string $foreignModel, string $foreignId, string $userId, $folderParentId = null)
    {
        $data = [
            'foreign_model' => $foreignModel,
            'foreign_id' => $foreignId,
            'user_id' => $userId,
            'folder_parent_id' => $folderParentId,
        ];
        $accessibleFields = [
            'foreign_model' => true,
            'foreign_id' => true,
            'user_id' => true,
            'folder_parent_id' => true,
        ];

        return $this->foldersRelationsTable->newEntity($data, ['accessibleFields' => $accessibleFields]);
    }

    /**
     * Handle folder relation validation errors.
     *
     * @param FoldersRelation $folderRelation The folder relation
     * @return void
     */
    private function handleFolderRelationValidationErrors(FoldersRelation $folderRelation)
    {
        $errors = $folderRelation->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate the user folder relation data.'), $folderRelation, $this->foldersRelationsTable);
        }
    }
}
