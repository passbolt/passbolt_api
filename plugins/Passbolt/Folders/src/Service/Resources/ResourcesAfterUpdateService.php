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

namespace Passbolt\Folders\Service\Resources;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Resource;
use App\Model\Table\ResourcesTable;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Exception;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsMoveItemInUserTreeService;

class ResourcesAfterUpdateService
{
    /**
     * @var FoldersRelationsMoveItemInUserTreeService
     */
    private $foldersRelationsMoveItemInUserTreeService;

    /**
     * @var ResourcesTable
     */
    private $resourcesTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsMoveItemInUserTreeService = new FoldersRelationsMoveItemInUserTreeService();
        $this->resourcesTable = TableRegistry::getTableLocator()->get('Resources');
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
        $folderParentId = $this->getAndValidateFolderParentId($resource, $data);

        try {
            $this->foldersRelationsMoveItemInUserTreeService->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, $folderParentId);
        } catch (CustomValidationException $e) {
            $resource->setError('folder_parent_id', $e->getErrors());
            $this->handleValidationErrors($resource);
        }
    }

    /**
     * Get and validate the folder parent id from the data.
     *
     * @param resource $resource The target resource
     * @param array $data The data
     * @return string
     */
    private function getAndValidateFolderParentId(Resource $resource, array $data = [])
    {
        $folderParentId = Hash::get($data, 'folder_parent_id', null);

        if (!is_null($folderParentId) && !Validation::uuid($folderParentId)) {
            $errors = ['uuid' => 'The folder parent id is not valid.'];
            $resource->setError('folder_parent_id', $errors);
            $this->handleValidationErrors($resource);
        }

        return $folderParentId;
    }

    /**
     * Handle resource validation errors.
     *
     * @param resource $resource The resource
     * @return void
     * @throws ValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(Resource $resource)
    {
        if ($resource->hasErrors()) {
            throw new ValidationException(__('Could not validate resource data.'), $resource, $this->resourcesTable);
        }
    }
}
