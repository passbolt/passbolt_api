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
namespace App\Model\Traits\Permissions;

use App\Model\Entity\Permission;
use App\Model\Table\AvatarsTable;
use App\Model\Table\PermissionsTable;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Query;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use InvalidArgumentException;

trait PermissionsFindersTrait
{
    /**
     * Find the highest permission an aro or the groups the aro is member of could have for a given aco.
     *
     * @param string $acoType The aco type. By instance Resource or Folder.
     * @param string|\Cake\Database\Expression\IdentifierExpression $acoForeignKey The target aco. By instance resource or folder id.
     * @param string $aroForeignKey The target aro id. By instance a user or a group id.
     * @return \Cake\ORM\Query
     */
    public function findHighestByAcoAndAro(string $acoType, $acoForeignKey, string $aroForeignKey): Query
    {
        return $this->findAllByAro($acoType, $aroForeignKey, ['checkGroupsUsers' => true])
            ->where(['Permissions.aco_foreign_key' => $acoForeignKey])
            ->orderDesc('Permissions.type')
            ->limit(1);
    }

    /**
     * Returns a query retrieving data for aco permissions view
     *
     * @param string $aco The target aco id. By instance a resource or a folder id.
     * @param array|null $options options
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     * @throws \InvalidArgumentException if the resourceId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findViewAcoPermissions(string $aco, ?array $options = []): Query
    {
        if (!Validation::uuid($aco)) {
            throw new InvalidArgumentException('The access control object identifier should be a valid UUID.');
        }

        $query = $this->find()
            ->where(['Permissions.aco_foreign_key' => $aco]);

        // If contains group.
        if (isset($options['contain']['group'])) {
            $query->contain('Groups');
        }

        // If contains user.
        if (isset($options['contain']['user'])) {
            $query->contain('Users');
        }

        // If contains user profile.
        if (isset($options['contain']['user.profile'])) {
            $query->contain([
                'Users' => [
                    'Profiles' =>
                        AvatarsTable::addContainAvatar(),
                ],
            ]);
        }

        return $query;
    }

    /**
     * Returns a query retrieving the permissions an aro have.
     *
     * The $checkGroupsUsers will also return the permissions inherited from the groups the aro is member of.
     *
     * @param string $acoType The aco type. By instance Resource or Folder.
     * @param string $aroForeignKey The target aro id. By instance a user or a group id.
     * @param array|null $options options
     * [
     *   bool $checkGroupsUsers Check also for the groups the aro is member of
     * ]
     * @return \Cake\ORM\Query
     * @throws \Cake\Http\Exception\BadRequestException if the aro foreign key is not a valid UUID
     */
    public function findAllByAro(string $acoType, string $aroForeignKey, ?array $options = []): Query
    {
        if (!Validation::uuid($aroForeignKey)) {
            throw new BadRequestException(__('The identifier should be a valid UUID.'));
        }
        $checkGroupsUsers = Hash::get($options, 'checkGroupsUsers', false);

        // Retrieve also the permissions for the groups a user is member of.
        if ($checkGroupsUsers) {
            $aroForeignKeys = $this->Groups->GroupsUsers->find()
                ->select('group_id')
                ->where(['user_id' => $aroForeignKey])
                ->epilog('UNION SELECT :aroForeignKey')
                ->bind(':aroForeignKey', $aroForeignKey);
        } else {
            $aroForeignKeys = [$aroForeignKey];
        }

        return $this->find()
            ->where([
                'Permissions.aco' => $acoType,
                'Permissions.aro_foreign_key IN' => $aroForeignKeys,
            ]);
    }

    /**
     * Returns a query retrieving the acos (resources or folders) that are shared with someone else that the given aco
     * (user or group). By instance, it is useful to know which resources ownership need to be transferred when deleting
     * a user.
     *
     * The options $checkGroupsUsers option will also take in account the groups the aro is sole manager that could be
     * sole owner of shared acos.
     *
     * @param string $acoType The aco type. By instance Resource or Folder.
     * @param string $aro The target aro id. By instance a user or a group id.
     * @param array|null $options (optional) array of options
     * [
     *   bool $checkGroupsUsers Check also for the groups the aro is member of
     * ]
     * @throws \InvalidArgumentException if the user id is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findSharedAcosByAroIsSoleOwner(string $acoType, string $aro, ?array $options = []): Query
    {
        $checkGroupsUsers = Hash::get($options, 'checkGroupsUsers', false);

        if (!Validation::uuid($aro)) {
            throw new InvalidArgumentException('The user identifier should be a valid UUID.');
        }

        if ($checkGroupsUsers) {
            // R = All the shared resources that are only owned by the user given in parameter or owned by non empty groups he is sole manager of
            // If the user is deleted these resources will require their permissions to be updated to not be left without OWNER.
            //
            // Details:
            // AROS, all users or groups that have entries in the permissions table (aro_foreign_key)
            // USER_AND_SOLE_MANAGER_GROUPS, is a set of AROs represented by the user given in parameter and the groups he is sole manager
            // USER_AND_SOLE_MANAGER_NON_EMPTY_GROUPS, is a set of AROs represented by the non empty groups the user is sole manager
            // ACOS, all the resources that have entries in the permissions table (aro_foreign_key)
            // ACOS_ONLY_OWNED_BY_USER_AND_SOLE_MANAGER_GROUPS, all the ACOS that are only owned by the user and the groups he is sole manager
            // ACOS_ONLY_OWNED_BY_USER_AND_SOLE_MANAGER_NON_EMPTY_GROUPS, all the ACOS that are owned by the user and the non empty groups he is sole manager
            // ACOS_ONLY_ACCESSIBLE_BY_USER, all the ACOS that are only accessible by the user and the groups he is the only member
            // R = ONLY_OWNED_BY_USER_AND_SOLE_MANAGER_GROUPS - ONLY_OWNED_BY_USER_AND_SOLE_MANAGER_NON_EMPTY_GROUPS - ACOS_ONLY_ACCESSIBLE_BY_USER

            // (USER_AND_SOLE_MANAGER_GROUPS)
            $groupsSoleManager = $this->Groups->GroupsUsers->findGroupsWhereUserIsSoleManager($aro)
                ->all()
                ->extract('group_id')->toArray();
            // (R = ACOS_ONLY_OWNED_BY_USER_AND_SOLE_MANAGER_GROUPS)
            $aros = [$aro];
            $aros = array_merge($aros, $groupsSoleManager);
            $query = $this->findAcosByArosAreSoleOwner($acoType, $aros);

            // (USER_AND_SOLE_MANAGER_NON_EMPTY_GROUPS)
            $nonEmptyGroupsSoleManager = $this->Groups->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($aro)
                ->all()
                ->extract('group_id')
                ->toArray();
            if (!empty($nonEmptyGroupsSoleManager)) {
                // (ACOS_ONLY_OWNED_BY_USER_AND_SOLE_MANAGER_NON_EMPTY_GROUPS)
                $acosOnlyOwnedByUsersAndSoleManagerOfNonEmptyGroups = $this->find()
                    ->select('aco_foreign_key')->distinct()
                    ->where([
                        'aco' => $acoType,
                        'type' => Permission::OWNER,
                        'aro_foreign_key IN' => $nonEmptyGroupsSoleManager,
                    ]);

                // (R = R - ONLY_OWNED_BY_USER_AND_SOLE_MANAGER_NON_EMPTY_GROUPS)
                $query->where(['aco_foreign_key NOT IN' => $acosOnlyOwnedByUsersAndSoleManagerOfNonEmptyGroups]);
            }

            // (ACOS_ONLY_ACCESSIBLE_BY_USER)
            $subquery = $this->findAcosOnlyAroCanAccess($acoType, $aro, ['checkGroupsUsers' => $checkGroupsUsers]);
            // (R = R - ACOS_ONLY_ACCESSIBLE_BY_USER)
            $query->where([
                'aco' => $acoType,
                'aco_foreign_key NOT IN' => $subquery,
            ]);
        } else {
            $aros = [$aro];
            // (R = ACOS_ONLY_OWNED_BY_USER)
            $query = $this->findAcosByArosAreSoleOwner($acoType, $aros);
            // (ACOS_ONLY_ACCESSIBLE_BY_USER)
            $subquery = $this->findAcosOnlyAroCanAccess($acoType, $aro, ['checkGroupsUsers' => $checkGroupsUsers]);
            // (R = R - ACOS_ONLY_ACCESSIBLE_BY_USER)
            $query->where([
                'aco' => $acoType,
                'aco_foreign_key NOT IN' => $subquery,
            ]);
        }

        return $query;
    }

    /**
     * Returns a query retrieving the acos (resources or folders) that at least one of the given aros (users or groups)
     * is sole owner.
     *
     * @param string $acoType The aco type. By instance Resource or Folder.
     * @param array $aros An array of aro id. Composed of users or groups ids.
     * @throw \InvalidArgumentException if the aros parameter contains not only uuid value.
     * @return \Cake\ORM\Query
     */
    public function findAcosByArosAreSoleOwner(string $acoType, array $aros)
    {
        foreach ($aros as $aro) {
            if (!Validation::uuid($aro)) {
                throw new InvalidArgumentException('The access request object identifier should be a valid UUID.');
            }
        }

        // R = All the resources that are only owned by the user given in parameter or owned by non empty groups he is sole manager of.
        //
        // Details:
        // AROS, all users or groups that have entries in the permissions table (aro_foreign_key)
        // USER_AND_GROUPS, is a set of AROs represented by the user and groups given as parameter
        // OTHER_USERS_AND_GROUPS, is a set of AROs represented by the users and groups which are not USER_AND_GROUPS
        // ACOS, all the resources that have entries in the permissions table (aro_foreign_key)
        // ACOS_OWNED_BY_USERS_AND_GROUPS, is the set of AROS that are owned by the USERS_AND_GROUPS
        // ACOS_OWNED_BY_OTHER_USERS_AND_GROUPS, is the set of AROS that are owned by the OTHER_USERS_AND_GROUPS
        // R = ACOS_OWNED_BY_USERS_AND_GROUPS - ACOS_OWNED_BY_OTHER_USERS_AND_GROUPS, is the set of ACOS only owned by USERS_AND_GROUPS

        // (ACOS_OWNED_BY_OTHER_USERS_AND_GROUPS)
        // SELECT aco_foreign_key
        // FROM permissions
        // WHERE aro_foreign_key NOT IN (USER_AND_GROUPS)
        // AND type = OWNER
        $acosOwnedByOtherUsersAndGroups = $this->find()
            ->select(['aco_foreign_key'])->distinct()
            ->where([
                'aco' => $acoType,
                'aro_foreign_key NOT IN' => $aros,
                'type' => Permission::OWNER,
            ]);

        // (R)
        // SELECT aco_foreign_key
        // FROM permissions
        // WHERE aro_foreign_key IN (USER_AND_GROUPS)
        // AND type = OWNER
        // AND aco_foreign_key NOT IN (ACOS_OWNED_BY_OTHER_USERS_AND_GROUPS)
        return $this->find()
            ->select(['aco_foreign_key'])->distinct()
            // ACOS_OWNED_BY_USERS_AND_GROUPS
            ->where([
                'aco' => $acoType,
                'aro_foreign_key IN' => $aros,
                'type' => Permission::OWNER,
            ])
            // ACOS_OWNED_BY_USERS_AND_GROUPS - ACOS_OWNED_BY_OTHER_USERS_AND_GROUPS
            ->where(['aco_foreign_key NOT IN' => $acosOwnedByOtherUsersAndGroups]);
    }

    /**
     * Returns a query retrieving the acos (resources or folders) a given aro (user or group) is the only one to have
     * access.
     *
     * The $checkGroupsUsers options will also check the groups the aro is member of. The returned query will return
     * also the acos that are accessible only by the groups the aro is the only member of.
     *
     * @param string $acoType The aco type. By instance Resource or Folder.
     * @param string $aro The target aro id. By instance a user or a group id.
     * @param array $options (optional) array of options
     * [
     *   bool $checkGroupsUsers Check also for the groups the aro is member of.
     * ]
     * @throws \InvalidArgumentException if the aro id is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findAcosOnlyAroCanAccess(string $acoType, string $aro, ?array $options = [])
    {
        $checkGroupsUsers = Hash::get($options, 'checkGroupsUsers', false);

        // R = All the resources that are only accessible by a list of users and/or groups.
        //
        // AROS, all users or groups that have entries in the permissions table (aro_foreign_key)
        // USER_AND_GROUPS, is a set of AROs represented by the user and groups given as parameter
        // OTHER_USERS_AND_GROUPS, is a set of AROs represented by the users and groups which are not USER_AND_GROUPS
        // ACOS, all the resources that have entries in the permissions table (aro_foreign_key)
        // ACOS_ACCESSIBLE_BY_USERS_AND_GROUPS, is the set of AROS that are accessible by USERS_AND_GROUPS
        // ACOS_ACCESSIBLE_BY_OTHER_USERS_AND_GROUPS, is the set of AROS that are accessible by OTHER_USERS_AND_GROUPS
        // R = ACOS_ACCESSIBLE_BY_USERS_AND_GROUPS - ACOS_ACCESSIBLE_BY_OTHER_USERS_AND_GROUPS, is the set of ACOS only accessible by USERS_AND_GROUPS

        // USER_AND_GROUPS
        $aros = [$aro];
        if ($checkGroupsUsers) {
            $groups = $this->Groups->GroupsUsers->findGroupsWhereUserOnlyMember($aro)
                ->all()
                ->extract('group_id')->toArray();
            $aros = array_merge($aros, $groups);
        }

        // (ACOS_ACCESSIBLE_BY_OTHER_USERS_AND_GROUPS)
        // SELECT aco_foreign_key
        // FROM permissions
        // WHERE aro_foreign_key NOT IN (USER_AND_GROUPS)
        $acosAccessibleByOtherUsersAndGroups = $this->find()
            ->select(['aco_foreign_key'])
            ->where([
                'aco' => $acoType,
                'aro_foreign_key NOT IN' => $aros,
            ]);

        // SELECT aco_foreign_key
        // FROM permissions
        // WHERE aro_foreign_key IN (USER_AND_GROUPS)
        // AND aco_foreign_key NOT IN (ACOS_ACCESSIBLE_BY_OTHER_USERS_AND_GROUPS)
        return $this->find()
            ->select(['aco_foreign_key'])->distinct()
            // ACOS_ACCESSIBLE_BY_USERS_AND_GROUPS
            ->where([
                'aco' => $acoType,
                'aro_foreign_key IN' => $aros,
            ])
            // ACOS_ACCESSIBLE_BY_USERS_AND_GROUPS - ACOS_ACCESSIBLE_BY_OTHER_USERS_AND_GROUPS
            ->where(['aco_foreign_key NOT IN' => $acosAccessibleByOtherUsersAndGroups]);
    }

    /**
     * Find access differences between a group and a user.
     * Return only the accesses that are found in the group accesses but not in the user accesses, such as array_diff
     * will do.
     *
     * @param string $acoType The aco type. By instance Resource or Folder.
     * @param string $groupId The group identifier.
     * @param string $userId The user identifier.
     * @return \Cake\ORM\Query
     */
    public function findAcosAccessesDiffBetweenGroupAndUser(string $acoType, string $groupId, string $userId): Query
    {
        // R = All the resources or folders that are only accessible by a group and not accessible by a user

        // Details:
        // ACOS_GROUP_ACCESS, is the set of ACOS that a group can access
        // ACOS_USER_ACCESS, is the set of ACOS that a user can access
        // R = ACOS_GROUP_ACCESS - ACOS_USER_ACCESS

        // ACOS_USER_ACCESS
        $remainAccessAcoForeignKeysQuery = $this->findAllByAro($acoType, $userId, ['checkGroupsUsers' => true])
            ->select('aco_foreign_key');

        // R = ACOS_GROUP_ACCESS - ACOS_USER_ACCESS
        return $this->findAllByAro($acoType, $groupId)
            ->select('aco_foreign_key')
            ->where([
                'aco_foreign_key NOT IN' => $remainAccessAcoForeignKeysQuery,
            ]);
    }

    /**
     * Find access differences between a group and multiple users.
     * Return only the accesses that are found in the group accesses but not in the user accesses, such as array_diff
     * would do.
     *
     * @param string $acoType The aco type. By instance Resource or Folder.
     * @param string $groupId The group identifier.
     * @param string[] $usersIds The user identifier.
     * @return \Cake\ORM\Query
     */
    public function findAcosAccessesDiffBetweenGroupAndUsers(string $acoType, string $groupId, array $usersIds): Query
    {
        // @todo to document

        $directUsersAccessesQuery = $this->find()
            ->select([
                'user_id' => 'aro_foreign_key',
                'resource_id' => 'aco_foreign_key',
            ])
            ->where([
                'aco' => PermissionsTable::RESOURCE_ACO,
                'aro' => PermissionsTable::USER_ARO,
                'aro_foreign_key IN' => $usersIds,
            ]);

        $inheritedUsersAccessesExcludingGroupQuery = $this->find()
            ->select([
                'user_id' => 'groups_users.user_id',
                'resource_id' => 'aco_foreign_key',
            ])
            ->leftJoin('groups_users', 'aro_foreign_key = group_id')
            ->where([
                'aco' => PermissionsTable::RESOURCE_ACO,
                'aro' => PermissionsTable::GROUP_ARO,
                'groups_users.user_id IN' => $usersIds,
                'groups_users.group_id <>' => $groupId,
            ]);

        $groupUsersAccessesQuery = $this->find()
            ->select([
                'user_id' => 'groups_users.user_id',
                'resource_id' => 'aco_foreign_key',
            ])
            ->leftJoin('groups_users', 'aro_foreign_key = group_id')
            ->where([
                'aco' => PermissionsTable::RESOURCE_ACO,
                'aro' => PermissionsTable::GROUP_ARO,
                'aro_foreign_key' => $groupId,
            ]);

        return $groupUsersAccessesQuery
            ->leftJoin(['DirectUsersAccesses' => $directUsersAccessesQuery], [
                'DirectUsersAccesses.resource_id' => new IdentifierExpression('Permissions.aco_foreign_key'),
                'DirectUsersAccesses.user_id' => new IdentifierExpression('groups_users.user_id'),
            ])
            ->leftJoin(['InheritedUsersAccesses' => $inheritedUsersAccessesExcludingGroupQuery], [
                'InheritedUsersAccesses.resource_id' => new IdentifierExpression('Permissions.aco_foreign_key'),
                'InheritedUsersAccesses.user_id' => new IdentifierExpression('groups_users.user_id'),
            ])
            ->where(function (QueryExpression $exp) {
                return $exp->isNull('DirectUsersAccesses.resource_id')
                    ->isNull('InheritedUsersAccesses.resource_id');
            });
    }

    /**
     * Returns a query retrieving the acos (resources or folders) a given aro (user or group) is owner of.
     *
     * @param string $acoType The aco type. By instance Resource or Folder.
     * @param string $aro The target aro id. By instance a user or a group id.
     * @param array $options (optional) array of options
     * [
     *   bool $checkGroupsUsers Check also for the groups the aro is member of
     * ]
     * @throws \InvalidArgumentException if the aro foreign key is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findAcosByAroIsOwner(string $acoType, string $aro, ?array $options = [])
    {
        return $this->findAllByAro($acoType, $aro, $options)
            ->where(['Permissions.type' => Permission::OWNER])
            ->select('aco_foreign_key')->distinct();
    }

    /**
     * Check that an aro has access to an aco.
     *
     * @param string $acoType The target aco type. By instance a Resource or a Folder.
     * @param string $acoForeignKey The target aco id. By instance a resource or a folder id.
     * @param string $aroForeignKey The target aro id. By instance a user or a group id.
     * @param int $permissionType The minimum permission type
     * @throws \InvalidArgumentException if the $aco parameter is not a valid uuid.
     * @throws \InvalidArgumentException if the $aro parameter is not a valid uuid.
     * @return bool
     */
    public function hasAccess(
        string $acoType,
        string $acoForeignKey,
        string $aroForeignKey,
        ?int $permissionType = null
    ): bool {
        if (!Validation::uuid($acoForeignKey)) {
            throw new InvalidArgumentException('The aco parameter should be a valid UUID.');
        }
        if (!Validation::uuid($aroForeignKey)) {
            throw new InvalidArgumentException('The aro parameter should be a valid UUID.');
        }
        $permissionType = $permissionType ?? Permission::READ;

        if (!$this->isValidPermissionType($permissionType)) {
            $msg = 'The permission type should be in the list of allowed permission type.';
            throw new InvalidArgumentException($msg);
        }
        $query = $this->findHighestByAcoAndAro($acoType, $acoForeignKey, $aroForeignKey)
            ->where(['Permissions.type >=' => $permissionType]);

        return $query->count() !== 0;
    }
}
