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

namespace Passbolt\Folders\EventListener;

use App\Controller\Events\ResourceCreateEvent;
use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventListenerInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersRelationsCreateService;

/**
 * Listen when a resource is created, and use the payload to create relation between a resource and the given parent.
 *
 * Class AddFolderParentIdBehavior
 * @package Passbolt\Folders\EventListener
 */
class ResourceCreateEventListener implements EventListenerInterface
{
    /**
     * @var FoldersRelationsCreateService
     */
    private $foldersRelationsCreateService;
    /**
     * @var UserHasPermissionService
     */
    private $userHasPermissionService;

    /** @var FoldersTable */
    private $foldersTable;

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            ResourceCreateEvent::EVENT_NAME => $this
        ];
    }

    public function __construct()
    {
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->userHasPermissionService = new UserHasPermissionService();
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * @param ResourceCreateEvent $event Event
     * @return void
     */
    public function __invoke(ResourceCreateEvent $event)
    {
        $resource = $event->getResource();
        $payload = $event->getPayload();
        $uac = $event->getUac();
        $folderParentId = Hash::get($payload, 'folder_parent_id', null);

        if (!is_null($folderParentId)) {
            $this->validateParentFolder($resource, $uac, $folderParentId);
        }

        try {
            $this->foldersRelationsCreateService->create($uac, $resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $folderParentId);
        } catch (Exception $e) {
            throw new InternalErrorException(__('Could not create the relation, please try again later.'), 500, $e);
        }
    }

    /**
     * Validate the parent folder
     *
     * @param UserAccessControl $uac The current user
     * @param string $folderParentId The parent folder to validate
     * @return void
     * @throws CustomValidationException If the parent folder does not exist.
     * @throws ForbiddenException If the user is not allowed to insert content in the parent folder.
     */
    private function validateParentFolder(Resource $resource, UserAccessControl $uac, string $folderParentId)
    {
        // The provided parent folder must exist.
        $this->validateFolderExists($resource, $folderParentId);
        // The user should have at least UPDATE permission on the destination parent folder to insert content into.
        $this->validateAllowedToMoveIn($resource, $folderParentId, $uac);
    }

    private function validateAllowedToMoveIn(Resource $resource, string $folderParentId, UserAccessControl $uac)
    {
        $isAllowedToMoveIn = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderParentId, $uac->userId(), Permission::UPDATE);
        if (!$isAllowedToMoveIn) {
            $resource->setError('folder_parent_id', [
                'not_allowed' => 'You are not allowed to create content into the parent folder.',
            ]);
            throw new ValidationException(__('Could not validate resource data.'), $resource, $this->foldersTable, 403);
        }
    }

    /**
     * @param Resource $resource Resource
     * @param string $folderParentId FolderParentId
     */
    private function validateFolderExists(Resource $resource, string $folderParentId)
    {
        try {
            $this->foldersTable->get($folderParentId);
        } catch (RecordNotFoundException $e) {
            $resource->setError('folder_parent_id', [
                'folder_exists' => 'The folder parent must exist.',
            ]);
            throw new ValidationException(__('Could not validate resource data.'), $resource, $this->foldersTable, 400);
        }

    }
}
