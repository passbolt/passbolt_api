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
 * @since         4.1.0
 */

namespace Passbolt\Folders\Service\FoldersRelations;

use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;

class FoldersRelationsHaveAndAreChildrenService
{
    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private FoldersRelationsTable $foldersRelationsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
    }

    /**
     * Check if the given folders have children and are children.
     *
     * @param array<string> $foldersIds The list of folders identifiers to check for.
     * @param string|null $userId The target user id to check for. If not provided, check for all users.
     * @return bool
     */
    public function haveAndAreChildren(array $foldersIds, ?string $userId = null): bool
    {
        if (empty($foldersIds)) {
            return false;
        }

        $parentsFoldersRelationsQuery = $this->foldersRelationsTable->find()
            ->select('folder_parent_id')
            ->where([
                'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
                'folder_parent_id IN' => $foldersIds,
            ]);

        $childrenFoldersRelationsAlsoParentsQuery = $this->foldersRelationsTable->find()
            ->where([
                'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
                'folder_parent_id IS NOT NULL',
                'foreign_id IN' => $parentsFoldersRelationsQuery,
            ])->limit(1)
            ->disableHydration();

        if (!is_null($userId)) {
            $parentsFoldersRelationsQuery->andWhere(['user_id' => $userId]);
            $childrenFoldersRelationsAlsoParentsQuery->andWhere(['user_id' => $userId]);
        }

        return count($childrenFoldersRelationsAlsoParentsQuery->all()->toArray()) !== 0;
    }
}
