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

namespace Passbolt\Folders\Service\Folders;

use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Behavior\ContainFolderParentIdBehavior;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersItems\FoldersItemsHasAncestorService;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsCreateService;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsDeleteService;

class FoldersMoveService
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
     * @var FoldersItemsHasAncestorService
     */
    private $foldersItemsHasAncestorService;

    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->foldersRelationsDeleteService = new FoldersRelationsDeleteService();
        $this->foldersItemsHasAncestorService = new FoldersItemsHasAncestorService();
    }

    /**
     * Move a folder.
     *
     * @param UserAccessControl $uac The current user.
     * @param Folder $folder The folder to move.
     * @param string|null $folderParentId The destination folder to move in. Place the folder at the root if null given.
     * @return void
     * @throws \Exception
     */
    public function move(UserAccessControl $uac, Folder $folder, string $folderParentId = null)
    {
        $cycle = $this->foldersItemsHasAncestorService->hasAncestor($folderParentId, $folder->id);
        if ($cycle) {
            throw new BadRequestException(__('Cycle detected.'));
        }

        $this->foldersTable->getConnection()->transactional(function () use ($uac, $folder, $folderParentId) {
            $this->foldersRelationsDeleteService->delete($uac, $folder->id);
            $userId = $uac->userId();
            $this->foldersRelationsCreateService->create($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folder->id, $userId, $folderParentId);
            $folder->set(ContainFolderParentIdBehavior::FOLDER_PARENT_ID_PROPERTY, $folderParentId);
        });
    }
}
