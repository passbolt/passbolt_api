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

namespace Passbolt\Folders\Model\Traits\FoldersRelations;

use App\Model\Table\PermissionsTable;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;
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
        $foldersIdsNotPersonalQuery = $this->find()
            ->select(['foreign_id'])
            ->where(['foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER])
            ->group('foreign_id')
            ->having('count(foreign_id) > 1');

        return $query->where(['foreign_id IN' => $foldersIdsNotPersonalQuery]);
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
     * Returns a query that retrieves all the relations that have a deleted folder parent.
     *
     * @return \Cake\ORM\Query
     */
    public function findByDeletedFolderParent()
    {
        return $this->find()
            ->leftJoinWith('FoldersParents')
            ->whereNull('FoldersParents.id')
            ->whereNotNull('FoldersRelations.folder_parent_id');
    }

    /**
     * Returns a query that retrieves all the missing folders relations for a given foreign model.
     *
     * @param string $foreignModel The foreign model to filter on
     * @return \Cake\ORM\Query
     */
    public function findMissingFoldersRelations(string $foreignModel): Query
    {
        // Find direct users accesses.
        $directUsersSecretsQuery = $this->Resources->Permissions->find()
            ->select([
                'aco_foreign_key' => 'aco_foreign_key',
                'user_id' => 'aro_foreign_key',
            ])
            ->where([
                'aco' => $foreignModel,
                'aro' => PermissionsTable::USER_ARO,
            ]);

        // Find inherited users accesses.
        $inheritedUsersSecretsQuery = $this->Resources->Permissions->find()
            ->select([
                'aco_foreign_key' => 'aco_foreign_key',
                'user_id' => 'groups_users.user_id',
            ])
            ->leftJoin('groups_users', 'aro_foreign_key = group_id')
            ->where([
                'aco' => $foreignModel,
                'aro' => PermissionsTable::GROUP_ARO,
            ]);

        // Find users accesses = direct users accesses + inherited users accesses.
        $userExpectedAccessesQuery = $directUsersSecretsQuery
            ->union($inheritedUsersSecretsQuery)
            ->group(['aco_foreign_key', 'user_id']);

        /*
         * Find users accesses for which a folder relation is missing.
         * A right join strategy is used instead of a subquery to improve the query performance.
         */
        return $this->find()
            ->join([
                'table' => $userExpectedAccessesQuery,
                'alias' => 'ExpectedAccesses',
                'type' => 'RIGHT',
                'conditions' => [
                    'ExpectedAccesses.aco_foreign_key' => new IdentifierExpression('FoldersRelations.foreign_id'),
                    'ExpectedAccesses.user_id' => new IdentifierExpression('FoldersRelations.user_id'),
                ],
            ])
            ->select(['foreign_id' => 'ExpectedAccesses.aco_foreign_key', 'user_id' => 'ExpectedAccesses.user_id'])
            ->where(function (QueryExpression $exp) {
                return $exp
                    ->isNull('FoldersRelations.foreign_id');
            });
    }
}
