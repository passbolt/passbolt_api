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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Model\Traits\Folders;

use Cake\ORM\Query;
use Passbolt\Folders\Model\Entity\FoldersRelation;

trait FoldersRelationsFindersTrait
{
    /**
     * Filter out personal folders from a query.
     *
     * @param \Cake\ORM\Query $query The folders relations query to decorate
     * @return \Cake\ORM\Query
     */
    public function filterQueryByIsNotPersonalFolder(Query $query)
    {
        $subQuery = $this->find();
        $foldersIdsNotPersonal = $subQuery
            ->select([
                'foreign_id',
                'countee' => $subQuery->func()->count('foreign_id'),
            ])
            ->where(['foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER])
            ->group('foreign_id')
            ->having('countee > 1')
            ->extract('foreign_id')
            ->toArray();

        if (!empty($foldersIdsNotPersonal)) {
            $query->where(['foreign_id IN' => $foldersIdsNotPersonal]);
        } else {
            $query->where(['false']);
        }

        return $query;
    }

    /**
     * Return a query that retrieves all user's folders relations.
     *
     * @param string $userId The user id
     * @param string $folderId The folder id
     * @return \Cake\ORM\Query
     */
    public function findUserFolderRelation(string $userId, string $folderId)
    {
        return $this->find()
            ->where([
                'foreign_id' => $folderId,
                'user_id' => $userId,
            ]);
    }

    /**
     * Filter a query by users ids
     *
     * @param \Cake\ORM\Query $query The query to decorate
     * @param array $usersIds The list of users ids
     * @return \Cake\ORM\Query
     */
    public function filterByUsersIds(Query $query, array $usersIds)
    {
        return $query->where([
            'user_id IN' => $usersIds,
        ]);
    }

    /**
     * Filter a query by foreign model
     *
     * @param \Cake\ORM\Query $query The query to decorate
     * @param string $foreignModel The foreign model to filter on
     * @return \Cake\ORM\Query
     */
    public function filterByForeignModel(Query $query, string $foreignModel)
    {
        return $query->where([
            'foreign_model' => $foreignModel,
        ]);
    }

    /**
     * Returns a query that retrieves all the users having access to a given item.
     *
     * @param string $foreignId The target folder
     * @return \Cake\ORM\Query
     */
    public function findUsersIdsHavingAccessToItem(string $foreignId)
    {
        return $this->findByForeignId($foreignId)
            ->select('user_id');
    }

    /**
     * Returns a query that retrieves all the relations that have a deleted folder parent.
     *
     * @return \Cake\ORM\Query
     */
    public function findByDeletedFolderParent()
    {
        return $this->find()
            ->leftJoinWith('FoldersParents')
            ->where([
                'FoldersRelations.folder_parent_id IS NOT NULL',
                'FoldersParents.id IS NULL',
            ]);
    }
}
