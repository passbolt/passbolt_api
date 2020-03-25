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

class FoldersRelationsDeleteService
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
     * Delete a folder relation
     * @param string $userId The target user
     * @param string $foreignId The target item
     * @param bool $moveContentToRoot (optional) Should the content be moved to root. Default false.
     * @return void
     * @throws \Exception
     */
    public function delete(string $userId, string $foreignId, bool $moveContentToRoot = false)
    {
        $this->foldersRelationsTable->getConnection()->transactional(function () use ($userId, $foreignId, $moveContentToRoot) {
            $this->deleteFolderRelation($userId, $foreignId, $moveContentToRoot);
        });
    }

    /**
     * Delete a folder relation entity.
     * @param string $userId The target user
     * @param string $foreignId The target item
     * @param bool $moveContentToRoot Should the content be moved to root ?
     * @return void
     */
    private function deleteFolderRelation(string $userId, string $foreignId, bool $moveContentToRoot)
    {
        if ($moveContentToRoot) {
            $fields = [
                'folder_parent_id' => null,
            ];
            $conditions = [
                'folder_parent_id' => $foreignId,
                'user_id' => $userId,
            ];
            $this->foldersRelationsTable->updateAll($fields, $conditions);
        }

        $this->foldersRelationsTable->deleteAll([
            'foreign_id' => $foreignId,
            'user_id' => $userId,
        ]);
    }
}
