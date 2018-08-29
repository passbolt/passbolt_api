<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Model\Table;

use App\Error\Exception\ValidationRuleException;
use App\Model\Entity\Permission;
use App\Model\Rule\IsActiveRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Cleanup\ResourcesCleanupTrait;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * Permissions Model
 *
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\BelongsTo $Resources
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Permission get($primaryKey, $options = [])
 * @method \App\Model\Entity\Permission newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Permission[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Permission|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Permission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Permission[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Permission findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PermissionsTable extends Table
{
    use ResourcesCleanupTrait;
    use TableCleanupTrait;

    /**
     * List of allowed aco models on which Permissions can be plugged.
     */
    const ALLOWED_ACOS = [
        'Resource'
    ];

    /**
     * List of allowed aro models on which Permissions can be plugged.
     */
    const ALLOWED_AROS = [
        'Group',
        'User',
    ];

    /**
     * List of allowed permission types.
     */
    const ALLOWED_TYPES = [
        Permission::READ,
        Permission::UPDATE,
        Permission::OWNER,
    ];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('permissions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Groups', [
            'foreignKey' => 'aro_foreign_key'
        ]);
        $this->belongsTo('Resources', [
            'foreignKey' => 'aco_foreign_key'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'aro_foreign_key'
        ]);

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->inList('aco', self::ALLOWED_ACOS, __(
                'The aco must be one of the following: {0}.',
                implode(', ', self::ALLOWED_ACOS)
            ))
            ->requirePresence('aco', 'create', __('The aco is required.'))
            ->notEmpty('aco', __('The aco cannot be empty.'));

        $validator
            ->uuid('aco_foreign_key')
            ->requirePresence('aco_foreign_key', 'create')
            ->notEmpty('aco_foreign_key');

        $validator
            ->inList('aro', self::ALLOWED_AROS, __(
                'The aro must be one of the following: {0}.',
                implode(', ', self::ALLOWED_AROS)
            ))
            ->requirePresence('aro', 'create', __('The aro is required.'))
            ->notEmpty('aro', __('The aro cannot be empty.'));

        $validator
            ->uuid('aro_foreign_key')
            ->requirePresence('aro_foreign_key', 'create')
            ->notEmpty('aro_foreign_key');

        $validator
            ->inList('type', self::ALLOWED_TYPES, __(
                'The type must be one of the following: {0}.',
                implode(', ', self::ALLOWED_TYPES)
            ))
            ->requirePresence('type', 'create', __('The type is required.'))
            ->notEmpty('type', __('The type cannot be empty.'));

        return $validator;
    }

    /**
     * Create resource validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationSaveResource(Validator $validator)
    {
        $validator = $this->validationDefault($validator);

        // The resource_id is added by cake after the resource is created.
        $validator->remove('aco_foreign_key');

        return $validator;
    }

    /**
     * Validate a permission type
     *
     * @param int $value permission type
     * @return bool
     */
    public function isValidPermissionType(int $value)
    {
        $permissionTypes = [
            Permission::READ,
            Permission::UPDATE,
            Permission::OWNER
        ];

        return is_int($value) && in_array($value, $permissionTypes);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->addCreate(
            $rules->isUnique(
                ['aco_foreign_key', 'aro_foreign_key'],
                __('A permission already exists for the given aco and aro.')
            ),
            'permission_unique'
        );
        $rules->addCreate([$this, 'acoExistsRule'], 'aco_exists', [
            'errorField' => 'aco_foreign_key',
            'message' => __('The aco does not exist.')
        ]);
        $rules->addCreate([$this, 'aroExistsRule'], 'aro_exists', [
            'errorField' => 'aro_foreign_key',
            'message' => __('The aro does not exist.')
        ]);

        return $rules;
    }

    /**
     * Checks that the aco exists
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to test
     * @param array $options The additional options for this rule
     * @return bool
     */
    public function acoExistsRule(\Cake\Datasource\EntityInterface $entity, array $options)
    {
        $rules = new RulesChecker($options);
        if ($entity->aco == 'Resource') {
            $rule = $rules->existsIn('aco_foreign_key', 'Resources');
            $existIn = $rule($entity, $options);
            $rule = new IsNotSoftDeletedRule();
            $isNotSoftDeleted = $rule($entity, [
                'table' => 'Resources',
                'errorField' => 'aco_foreign_key',
            ]);

            return $existIn && $isNotSoftDeleted;
        }

        return false;
    }

    /**
     * Checks that the aro exists
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to test
     * @param array $options The additional options for this rule
     * @return bool
     */
    public function aroExistsRule(\Cake\Datasource\EntityInterface $entity, array $options)
    {
        $rules = new RulesChecker($options);
        $aro = Inflector::pluralize($entity->aro);
        if (in_array($aro, ['Users', 'Groups'])) {
            // The aro instance exists.
            $existRule = $rules->existsIn('aro_foreign_key', $aro);
            $existIn = $existRule($entity, $options);
            // The aro instance is not soft deleted.
            $isNotSoftDeletedRule = new IsNotSoftDeletedRule();
            $isNotSoftDeleted = $isNotSoftDeletedRule($entity, [
                'table' => $aro,
                'errorField' => 'aro_foreign_key',
            ]);
            // The user is active.
            $isActive = true;
            if ($aro == 'Users') {
                $isActiveRule = new IsActiveRule();
                $isActive = $isActiveRule($entity, [
                    'table' => $aro,
                    'errorField' => 'aro_foreign_key',
                ]);
            }

            return $existIn && $isNotSoftDeleted && $isActive;
        }

        return false;
    }

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

            $GroupsUsers = TableRegistry::get('GroupsUsers');
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
     * @param string $arosIds uuid
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
            $GroupsUsers = TableRegistry::get('GroupsUsers');
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
     * Patch a list of permission entities with a list of changes.
     * A change is formatted as following :
     *
     * - Add a new permission:
     * [
     *   'aro' => string,
     *   'aro_foreign_key' => uuid,
     *   'type' => int
     * ]
     *
     * - Update a permission:
     * [
     *   'id' => uuid,
     *   'type' => int
     * ]
     *
     * - Delete a permission
     * [
     *   'id' => uuid,
     *   'delete' => boolean
     * ]
     *
     * The 4th parameter $changeReferences will allow the caller to know on which permissions the changes have been
     * applied on.
     *
     * Example :
     * $changesReferences = [0=>4];
     *
     * It means the first change of the list of changes has been applied to the 5th permission in the list of
     * permission entities.
     *
     * @param array $entities The list of permissions entities to patch
     * @param array $changes The changes to apply
     * @param null $acoForeignKey The aco identifier that the entities belong to
     * @param array $changesReferences A reference list of the applied changes
     * @throw ValidationRuleException If a change try to modify a permission that is not in the list of permissions
     * @throw ValidationRuleException If a change does not validate when calling patchEntity
     * @throw ValidationRuleException If a change does not validate when calling newEntity
     * @return array The list of permissions entities patched with the changes
     */
    public function patchEntitiesWithChanges($entities = [], $changes = [], $acoForeignKey = null, &$changesReferences = [])
    {
        foreach ($changes as $changeKey => $change) {
            // Update or Delete case.
            if (isset($change['id'])) {
                // Retrieve the permission a change is requested for.
                $permissionKey = null;
                foreach ($entities as $key => $entity) {
                    if ($entity['id'] == $change['id']) {
                        $permissionKey = $key;
                        break;
                    }
                }
                // The permission does not belong to the resource.
                if (is_null($permissionKey)) {
                    $errors = ['id' => [
                        'permission_exists' => __('The permission does not exist.', $change['id'])
                    ]];
                    throw new ValidationRuleException(__('Validation error.'), [$changeKey => $errors]);
                }
                // Keep a trace of the permission entity the change will be applied on.
                $changesReferences[$changeKey] = $permissionKey;

                // Delete case.
                if (isset($change['delete']) && $change['delete']) {
                    unset($entities[$permissionKey]);
                } else {
                    // Update case
                    $options = ['accessibleFields' => ['type' => true]];
                    $this->patchEntity($entities[$permissionKey], $change, $options);
                    $errors = $entities[$permissionKey]->getErrors();
                    if (!empty($errors)) {
                        throw new ValidationRuleException(__('Validation error.'), [$changeKey => $errors]);
                    }
                }
            } else {
                // Add case.
                // Enforce data.
                $change['aco'] = 'Resource';
                $change['aco_foreign_key'] = $acoForeignKey;
                // New entity options.
                $options = ['accessibleFields' => [
                    'aco' => true,
                    'aco_foreign_key' => true,
                    'aro' => true,
                    'aro_foreign_key' => true,
                    'type' => true
                ]];
                // Create and validate the new permission entity.
                $permission = $this->newEntity($change, $options);
                $errors = $permission->getErrors();
                if (!empty($errors)) {
                    throw new ValidationRuleException(__('Validation error.'), [$changeKey => $errors]);
                }
                $entities[] = $permission;
                // Keep a trace of the permission entity the change will be applied on.
                $changesReferences[$changeKey] = count($entities) - 1;
            }
        }

        return $entities;
    }

    /**
     * Delete all association records where associated model entities are soft deleted
     *
     * @param string $modelName model
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupSoftDeletedAro(string $modelName, $dryRun = false)
    {
        $query = $this->query()
            ->select(['id'])
            ->leftJoinWith($modelName)
            ->where([
                $modelName . '.deleted' => true,
                'aro' => ucfirst(Inflector::singularize($modelName))
            ]);

        return $this->cleanupSoftDeleted($modelName, $dryRun, $query);
    }

    /**
     * Delete all association records where associated model entities are deleted
     *
     * @param string $modelName model
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupHardDeletedAro(string $modelName, $dryRun = false)
    {
        $query = $this->query()
            ->select(['id'])
            ->leftJoinWith($modelName)
            ->where(function ($exp, $q) use ($modelName) {
                return $exp
                    ->isNull($modelName . '.id')
                    ->eq('aro', ucfirst(Inflector::singularize($modelName)));
            });

        return $this->cleanupHardDeleted($modelName, $dryRun, $query);
    }

    /**
     * Delete all records where associated users are soft deleted
     *
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupSoftDeletedUsers($dryRun = false)
    {
        return $this->cleanupSoftDeletedAro('Users', $dryRun);
    }

    /**
     * Delete all records where associated users are deleted
     *
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupHardDeletedUsers($dryRun = false)
    {
        return $this->cleanupHardDeletedAro('Users', $dryRun);
    }

    /**
     * Delete all records where associated groups are soft deleted
     *
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupSoftDeletedGroups($dryRun = false)
    {
        return $this->cleanupSoftDeletedAro('Groups', $dryRun);
    }

    /**
     * Delete all records where associated groups are deleted
     *
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupHardDeletedGroups($dryRun = false)
    {
        return $this->cleanupHardDeletedAro('Groups', $dryRun);
    }
}
