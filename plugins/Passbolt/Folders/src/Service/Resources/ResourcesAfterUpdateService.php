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

namespace Passbolt\Folders\Service\Resources;

use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Exception;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsCreateService;

class ResourcesAfterUpdateService
{
    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var FoldersRelationsCreateService
     */
    private $foldersRelationsCreateService;

    /**
     * @var ResourcesMoveService
     */
    private $resourcesMoveService;

    /**
     * @var UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->resourcesMoveService = new ResourcesMoveService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     *
     * @param UserAccessControl $uac The current user.
     * @param resource $resource The created resource.
     * @param array $data The data sent by the user to update the resource.
     * @return void
     * @throws Exception
     */
    public function afterUpdate(UserAccessControl $uac, Resource $resource, array $data = [])
    {
        $folderParentId = Hash::get($data, 'folder_parent_id', null);

        if (!is_null($folderParentId)) {
            if (!$this->validateParentFolder($uac, $resource, $folderParentId)) {
                return;
            }
        }
        $this->resourcesMoveService->move($uac, $resource, $folderParentId);
    }

    /**
     * Validate the parent folder
     *
     * @param UserAccessControl $uac The current user
     * @param resource $resource The resource to move.
     * @param string $folderParentId The destination folder.
     * @return bool
     */
    private function validateParentFolder(UserAccessControl $uac, Resource $resource, string $folderParentId = null)
    {
        if (is_null($folderParentId)) {
            return $this->assertUserCanMoveOutOfFolder($uac, $resource);
        } else {
            return $this->assertFolderParentIdIsValid($resource, $folderParentId)
                && $this->assertUserCanMoveOutOfFolder($uac, $resource)
                && $this->assertUserCanMoveInFolder($uac, $resource, $folderParentId);
        }
    }

    /**
     * Check if the user can move content out of the folder;
     * @param UserAccessControl $uac The current user
     * @param resource $resource The resource to move.
     * @return bool
     */
    private function assertUserCanMoveOutOfFolder(UserAccessControl $uac, Resource $resource)
    {
        // @todo Not needed with personal folder.
        return true;
    }

    /**
     * Assert that the parent folder id is valid and exists.
     *
     * @param resource $resource The resource to move.
     * @param string $folderId The destination folder.
     * @return bool
     */
    private function assertFolderParentIdIsValid(Resource $resource, string $folderId)
    {
        if (!Validation::uuid($folderId)) {
            $errors = ['uuid' => 'The folder parent id is not valid.'];
            $resource->setError('folder_parent_id', $errors);

            return false;
        }

        try {
            $this->foldersTable->get($folderId);
        } catch (RecordNotFoundException $e) {
            $errors = ['folder_exists' => 'The folder parent must exist.'];
            $resource->setError('folder_parent_id', $errors);

            return false;
        }

        return true;
    }

    /**
     * Check if the user can move content in the folder;
     * @param UserAccessControl $uac The current user
     * @param resource $resource The resource to move.
     * @param string $folderParentId The destination folder
     * @return bool
     */
    private function assertUserCanMoveInFolder(UserAccessControl $uac, Resource $resource, string $folderParentId)
    {
        $userId = $uac->userId();
        $isAllowedToMoveIn = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderParentId, $userId, Permission::UPDATE);
        if (!$isAllowedToMoveIn) {
            $errors = ['has_folder_access' => 'You are not allowed to create content into the parent folder.'];
            $resource->setError('folder_parent_id', $errors);

            return false;
        }

        return true;
    }
}
