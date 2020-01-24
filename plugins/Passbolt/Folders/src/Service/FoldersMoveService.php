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

use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;

class FoldersMoveService
{
    /**
     * @var FoldersRelationCreateService
     */
    private $foldersRelationsCreateService;

    /**
     * @var FoldersRelationsDeleteService
     */
    private $foldersRelationsDeleteService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->foldersRelationsDeleteService = new FoldersRelationsDeleteService();
        $this->foldersHasAncestorService = new FoldersHasAncestorService();
    }

    /**
     * Move a folder.
     *
     * @param UserAccessControl $uac The current user.
     * @param string $folderId The folder to move.
     * @param string|null $folderParentId The destination folder to move in. Place the folder at the root if null given.
     * @return void
     * @throws \Exception
     */
    public function move(UserAccessControl $uac, string $folderId, string $folderParentId = null)
    {
        $cycle = $this->foldersHasAncestorService->check($uac, $folderId, $folderParentId);
        if ($cycle) {
            throw new BadRequestException(__('Cycle detected.'));
        }

        $this->foldersTable->getConnection()->transactional(function () use ($uac, $folderId, $folderParentId) {
            $this->foldersRelationsDeleteService->delete($uac, $folderId, $uac->userId());
            $this->foldersRelationsCreateService->create($uac, $folderId, $folderParentId);
        });
    }
}
