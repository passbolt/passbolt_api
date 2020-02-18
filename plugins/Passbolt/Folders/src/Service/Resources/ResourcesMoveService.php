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

use App\Model\Entity\Resource;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Folders\Model\Behavior\ContainFolderParentIdBehavior;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsCreateService;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsDeleteService;

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
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->foldersRelationsDeleteService = new FoldersRelationsDeleteService();
    }

    /**
     * Move a folder.
     *
     * @param UserAccessControl $uac The current user.
     * @param resource $resource The resource to move
     * @param string|null $folderParentId The destination folder to move in. Place the folder at the root if null given.
     * @return void
     * @throws Exception
     */
    public function move(UserAccessControl $uac, Resource $resource, string $folderParentId = null)
    {
        $this->foldersRelationsTable->getConnection()->transactional(function () use ($uac, $resource, $folderParentId) {
            $this->foldersRelationsDeleteService->delete($uac, $resource->id);
            $userId = $uac->userId();
            $this->foldersRelationsCreateService->create($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, $userId, $folderParentId);
            $resource->set(ContainFolderParentIdBehavior::FOLDER_PARENT_ID_PROPERTY, $folderParentId);
        });
    }
}
