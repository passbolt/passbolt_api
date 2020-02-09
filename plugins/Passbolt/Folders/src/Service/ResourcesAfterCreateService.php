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
use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersTable;

class ResourcesAfterCreateService
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
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     *
     * @param UserAccessControl $uac The current user.
     * @param resource $resource The created resource.
     * @param string|null $folderParentId The destination folder to move in. Place the folder at the root if null given.
     * @return void
     * @throws Exception
     */
    public function afterCreate(UserAccessControl $uac, Resource $resource, string $folderParentId = null)
    {
        if (!is_null($folderParentId)) {
            $this->validateParentFolder($uac, $resource, $folderParentId);
            if ($resource->hasErrors()) {
                return;
            }
        }

        try {
            $this->foldersRelationsCreateService->create($uac, $resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $folderParentId);
            $resource->set('folder_parent_id', $folderParentId);
        } catch (Exception $e) {
            throw new InternalErrorException(__('Could not create the resource, please try again later.'), 500, $e);
        }
    }

    /**
     * Validate the parent folder
     *
     * @param UserAccessControl $uac The current user
     * @param resource $resource The created resource.
     * @param string $folderParentId The parent folder to validate
     * @return void
     * @throws CustomValidationException If the parent folder does not exist.
     * @throws ForbiddenException If the user is not allowed to insert content in the parent folder.
     */
    private function validateParentFolder(UserAccessControl $uac, Resource $resource, string $folderParentId = null)
    {
        // The parent folder must exist.
        try {
            $this->foldersTable->get($folderParentId);
        } catch (RecordNotFoundException $e) {
            $errors = ['folder_exists' => 'The folder parent must exist.'];

            return $resource->setError('folder_parent_id', $errors);
        }

        // The user should have at least UPDATE permission on the destination parent folder to insert content into.
        $userId = $uac->userId();
        $isAllowedToCreateIn = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderParentId, $userId, Permission::UPDATE);
        if (!$isAllowedToCreateIn) {
            $errors = ['has_folder_access' => 'You are not allowed to create content into the parent folder.'];

            return $resource->setError('folder_parent_id', $errors);
        }
    }
}
