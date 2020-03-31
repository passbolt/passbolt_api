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

class FoldersItemsGetAncestorsService
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
     * Get a list of ancestors for a given folder item.
     *
     * @param string $userId The user tree to look into.
     * @param string $folderId The target folder
     * @return array
     */
    public function getAncestors(string $userId, string $folderId)
    {
        return $this->_getAncestors($userId, $folderId);
    }

    /**
     * Private method to recursively get a folder item list of ancestors.
     * @param string $userId The user tree to look into.
     * @param string $folderId The target folder
     * @param array $_ancestors Internal variable to aggregate the recursive function results.
     * @return array
     */
    private function _getAncestors(string $userId, string $folderId, array $_ancestors = [])
    {
        $folderParentId = $this->foldersRelationsTable->findByUserIdAndForeignId($userId, $folderId)
            ->select('folder_parent_id')
            ->extract('folder_parent_id')
            ->first();

        if (is_null($folderParentId)) {
            return $_ancestors;
        }
        $_ancestors[] = $folderParentId;

        return $this->_getAncestors($userId, $folderParentId, $_ancestors);
    }
}
