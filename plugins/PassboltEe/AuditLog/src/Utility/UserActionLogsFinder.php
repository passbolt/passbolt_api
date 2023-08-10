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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.7.0
 */

namespace Passbolt\AuditLog\Utility;

use App\Utility\UserAccessControl;
use Cake\Database\Expression\IdentifierExpression;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class UserActionLogsFinder extends BaseActionLogsFinder
{
    /**
     * @inheritDoc
     */
    public function find(UserAccessControl $uac, string $entityId, ?array $options = []): Query
    {
        // Build query.
        $query = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs')
            ->find()
            ->where(['ActionLogs.status' => 1,])
            ->contain(['Actions' => [
                'fields' => ['Actions.name'],
            ]])
            ->group(['ActionLogs.id', 'Actions.name']);

        $this->_filterQueryByUserId($query, $entityId);

        // Join the action log related user
        $this->joinUser($query);
        // Join the history related user
        $query
            ->contain('EntitiesHistory.Users', function (Query $q) {
                return $q
                    ->select(['Users.id', 'Users.role_id', 'Users.username'])
                    ->contain('Profiles', function (Query $q) {
                        return $q->select([
                            'Profiles.first_name',
                            'Profiles.last_name',
                        ]);
                    });
            });

        return $query;
    }

    /**
     * Filter query by user id
     *
     * @param \Cake\ORM\Query $query query
     * @param string $userId user id
     * @return void
     */
    protected function _filterQueryByUserId(Query $query, string $userId): void
    {
        $subQuery = $this->_findActionLogIdsForUserCrud($userId);

        $query->join([
            'userActionLogs' => [
                'table' => $subQuery,
                'alias' => 'userActionLogs',
                'type' => 'INNER',
                'conditions' => ['userActionLogs.ActionLogs__id' => new IdentifierExpression('ActionLogs.id')],
            ],
        ]);
    }

    /**
     * Find ActionLog ids for a given user id for CRUD actions
     *
     * @param string $userId user id
     * @return \Cake\ORM\Query query
     */
    protected function _findActionLogIdsForUserCrud(string $userId): Query
    {
        return $this->ActionLogs
            ->find()
            ->select(['ActionLogs__id' => 'ActionLogs.id', 'Actions__name' => 'Actions.name'])
            ->innerJoinWith('Actions')
            ->innerJoinWith('EntitiesHistory', function (Query $q) use ($userId) {
                return $q->where([
                    'EntitiesHistory.foreign_key' => $userId,
                    'EntitiesHistory.foreign_model' => 'Users',
                ]);
            })
            ->where([
                'ActionLogs.status' => 1,
            ])
            ->group(['ActionLogs.id', 'Actions.name']);
    }
}
