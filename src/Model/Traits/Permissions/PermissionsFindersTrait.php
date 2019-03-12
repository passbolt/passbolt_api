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
 * @since         2.0.0
 */
namespace App\Model\Traits\Permissions;

use App\Model\Entity\Permission;
use App\Model\Table\AvatarsTable;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

trait PermissionsFindersTrait
{
    /**
     * Build the query that fetches data for aco permissions view
     *
     * @param string $acoForeignKey The aco instance id to retrieve to get the permissions for
     * @param array $options options
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     * @throws \InvalidArgumentException if the resourceId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findViewAcoPermissions(string $acoForeignKey, array $options = [])
    {
        if (!Validation::uuid($acoForeignKey)) {
            throw new \InvalidArgumentException(__('The parameter acoForeignKey is not a valid uuid.'));
        }

        $query = $this->find()
            ->where(['Permissions.aco_foreign_key' => $acoForeignKey]);

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
                        AvatarsTable::addContainAvatar()
                ]
            ]);
        }

        return $query;
    }

    /**
     * Return resources the user is the owner of and that are shared
     * with somebody else. Useful to know which resources need to be transferred
     * when deleting the user.
     *
     * Setting $checkGroupsUsers will also take in account the groups the users
     * is sole manager that could be sole owner of shared resources.
     *
     * @param string $userId uuid of the user
     * @param bool $checkGroupsUsers also check for group user is sole member of
     * @throws \InvalidArgumentException if the user id is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findSharedResourcesUserIsSoleOwner(string $userId, bool $checkGroupsUsers = false)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
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

            $GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
            // (USER_AND_SOLE_MANAGER_GROUPS)
            $groupsSoleManager = $GroupsUsers->findGroupsWhereUserIsSoleManager($userId)->extract('group_id')->toArray();
            // (R = ACOS_ONLY_OWNED_BY_USER_AND_SOLE_MANAGER_GROUPS)
            $arosIds = [$userId];
            $arosIds = array_merge($arosIds, $groupsSoleManager);
            $query = $this->findResourcesArosIsSoleOwner($arosIds);

            // (USER_AND_SOLE_MANAGER_NON_EMPTY_GROUPS)
            $nonEmptyGroupsSoleManager = $GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($userId)->extract('group_id')->toArray();
            if (!empty($nonEmptyGroupsSoleManager)) {
                // (ACOS_ONLY_OWNED_BY_USER_AND_SOLE_MANAGER_NON_EMPTY_GROUPS)
                $acosOnlyOwnedByUsersAndSoleManagerOfNonEmptyGroups = $this->find()
                    ->select('aco_foreign_key')->distinct()
                    ->where(['type' => Permission::OWNER, 'aro_foreign_key IN' => $nonEmptyGroupsSoleManager]);

                // (R = R - ONLY_OWNED_BY_USER_AND_SOLE_MANAGER_NON_EMPTY_GROUPS)
                $query->where(['aco_foreign_key NOT IN' => $acosOnlyOwnedByUsersAndSoleManagerOfNonEmptyGroups]);
            }

            // (ACOS_ONLY_ACCESSIBLE_BY_USER)
            $subquery = $this->findResourcesOnlyUserCanAccess($userId, true);
            // (R = R - ACOS_ONLY_ACCESSIBLE_BY_USER)
            $query->where(['aco_foreign_key NOT IN' => $subquery]);
        } else {
            $arosIds = [$userId];
            // (R = ACOS_ONLY_OWNED_BY_USER)
            $query = $this->findResourcesArosIsSoleOwner($arosIds);
            // (ACOS_ONLY_ACCESSIBLE_BY_USER)
            $subquery = $this->findResourcesOnlyUserCanAccess($userId, $checkGroupsUsers);
            // (R = R - ACOS_ONLY_ACCESSIBLE_BY_USER)
            $query->where(['aco_foreign_key NOT IN' => $subquery]);
        }

        return $query;
    }

    /**
     * Group alias for findSharedResourcesAroIsSoleOwner
     *
     * @param string $groupId uuid of the group
     * @throws \InvalidArgumentException if the group id is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findSharedResourcesGroupIsSoleOwner(string $groupId)
    {
        if (!Validation::uuid($groupId)) {
            throw new \InvalidArgumentException(__('The group id should be a valid uuid.'));
        }

        // (R = ACOS_ONLY_OWNED_BY_GROUP
        $query = $this->findResourcesArosIsSoleOwner([$groupId]);
        // (ACOS_ONLY_ACCESSIBLE_BY_GROUP)
        $subquery = $this->findResourcesOnlyArosCanAccess([$groupId]);
        // (R = R - ACOS_ONLY_ACCESSIBLE_BY_GROUP)
        $query->where(['aco_foreign_key NOT IN' => $subquery]);

        return $query;
    }

    /**
     * Returns an array of resources the given AROs are the owner of.
     *
     * @param array $arosIds uuid of the users|groups
     * @throw \InvalidArgumentException if the aros ids are not valid uuids
     * @return \Cake\ORM\Query
     */
    public function findResourcesArosIsSoleOwner(array $arosIds)
    {
        foreach ($arosIds as $aroId) {
            if (!Validation::uuid($aroId)) {
                throw new \InvalidArgumentException(__('The aro id should be a valid uuid.'));
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
                'aro_foreign_key NOT IN' => $arosIds,
                'type' => Permission::OWNER
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
                'aro_foreign_key IN' => $arosIds,
                'type' => Permission::OWNER,
            ])
            // ACOS_OWNED_BY_USERS_AND_GROUPS - ACOS_OWNED_BY_OTHER_USERS_AND_GROUPS
            ->where(['aco_foreign_key NOT IN' => $acosOwnedByOtherUsersAndGroups]);
    }

    /**
     * Returns the list of resources ids that the ARO has access
     * and that are not shared with anybody
     *
     * Note: this does not check for ownership right. In theory it should not be possible to have
     * a resource with only a group|user permission set to anything else than OWNER,
     * but since we might as well delete these, we do cast a wider net.
     *
     * @param string $aroId uuid
     * @throws \InvalidArgumentException if the aro id is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findResourcesOnlyAroCanAccess(string $aroId)
    {
        if (!Validation::uuid($aroId)) {
            throw new \InvalidArgumentException(__('The aro id should be a valid uuid.'));
        }

        $arosIds = [$aroId];

        return $this->findResourcesOnlyArosCanAccess($arosIds);
    }

    /**
     * Returns the list of resources ids that the ARO has access
     * and that are not shared with anybody
     *
     * Note: this does not check for ownership right. In theory it should not be possible to have
     * a resource with only a group|user permission set to anything else than OWNER,
     * but since we might as well delete these, we do cast a wider net.
     *
     * @param array $arosIds array of uuid
     * @throws \InvalidArgumentException if the aro id is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findResourcesOnlyArosCanAccess(array $arosIds)
    {
        foreach ($arosIds as $aroId) {
            if (!Validation::uuid($aroId)) {
                throw new \InvalidArgumentException(__('The aro id should be a valid uuid.'));
            }
        }

        // R = All the resources that are only accessible by a list of users and/or groups.
        //
        // AROS, all users or groups that have entries in the permissions table (aro_foreign_key)
        // USER_AND_GROUPS, is a set of AROs represented by the user and groups given as parameter
        // OTHER_USERS_AND_GROUPS, is a set of AROs represented by the users and groups which are not USER_AND_GROUPS
        // ACOS, all the resources that have entries in the permissions table (aro_foreign_key)
        // ACOS_ACCESSIBLE_BY_USERS_AND_GROUPS, is the set of AROS that are accessible by USERS_AND_GROUPS
        // ACOS_ACCESSIBLE_BY_OTHER_USERS_AND_GROUPS, is the set of AROS that are accessible by OTHER_USERS_AND_GROUPS
        // R = ACOS_ACCESSIBLE_BY_USERS_AND_GROUPS - ACOS_ACCESSIBLE_BY_OTHER_USERS_AND_GROUPS, is the set of ACOS only accessible by USERS_AND_GROUPS

        // (ACOS_ACCESSIBLE_BY_OTHER_USERS_AND_GROUPS)
        // SELECT aco_foreign_key
        // FROM permissions
        // WHERE aro_foreign_key NOT IN (USER_AND_GROUPS)
        $acosAccessibleByOtherUsersAndGroups = $this->find()
            ->select(['aco_foreign_key'])
            ->where(['aro_foreign_key NOT IN' => $arosIds]);

        // SELECT aco_foreign_key
        // FROM permissions
        // WHERE aro_foreign_key IN (USER_AND_GROUPS)
        // AND aco_foreign_key NOT IN (ACOS_ACCESSIBLE_BY_OTHER_USERS_AND_GROUPS)
        return $this->find()
            ->select(['aco_foreign_key'])->distinct()
            // ACOS_ACCESSIBLE_BY_USERS_AND_GROUPS
            ->where(['aro_foreign_key IN' => $arosIds])
            // ACOS_ACCESSIBLE_BY_USERS_AND_GROUPS - ACOS_ACCESSIBLE_BY_OTHER_USERS_AND_GROUPS
            ->where(['aco_foreign_key NOT IN' => $acosAccessibleByOtherUsersAndGroups]);
    }

    /**
     * User alias for findResourcesOnlyAroCanAccess
     *
     * @param string $userId uuid
     * @param bool $checkGroupsUsers also check for group user is sole member of
     * @throws \InvalidArgumentException if the user id is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findResourcesOnlyUserCanAccess(string $userId, bool $checkGroupsUsers = false)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        $arosIds = [$userId];
        if ($checkGroupsUsers) {
            $GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
            $groups = $GroupsUsers->findGroupsWhereUserOnlyMember($userId)->extract('group_id')->toArray();
            $arosIds = array_merge($arosIds, $groups);
        }

        return $this->findResourcesOnlyArosCanAccess($arosIds);
    }

    /**
     * Group alias for findResourcesOnlyAroCanAccess
     *
     * @param string $groupId uuid
     * @throws \InvalidArgumentException if the group id is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findResourcesOnlyGroupCanAccess(string $groupId)
    {
        if (!Validation::uuid($groupId)) {
            throw new \InvalidArgumentException(__('The group id should be a valid uuid.'));
        }

        return $this->findResourcesOnlyAroCanAccess($groupId);
    }

    /**
     * Group alias for findResourcesOnlyArosCanAccess
     *
     * @param array $groupsIds list of uuid
     * @throws \InvalidArgumentException if one of the group id is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findResourcesOnlyGroupsCanAccess(array $groupsIds = [])
    {
        foreach ($groupsIds as $groupId) {
            if (!Validation::uuid($groupId)) {
                throw new \InvalidArgumentException(__('The groups ids should be valid uuids.'));
            }
        }

        return $this->findResourcesOnlyArosCanAccess($groupsIds);
    }

    /**
     * Returns the list of resources that a user own.
     *
     * @param string $userId uuid
     * @param bool $checkGroupsUsers also check for group user is member of
     * @throws \InvalidArgumentException if the aro id is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findResourcesUserIsOwner(string $userId, bool $checkGroupsUsers = false)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        $arosIds = [$userId];
        if ($checkGroupsUsers) {
            $groupsIds = $this->Groups->GroupsUsers->findGroupsWhereUserIsMember($userId)->extract('group_id')->toArray();
            if (!empty($groupsIds)) {
                $arosIds = array_merge($arosIds, $groupsIds);
            }
        }

        return $this->findResourcesArosIsOwner($arosIds);
    }

    /**
     * Returns the list of resources that Aros (users and/or groups) are owner.
     *
     * @param array $arosIds array of uuid
     * @throws \InvalidArgumentException if an aro id is not a valid uuid
     * @return \Cake\ORM\Query
     */
    public function findResourcesArosIsOwner(array $arosIds)
    {
        foreach ($arosIds as $aroId) {
            if (!Validation::uuid($aroId)) {
                throw new \InvalidArgumentException(__('The aro id should be a valid uuid.'));
            }
        }

        return $this->find()
            ->select('aco_foreign_key')->distinct()
            ->where([
                'aro_foreign_key IN' => $arosIds,
                'type' => Permission::OWNER
            ]);
    }
}
