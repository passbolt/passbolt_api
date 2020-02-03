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

class FoldersHasAncestorService
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
     * Check if a folder has another one as ancestor.
     *
     * @param UserAccessControl $uac The current user
     * @param string $folderId The folder to check.
     * @param string|null $folderParentId The target ancestor folder.
     * @return bool
     */
    public function check(UserAccessControl $uac, string $folderId, string $folderParentId = null)
    {
        return $this->_hasAncestor($uac->userId(), $folderId, $folderParentId);
    }

    /**
     * Sub function of hasAncestor that loop on itself to check a folder does not have another has ancestor.
     * @param string $userId The user to check for.
     * @param string $folderId The folder to check.
     * @param string $folderParentId The target ancestor folder.
     * @return bool
     */
    private function _hasAncestor(string $userId, string $folderId, string $folderParentId)
    {
        $cursorParentFolderId = $this->foldersRelationsTable->find()
            ->where([
                'foreign_id' => $folderParentId,
                'user_id' => $userId,
            ])->select('folder_parent_id')
            ->extract('folder_parent_id')
            ->first();

        if (is_null($cursorParentFolderId)) {
            return false;
        } elseif ($cursorParentFolderId === $folderId) {
            return true;
        } else {
            return $this->_hasAncestor($userId, $folderId, $cursorParentFolderId);
        }
    }
}
