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

namespace Passbolt\Folders\Service\FoldersRelations;

use Cake\ORM\TableRegistry;

class FoldersItemsHasAncestorService
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
     * @param string|null $folderId The target folder. Default null.
     * @param string $ancestorFolderId The potential folder ancestor
     * @param string $userId The user tree to check in
     * @return bool
     */
    public function hasAncestor(string $folderId = null, string $ancestorFolderId, string $userId)
    {
        if ($ancestorFolderId === $folderId) {
            return true;
        } elseif (is_null($folderId)) {
            // Root has no ancestor.
            return false;
        }

        $folderParentId = $this->getFolderParentId($userId, $folderId);

        return $this->hasAncestor($folderParentId, $ancestorFolderId, $userId);
    }

    /**
     * Retrieve a folder parent id for a user.
     * @param string $userId The user to check for
     * @param string $folderId The folder to check for
     * @return string
     */
    private function getFolderParentId(string $userId, string $folderId)
    {
        return $this->foldersRelationsTable->find()
            ->where([
                'foreign_id' => $folderId,
                'user_id' => $userId,
            ])->select('folder_parent_id')
            ->extract('folder_parent_id')
            ->first();
    }
}
