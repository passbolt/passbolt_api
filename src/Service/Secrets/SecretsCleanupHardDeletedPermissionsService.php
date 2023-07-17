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
 * @since         2.0.0
 */
namespace App\Service\Secrets;

use App\Model\Table\PermissionsTable;
use App\Model\Table\SecretsTable;
use Cake\Database\Driver\Postgres;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class SecretsCleanupHardDeletedPermissionsService
{
    private SecretsTable $Secrets;

    /**
     * SecretsCleanupHardDeletedPermissionsService constructor.
     */
    public function __construct()
    {
        /** @var \App\Model\Table\SecretsTable $SecretsTable */
        $SecretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $this->Secrets = $SecretsTable;
    }

    /**
     * Delete all records where associated permissions are soft deleted
     *
     * @param bool|null $dryRun default false
     * @return int number of affected records
     */
    public function cleanupHardDeletedPermissions(?bool $dryRun = false): int
    {
        $secretsIdsToDelete = $this->findSecretsToDelete()->select('id');

        if ($dryRun) {
            return $secretsIdsToDelete->count();
        }

        if ($secretsIdsToDelete->getConnection()->getDriver() instanceof Postgres) {
            // Postgres does not accept the deletion combined with a join, and rather recommends injecting a sub-query
            return $this->Secrets->deleteAll(['id IN' => $secretsIdsToDelete]);
        } else {
            // MySQL does not accept the deletion of a table with a sub-query on that same table (secrets)
            return $this->deletedSecretsWithJoinInDelete();
        }
    }

    /**
     * Find the secrets to delete.
     *
     * @return \Cake\ORM\Query
     */
    private function findSecretsToDelete(): Query
    {
        $directUsersSecretsQuery = $this->Secrets->Resources->Permissions->find()
            ->select([
                'resource_id' => 'aco_foreign_key',
                'user_id' => 'aro_foreign_key',
            ])
            ->where([
                'aco' => PermissionsTable::RESOURCE_ACO,
                'aro' => PermissionsTable::USER_ARO,
            ]);

        $inheritedUsersSecretsQuery = $this->Secrets->Resources->Permissions->find()
            ->select([
                'resource_id' => 'aco_foreign_key',
                'user_id' => 'groups_users.user_id',
            ])
            ->leftJoin('groups_users', 'aro_foreign_key = group_id')
            ->where([
                'aco' => PermissionsTable::RESOURCE_ACO,
                'aro' => PermissionsTable::GROUP_ARO,
            ]);

        $userExpectedSecretsQuery = $directUsersSecretsQuery
            ->union($inheritedUsersSecretsQuery->group(['resource_id', 'user_id']))
            ->group(['resource_id', 'user_id']);

        // Use a "LEFT JOIN" instead of a "NOT IN" for performance reason.
        return $this->Secrets->find()
            ->leftJoin(['ExpectedSecrets' => $userExpectedSecretsQuery], [
                'ExpectedSecrets.resource_id' => new IdentifierExpression('Secrets.resource_id'),
                'ExpectedSecrets.user_id' => new IdentifierExpression('Secrets.user_id'),
            ])
            ->where(function (QueryExpression $exp) {
                return $exp->isNull('ExpectedSecrets.resource_id');
            });
    }

    /**
     * Cleanup secrets with a join ins delete statement.
     * This is not supported by Postgres
     *
     * @return int
     */
    private function deletedSecretsWithJoinInDelete(): int
    {
        return ConnectionManager::get('default')->execute("
            DELETE secrets FROM secrets
            LEFT JOIN (
            (
                SELECT aco_foreign_key AS resource_id, aro_foreign_key AS user_id
                FROM permissions Permissions
                WHERE (aco = '" . PermissionsTable::RESOURCE_ACO . "' AND aro = '" . PermissionsTable::USER_ARO . "')
                GROUP BY resource_id, user_id
            )
            UNION
            (
                SELECT aco_foreign_key AS resource_id, groups_users.user_id AS user_id
                FROM permissions Permissions
                LEFT JOIN groups_users groups_users ON aro_foreign_key = group_id
                WHERE (aco = '" . PermissionsTable::RESOURCE_ACO . "' AND aro = '" . PermissionsTable::GROUP_ARO . "')
                GROUP BY resource_id, user_id
            )
            ) ExpectedSecrets ON (
                ExpectedSecrets.resource_id = secrets.resource_id
                AND ExpectedSecrets.user_id = secrets.user_id
            )

            WHERE ExpectedSecrets.resource_id IS NULL;
        ")->count();
    }
}
