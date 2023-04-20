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
namespace App\Model\Traits\Cleanup;

use App\Model\Table\PermissionsTable;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;

trait PermissionsCleanupTrait
{
    /**
     * Delete all records where associated permissions are soft deleted
     *
     * @param bool|null $dryRun default false
     * @return int number of affected records
     */
    public function cleanupHardDeletedPermissions(?bool $dryRun = false): int
    {
        if (!$dryRun) {
            $secretsIdsToDelete = $this->findSecretsToDelete()
                ->select('id')
                ->all()
                ->extract('id')
                ->toArray();

            if (!empty($secretsIdsToDelete)) {
                return $this->deleteAll(['id IN' => $secretsIdsToDelete]);
            }
        }

        return $this->findSecretsToDelete()
            ->count();
    }

    /**
     * Find the secrets to delete.
     *
     * @return \Cake\ORM\Query
     */
    private function findSecretsToDelete(): Query
    {
        $directUsersSecretsQuery = $this->Resources->Permissions->find()
            ->select([
                'resource_id' => 'aco_foreign_key',
                'user_id' => 'aro_foreign_key',
            ])
            ->where([
                'aco' => PermissionsTable::RESOURCE_ACO,
                'aro' => PermissionsTable::USER_ARO,
            ]);

        $inheritedUsersSecretsQuery = $this->Resources->Permissions->find()
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
            ->union($inheritedUsersSecretsQuery)
            ->group(['resource_id', 'user_id']);

        // Use a "LEFT JOIN" instead of a "NOT IN" for performance reason.
        return $this->find()
            ->join([
                'table' => $userExpectedSecretsQuery,
                'alias' => 'ExpectedSecrets',
                'type' => 'LEFT',
                'conditions' => [
                    'ExpectedSecrets.resource_id' => new IdentifierExpression('Secrets.resource_id'),
                    'ExpectedSecrets.user_id' => new IdentifierExpression('Secrets.user_id'),
                ],
            ])
            ->where(function (QueryExpression $exp) {
                 return $exp->isNull('ExpectedSecrets.resource_id');
            });
    }
}
