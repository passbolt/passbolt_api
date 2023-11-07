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

namespace Passbolt\Folders\Service\Resources;

use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Exception;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsCreateService;

class ResourcesAfterCreateService
{
    /**
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    private $foldersTable;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsCreateService
     */
    private $foldersRelationsCreateService;

    /**
     * @var \App\Service\Permissions\UserHasPermissionService
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
     * @param \App\Utility\UserAccessControl $uac The current user.
     * @param \App\Model\Entity\Resource $resource The created resource.
     * @param array $data The data sent by the user to create the resource.
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException If an unexpected error occurred while creating the folder relation.
     */
    public function afterCreate(UserAccessControl $uac, Resource $resource, ?array $data = [])
    {
        $folderParentId = Hash::get($data, 'folder_parent_id', null);

        if (!is_null($folderParentId)) {
            $this->validateParentFolder($uac, $resource, $folderParentId);
            if ($resource->hasErrors()) {
                return;
            }
        }

        $folderRelationData = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE,
            'foreign_id' => $resource->id,
            'folder_parent_id' => $folderParentId,
            'user_id' => $uac->getId(),
        ];

        try {
            $this->foldersRelationsCreateService->create($folderRelationData);
            $resource->set('folder_parent_id', $folderParentId);
        } catch (Exception $e) {
            throw new InternalErrorException(
                'Could not create the resource folder relation, please try again later.',
                500,
                $e
            );
        }
    }

    /**
     * Validate the parent folder
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param \App\Model\Entity\Resource $resource The created resource.
     * @param string|null $folderParentId The parent folder to validate
     * @return void
     * @throws \App\Error\Exception\CustomValidationException If the parent folder does not exist.
     * @throws \Cake\Http\Exception\ForbiddenException If the user is not allowed to insert content in the parent folder.
     */
    private function validateParentFolder(UserAccessControl $uac, Resource $resource, ?string $folderParentId = null)
    {
        if (!Validation::uuid($folderParentId)) {
            $errors = ['uuid' => __('The folder parent identifier should be a valid UUID.')];

            $resource->setError('folder_parent_id', $errors);

            return;
        }

        // The parent folder must exist.
        try {
            $this->foldersTable->get($folderParentId);
        } catch (RecordNotFoundException $e) {
            $errors = ['folder_exists' => __('The folder parent does not exist.')];

            $resource->setError('folder_parent_id', $errors);

            return;
        }

        // The user should have at least UPDATE permission on the destination parent folder to insert content into.
        $userId = $uac->getId();
        $isAllowedToCreateIn = $this->userHasPermissionService->check(
            PermissionsTable::FOLDER_ACO,
            $folderParentId,
            $userId,
            Permission::UPDATE
        );
        if (!$isAllowedToCreateIn) {
            $errors = ['has_folder_access' => __('You are not allowed to create content into the parent folder.')];

            $resource->setError('folder_parent_id', $errors);

            return;
        }
    }
}
