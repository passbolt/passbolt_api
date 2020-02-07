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

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Folders\Model\Behavior\ContainFolderParentIdBehavior;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersTable;

class ResourcesMoveService
{
    /**
     * @var FoldersRelationsCreateService
     */
    private $foldersRelationsCreateService;

    /**
     * @var FoldersRelationsDeleteService
     */
    private $foldersRelationsDeleteService;

    /**
     * @var FoldersTable
     */
    private $foldersTable;

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
        $this->foldersRelationsDeleteService = new FoldersRelationsDeleteService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Move a folder.
     *
     * @param UserAccessControl $uac The current user.
     * @param Resource $resource The resource to move
     * @param string|null $folderParentId The destination folder to move in. Place the folder at the root if null given.
     * @return void
     * @throws Exception
     */
    public function move(UserAccessControl $uac, Resource $resource, string $folderParentId = null)
    {
        if (is_null($folderParentId)) {
            $this->assertUserCanMoveResourceToTheRoot($resource);
        } else {
            $this->assertFolderParentExists($folderParentId);
            $this->assertUserHasPermissionToUseFolderParent($uac, $folderParentId);
        }

        $this->foldersTable->getConnection()->transactional(function () use ($uac, $resource, $folderParentId) {
            $this->foldersRelationsDeleteService->delete($uac, $resource->id);
            $this->foldersRelationsCreateService->create($uac, $resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $folderParentId);
            $resource->set(ContainFolderParentIdBehavior::FOLDER_PARENT_ID_PROPERTY, $folderParentId);
        });
    }

    private function assertUserCanMoveResourceToTheRoot(Resource $resource)
    {
        // TODO: shared
    }

    /**
     * Assert that the parent folder exists.
     *
     * @param string $folderId The destination folder.
     * @return void
     * @throws CustomValidationException If the destination folder does not exist.
     */
    private function assertFolderParentExists(string $folderId)
    {
        try {
            $this->foldersTable->get($folderId);
        } catch (RecordNotFoundException $e) {
            $errors = [
                'folder_parent_id' => [
                    'folder_exists' => 'The folder parent must exist.',
                ],
            ];
            throw new CustomValidationException(__('Could not validate the folder data.'), $errors);
        }
    }

    /**
     * Assert that the current user can update the destination folder.
     *
     * @param UserAccessControl $uac The current user
     * @param string $folderId The parent folder.
     * @return void
     * @throws CustomValidationException If the user cannot write in the destination folder.
     */
    private function assertUserHasPermissionToUseFolderParent(UserAccessControl $uac, string $folderId)
    {
        $userId = $uac->userId();
        $isAllowedToMoveIn = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderId, $userId, Permission::UPDATE);
        if (!$isAllowedToMoveIn) {
            $errors = [
                'folder_parent_id' => [
                    'folder_exists' => 'The folder parent is not writable.',
                ],
            ];
            throw new ForbiddenException('Could not validate the folder data.', null, new CustomValidationException(__('Could not validate the folder data.'), $errors));
        }
    }
}
