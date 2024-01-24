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
 * @since         4.5.0
 */

namespace App\Service\Secrets;

use App\Model\Table\PermissionsTable;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\Expression\TupleComparison;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\Validation\Validation;

class SecretsFindSecretsAccessibleViaGroupOnlyService
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    private $secretsTable;

    /**
     * Instantiate the service
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->permissionsTable = $this->fetchTable('Permissions');
        /** @phpstan-ignore-next-line */
        $this->secretsTable = $this->fetchTable('Secrets');
    }

    /**
     * Find secrets that users can access only from a given group.
     *
     * @param string $groupId The group to find the accesses for.
     * @param array $usersIds The list of users to find the accesses for.
     * @param ?string $acoType The type of ACO to find the accesses for.
     * @return \Cake\ORM\Query
     */
    public function find(string $groupId, array $usersIds, ?string $acoType = null): Query
    {
        if (!Validation::uuid($groupId)) {
            throw new \TypeError(__('The group id should be a valid UUID.'));
        }

        foreach ($usersIds as $usersId) {
            if (!Validation::uuid($usersId)) {
                throw new \TypeError(__('The users ids array should contain only valid UUIDs.'));
            }
        }

        $query = $this->secretsTable->find();

        // If no users ids given, ensure the find return an empty data set.
        if (empty($usersIds)) {
            return $query->where(function ($exp, $q) {
                return $exp->isNull('Secrets.id');
            });
        }

        // INHERITED_FROM_GROUP_ACCESSES
        $inheritedAccessesFromGroupQuery = $this->permissionsTable->find()
            ->from(['PermissionsInheritedFromGroup' => 'permissions'])
            ->select([
                'user_id' => 'GroupsUsersForInheritedFromGroup.user_id',
                'resource_id' => 'PermissionsInheritedFromGroup.aco_foreign_key',
            ])
            ->join([
                'table' => 'groups_users',
                'alias' => 'GroupsUsersForInheritedFromGroup',
                'type' => 'INNER',
                'conditions' => [
                    'GroupsUsersForInheritedFromGroup.group_id' =>
                        new IdentifierExpression('PermissionsInheritedFromGroup.aro_foreign_key'),
                ],
            ])
            ->where([
                'PermissionsInheritedFromGroup.aro' => PermissionsTable::GROUP_ARO,
                'PermissionsInheritedFromGroup.aco' => $acoType,
                'GroupsUsersForInheritedFromGroup.group_id' => $groupId,
                'GroupsUsersForInheritedFromGroup.user_id IN' => $usersIds,
            ]);

        // DIRECT_USERS_ACCESSES
        $directAccessesQuery = $this->permissionsTable->find()
            ->from(['PermissionsDirect' => 'permissions'])
            ->select([
                'user_id' => 'PermissionsDirect.aro_foreign_key',
                'resource_id' => 'PermissionsDirect.aco_foreign_key',
            ])
            ->where([
                'PermissionsDirect.aro' => PermissionsTable::USER_ARO,
                'PermissionsDirect.aco' => $acoType,
                'PermissionsDirect.aro_foreign_key IN' => $usersIds,
            ]);

        // INHERITED_FROM_OTHER_GROUPS_ACCESSES
        $inheritedAccessesExcludingTargetGroupQuery = $this->permissionsTable->find()
            ->from(['PermissionsInheritedOtherGroups' => 'permissions'])
            ->select([
                'user_id' => 'GroupsUsersForInheritedFromOtherGroups.user_id',
                'resource_id' => 'PermissionsInheritedOtherGroups.aco_foreign_key',
            ])
            ->join([
                'table' => 'groups_users',
                'alias' => 'GroupsUsersForInheritedFromOtherGroups',
                'type' => 'INNER',
                'conditions' => [
                    'GroupsUsersForInheritedFromOtherGroups.group_id' =>
                        new IdentifierExpression('PermissionsInheritedOtherGroups.aro_foreign_key'),
                ],
            ])
            ->where([
                'PermissionsInheritedOtherGroups.aro' => PermissionsTable::GROUP_ARO,
                'PermissionsInheritedOtherGroups.aco' => $acoType,
                'PermissionsInheritedOtherGroups.aro_foreign_key <>' => $groupId,
                'GroupsUsersForInheritedFromOtherGroups.user_id IN' => $usersIds,
            ]);

        // R = INHERITED_FROM_GROUP_ACCESSES - ( INHERITED_FROM_OTHER_GROUPS_ACCESSES + DIRECT_USERS_ACCESSES)
        $inheritedAccessesFromGroupOnly = $inheritedAccessesFromGroupQuery
            ->join([
                'table' => $directAccessesQuery,
                'alias' => 'DirectUsersAccesses',
                'type' => 'LEFT',
                'conditions' => [
                    'DirectUsersAccesses.resource_id' =>
                        new IdentifierExpression('PermissionsInheritedFromGroup.aco_foreign_key'),
                    'DirectUsersAccesses.user_id' =>
                        new IdentifierExpression('GroupsUsersForInheritedFromGroup.user_id'),
                ],
            ])
            ->join([
                'table' => $inheritedAccessesExcludingTargetGroupQuery,
                'alias' => 'InheritedUsersAccesses',
                'type' => 'LEFT',
                'conditions' => [
                    'InheritedUsersAccesses.resource_id' =>
                        new IdentifierExpression('PermissionsInheritedFromGroup.aco_foreign_key'),
                    'InheritedUsersAccesses.user_id' =>
                        new IdentifierExpression('GroupsUsersForInheritedFromGroup.user_id'),
                ],
            ])
            ->where(function (QueryExpression $exp) {
                return $exp
                    ->isNull('DirectUsersAccesses.resource_id')
                    ->isNull('InheritedUsersAccesses.resource_id');
            });

        return $query
            ->where(new TupleComparison(['user_id', 'resource_id'], $inheritedAccessesFromGroupOnly, [], 'IN'));
    }

    /**
     * Find secrets that users can access only from a given group using exists
     *
     * @param string $groupId The group to find the accesses for.
     * @param array $usersIds The list of users to find the accesses for.
     * @param ?string $acoType The type of ACO to find the accesses for.
     * @return \Cake\ORM\Query
     */
    public function findWithExists(string $groupId, array $usersIds, ?string $acoType = null): Query
    {
        if (!Validation::uuid($groupId)) {
            throw new \TypeError(__('The group id should be a valid UUID.'));
        }

        foreach ($usersIds as $usersId) {
            if (!Validation::uuid($usersId)) {
                throw new \TypeError(__('The users ids array should contain only valid UUIDs.'));
            }
        }

        $query = $this->secretsTable->find();

        // If no users ids given, ensure the find return an empty data set.
        if (empty($usersIds)) {
            return $query->where(function ($exp, $q) {
                return $exp->isNull('Secrets.id');
            });
        }

        // INHERITED_FROM_GROUP_ACCESSES
        $inheritedAccessesFromTargetGroupQuery = $this->permissionsTable->find()
            ->from(['PermissionsInheritedFromGroup' => 'permissions'])
            ->select([
                'user_id' => 'GroupsUsersForInheritedFromGroup.user_id',
                'resource_id' => 'PermissionsInheritedFromGroup.aco_foreign_key',
            ])
            ->join([
                'table' => 'groups_users',
                'alias' => 'GroupsUsersForInheritedFromGroup',
                'type' => 'INNER',
                'conditions' => [
                    'GroupsUsersForInheritedFromGroup.group_id' =>
                        new IdentifierExpression('PermissionsInheritedFromGroup.aro_foreign_key'),
                ],
            ])
            ->where([
                'PermissionsInheritedFromGroup.aco' => $acoType,
                'PermissionsInheritedFromGroup.aro' => PermissionsTable::GROUP_ARO,
                'PermissionsInheritedFromGroup.aro_foreign_key' => $groupId,
                'GroupsUsersForInheritedFromGroup.user_id IN' => $usersIds,
            ]);

        // DIRECT_USERS_ACCESSES
        $directAccessesQuery = $this->permissionsTable->find()
            ->from(['PermissionsDirect' => 'permissions'])
            ->select([
                'user_id' => 'PermissionsDirect.aro_foreign_key',
                'resource_id' => 'PermissionsDirect.aco_foreign_key',
            ])
            ->where([
                'PermissionsDirect.aco' => $inheritedAccessesFromTargetGroupQuery->newExpr('PermissionsInheritedFromGroup.aco'), //phpcs:ignore
                'PermissionsDirect.aro' => PermissionsTable::USER_ARO,
                'PermissionsDirect.aro_foreign_key' => $inheritedAccessesFromTargetGroupQuery->newExpr('GroupsUsersForInheritedFromGroup.user_id'), //phpcs:ignore
                'PermissionsDirect.aco_foreign_key' => $inheritedAccessesFromTargetGroupQuery->newExpr('PermissionsInheritedFromGroup.aco_foreign_key'), //phpcs:ignore
            ]);

        // INHERITED_FROM_OTHER_GROUPS_ACCESSES
        $inheritedAccessesExcludingTargetGroupQuery = $this->permissionsTable->find()
            ->from(['PermissionsInheritedOtherGroups' => 'permissions'])
            ->select([
                'user_id' => 'GroupsUsersForInheritedFromOtherGroups.user_id',
                'resource_id' => 'PermissionsInheritedOtherGroups.aco_foreign_key',
            ])
            ->join([
                'table' => 'groups_users',
                'alias' => 'GroupsUsersForInheritedFromOtherGroups',
                'type' => 'INNER',
                'conditions' => [
                    'GroupsUsersForInheritedFromOtherGroups.group_id' =>
                        new IdentifierExpression('aro_foreign_key'),
                ],
            ])
            ->where([
                'PermissionsInheritedOtherGroups.aco' => $inheritedAccessesFromTargetGroupQuery->newExpr('PermissionsInheritedFromGroup.aco'), //phpcs:ignore
                'PermissionsInheritedOtherGroups.aro' => PermissionsTable::GROUP_ARO,
                'GroupsUsersForInheritedFromOtherGroups.group_id <>' => $inheritedAccessesFromTargetGroupQuery->newExpr('GroupsUsersForInheritedFromGroup.group_id'), //phpcs:ignore
                'GroupsUsersForInheritedFromOtherGroups.user_id' => $inheritedAccessesFromTargetGroupQuery->newExpr('GroupsUsersForInheritedFromGroup.user_id'), //phpcs:ignore
                'PermissionsInheritedOtherGroups.aco_foreign_key' => $inheritedAccessesFromTargetGroupQuery->newExpr('PermissionsInheritedFromGroup.aco_foreign_key'), //phpcs:ignore
            ]);

        // R = INHERITED_FROM_GROUP_ACCESSES - ( INHERITED_FROM_OTHER_GROUPS_ACCESSES + DIRECT_USERS_ACCESSES)
        $inheritedAccessesFromTargetGroupOnlyQuery = $inheritedAccessesFromTargetGroupQuery
            ->where(function (QueryExpression $exp) use ($directAccessesQuery, $inheritedAccessesExcludingTargetGroupQuery) { //phpcs:ignore

                return $exp->notExists($directAccessesQuery)
                    ->notExists($inheritedAccessesExcludingTargetGroupQuery);
            });

        return $this->secretsTable->find()
            ->where(new TupleComparison(['user_id', 'resource_id'], $inheritedAccessesFromTargetGroupOnlyQuery, [], 'IN')); //phpcs:ignore
    }
}
