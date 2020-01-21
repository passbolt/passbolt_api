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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Service;

use App\Error\Exception\ValidationException;
use App\Model\Table\PermissionsTable;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;

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
     * @param UserAccessControl $uac The current user
     * @param string $folderId The target folder
     * @param string|null $folderParentId (optional) The target folder destination.
     * @return Folder
     * @throws \Exception If an unexpected error occurred
     */
    public function create(UserAccessControl $uac, string $folderId, string $folderParentId = null)
    {
        $folderRelation = null;

        $this->foldersRelationsTable->getConnection()->transactional(function () use (&$folderRelation, $uac, $folderId, $folderParentId) {
            $folderRelation = $this->createUserFolderRelation($uac, $folderId, $folderParentId);
        });

        return $folderRelation;
    }

    /**
     * Create and save the folder relation in database.
     *
     * @param UserAccessControl $uac The current user
     * @param string $folderId The target folder
     * @param string|null $folderParentId (optional) The target folder destination.
     * @return FoldersRelation
     */
    private function createUserFolderRelation(UserAccessControl $uac, string $folderId, string $folderParentId = null)
    {
        $userId = $uac->userId();
        $folderRelation = $this->buildFolderRelationEntity($userId, $folderId, $folderParentId);
        $this->handleFolderRelationValidationErrors($folderRelation);
        $this->foldersRelationsTable->save($folderRelation);
        $this->handleFolderRelationValidationErrors($folderRelation);

        return $folderRelation;
    }

    /**
     * Build the folder relation entity.
     *
     * @param string $userId The user id.
     * @param string $folderId The target folder
     * @param string|null $folderParentId (optional) The target folder destination.
     * @return FoldersRelation
     */
    private function buildFolderRelationEntity(string $userId, string $folderId, $folderParentId = null)
    {
        $data = [
            'foreign_model' => PermissionsTable::FOLDER_ACO,
            'foreign_id' => $folderId,
            'user_id' => $userId,
            'folder_parent_id' => $folderParentId
        ];
        $accessibleFields = [
            'foreign_model' => true,
            'foreign_id' => true,
            'user_id' => true,
            'folder_parent_id' => true
        ];

        return $this->foldersRelationsTable->newEntity($data, ['accessibleFields' => $accessibleFields]);
    }

    /**
     * Handle folder relation validation errors.
     *
     * @param $folderRelation The folder relation
     * @return void
     */
    private function handleFolderRelationValidationErrors($folderRelation)
    {
        $errors = $folderRelation->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate the user folder relation data.'), $folderRelation, $this->foldersRelationsTable);
        }
    }
}
