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
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\ResourcesTable|\Cake\ORM\Association\BelongsTo $Resources
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
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
     * User alias for findSharedResourcesAroIsSoleOwner
     *
     * @param string $userId uuid of the user
     * @throws \InvalidArgumentException if the user id is not a valid uuid
     * @return array $results the uuids of the resources
     */
    public function findSharedResourcesUserIsSoleOwner(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        return $this->findSharedResourcesAroIsSoleOwner($userId);
    }

    /**
     * Group alias for findSharedResourcesAroIsSoleOwner
     *
     * @param string $groupId uuid of the group
     * @throws \InvalidArgumentException if the group id is not a valid uuid
     * @return array $results the uuids of the resources
     */
    public function findSharedResourcesGroupIsSoleOwner(string $groupId)
    {
        if (!Validation::uuid($groupId)) {
            throw new \InvalidArgumentException(__('The group id should be a valid uuid.'));
        }

        return $this->findSharedResourcesAroIsSoleOwner($groupId);
    }

    /**
     * Returns an array of resources the given ARO is the owner of
     * and that are shared with somebody else. Useful to know which resources
     * need to be transferred when deleting the user or a group
     *
     * @param string $aroId uuid of the user|group
     * @throw \InvalidArgumentException if the aro id is not a valid uuid
     * @return array $results the uuids of the resources
     */
    public function findSharedResourcesAroIsSoleOwner(string $aroId)
    {
        if (!Validation::uuid($aroId)) {
            throw new \InvalidArgumentException(__('The aro id should be a valid uuid.'));
        }

        // Show the ARO counts by permissions for all the resources
        // the given user or group is the owner of.
        //
        // SELECT permissions.aco_foreign_key AS resource_id,
        //    permissions.type, count(permissions.id) AS aro_count
        // FROM permissions
        // WHERE permissions.aco_foreign_key IN (
        //    SELECT permissions.aco_foreign_key
        //    FROM permissions
        //    WHERE permissions.type = Permission::OWNER
        //    AND permissions.aro_foreign_key = :aroId
        // )
        // GROUP BY permissions.aco_foreign_key, permissions.type
        // ORDER BY permissions.aco_foreign_key, permissions.type;
        //
        // Returns something like:
        // +--------------------------------------+--------+------------+
        // | resource_id                          | type   | aro_count  |
        // +--------------------------------------+--------+------------+
        // | 8378fa3d-b9f4-5428-90a4-ab5478c1a5bb | READ   |          2 |
        // | 8378fa3d-b9f4-5428-90a4-ab5478c1a5bb | WRITE  |          1 |
        // | 8378fa3d-b9f4-5428-90a4-ab5478c1a5bb | OWNER  |          1 |
        // | ...                                  |  ...   |        ... |
        // +--------------------------------------+--------+------------+

        // Find all the resources the ARO is owner of
        $subquery = $this->find()
            ->select(['aco_foreign_key'])
            ->where(['type' => Permission::OWNER, 'aro_foreign_key' => $aroId]);

        // Find the user count by permissions for these
        $query = $this->find();
        $resources = $query
            ->select([
                'aco_foreign_key' => 'aco_foreign_key',
                'type' => 'type',
                'aro_count' => $query->func()->count('id')
            ])
            ->where(['aco_foreign_key IN' => $subquery])
            ->group(['aco_foreign_key', 'type'])
            ->order(['aco_foreign_key', 'type'])
            ->all()
            ->toArray();

        return $this->_extractResourcesWhereAroIsSoleOwner($resources);
    }

    /**
     * Find list of shared resources ids for all the groups for a given user
     * where the user is the only admin of these groups
     *
     * Useful to make sure we do not delete a user from a group that would make a resource
     * loose its only owner.
     *
     * @param string $userId uuid of the user
     * @throws \InvalidArgumentException if the user id is not a valid uuid
     * @return array $results the uuids of the resources
     */
    public function findSharedResourcesSoleGroupManagerIsSoleOwner(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        // Show the ARO counts by permissions for all the resources
        // the given user is the only admin of a non empty group
        // and this group is the only owner of the resource
        //
        // SELECT permissions.aco_foreign_key AS resource_id,
        //    permissions.type, count(permissions.id) AS aro_count
        // FROM permissions
        // WHERE permissions.aco_foreign_key IN (
        //    SELECT permissions.aco_foreign_key
        //    FROM permissions
        //    WHERE permissions.type = Permission::OWNER
        //    AND permissions.aro_foreign_key IN (
        //         'uuid_group1', 'uuid_group2', etc.
        //    )
        // )
        // GROUP BY permissions.aco_foreign_key, permissions.type
        // ORDER BY permissions.aco_foreign_key, permissions.type;
        //
        // Returns something like:
        // +--------------------------------------+--------+------------+
        // | resource_id                          | type   | aro_count  |
        // +--------------------------------------+--------+------------+
        // | 8378fa3d-b9f4-5428-90a4-ab5478c1a5bb | READ   |          2 |
        // | 8378fa3d-b9f4-5428-90a4-ab5478c1a5bb | WRITE  |          1 |
        // | 8378fa3d-b9f4-5428-90a4-ab5478c1a5bb | OWNER  |          1 |
        // | ...                                  |  ...   |        ... |
        // +--------------------------------------+--------+------------+

        // Find all the groups a user is the only member (and thus only manager)
        $GroupsUsers = TableRegistry::get('GroupsUsers');
        $subquery1 = $GroupsUsers->findGroupsWhereUserOnlyMember($userId);
        if (empty($subquery1)) {
            return [];
        }

        // Find all the resources groups are owner of
        $subquery2 = $this->find()
            ->select(['aco_foreign_key'])
            ->where(['type' => Permission::OWNER, 'aro_foreign_key IN' => $subquery1]);
        if (empty($subquery2)) {
            return [];
        }

        // Find the user|group count by permissions for these
        $query = $this->find();
        $resources = $query
            ->select([
                'aco_foreign_key' => 'aco_foreign_key',
                'type' => 'type',
                'aro_count' => $query->func()->count('id')
            ])
            ->where(['aco_foreign_key IN' => $subquery2])
            ->group(['aco_foreign_key', 'type'])
            ->order(['aco_foreign_key', 'type'])
            ->all()
            ->toArray();

        return $this->_extractResourcesWhereAroIsSoleOwner($resources);
    }

    /**
     * Extract resources id where ARO is sole owner from a list of resources
     * and their associated permissions map. See. findSharedResourcesGroupManagerIsSoleOwner
     *
     * @param array $resources list of resources with associated permissions type count
     * @return array
     */
    private function _extractResourcesWhereAroIsSoleOwner(array $resources)
    {
        $results = [];

        // No resources, no problem
        if (empty($resources) || count($resources) === 0) {
            return $results;
        }

        // Hash around the results to look like a table where the aro id is the key
        // and the count of each permission rights is a sub table with permission types as keys
        //
        // Example:
        // [
        //      [8378fa3d-b9f4-5428-90a4-ab5478c1a5bb] => [
        //          [1] => 2
        //          [7] => 2
        //          [15] => 1
        //      ]
        // ]
        $resources = Hash::combine($resources, '{n}.type', '{n}.aro_count', '{n}.aco_foreign_key');
        foreach ($resources as $resourceId => $rights) {
            if ($rights[Permission::OWNER] === 1) {
                $someRead = !empty($rights[Permission::READ]);
                $someUpdate = !empty($rights[Permission::UPDATE]);
                if ($someRead || $someUpdate) {
                    $results[] = $resourceId;
                }
            }
        }

        return $results;
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
     * @return array list of resource uuid
     */
    public function findResourcesOnlyAroCanAccess(string $aroId)
    {
        if (!Validation::uuid($aroId)) {
            throw new \InvalidArgumentException(__('The aro id should be a valid uuid.'));
        }

        // SELECT aco_foreign_key, count(aro_foreign_key) as aro_count
        // FROM permissions
        // WHERE aco_foreign_key IN (
        //    SELECT aco_foreign_key
        //    FROM permissions
        //    WHERE aro_foreign_key = $aroId
        // )
        // GROUP by aco_foreign_key
        // HAVING aro_count=1;

        $subquery = $this->find();
        $subquery->select(['aco_foreign_key'])
            ->where(['aro_foreign_key' => $aroId]);

        $query = $this->find();
        $resources = $query
            ->select([
                'aco_foreign_key' => 'aco_foreign_key',
                'aro_count' => $query->func()->count('id')
            ])
            ->where(['aco_foreign_key IN' => $subquery])
            ->group(['aco_foreign_key'])
            ->having(['aro_count' => 1])
            ->all()
            ->toArray();

        if (!empty($resources)) {
            $resources = Hash::extract($resources, '{n}.aco_foreign_key');
        }

        return $resources;
    }

    /**
     * User alias for findResourcesOnlyAroCanAccess
     *
     * @param string $userId uuid
     * @param bool $checkGroupsUsers also check for group user is sole member of
     * @throws \InvalidArgumentException if the user id is not a valid uuid
     * @return array list of resource uuid
     */
    public function findResourcesOnlyUserCanAccess(string $userId, bool $checkGroupsUsers = false)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }
        $resources = $this->findResourcesOnlyAroCanAccess($userId);
        if ($checkGroupsUsers) {
            $GroupsUsers = TableRegistry::get('GroupsUsers');
            $groups = $GroupsUsers->findGroupsWhereUserOnlyMember($userId);
            foreach ($groups as $i => $groupId) {
                $r = $this->findResourcesOnlyGroupCanAccess($groupId);
                if (!empty($r)) {
                    $resources = array_merge($r, $resources);
                }
            }
        }

        return $resources;
    }

    /**
     * Group alias for findResourcesOnlyAroCanAccess
     *
     * @param string $groupId uuid
     * @throws \InvalidArgumentException if the group id is not a valid uuid
     * @return array list of resource uuid
     */
    public function findResourcesOnlyGroupCanAccess(string $groupId)
    {
        if (!Validation::uuid($groupId)) {
            throw new \InvalidArgumentException(__('The group id should be a valid uuid.'));
        }

        return $this->findResourcesOnlyAroCanAccess($groupId);
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
