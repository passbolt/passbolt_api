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

use App\Model\Entity\Permission;
use App\Model\Rule\IsNotSoftDeletedRule;
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
            ->inList('aco', self::ALLOWED_ACOS)
            ->requirePresence('aco', 'create')
            ->notEmpty('aco');

        $validator
            ->uuid('aco_foreign_key')
            ->requirePresence('aco_foreign_key', 'create')
            ->notEmpty('aco_foreign_key');

        $validator
            ->inList('aro', self::ALLOWED_AROS)
            ->requirePresence('aro', 'create')
            ->notEmpty('aro');

        $validator
            ->uuid('aro_foreign_key')
            ->requirePresence('aro_foreign_key', 'create')
            ->notEmpty('aro_foreign_key');

        $validator
            ->inList('type', self::ALLOWED_TYPES)
            ->requirePresence('type', 'create')
            ->notEmpty('type');

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
     * Custom validation rule to validate permission type
     *
     * @param int $value permission type
     * @return bool
     */
    public function isValidPermissionType($value)
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
    public function acoExistsRule($entity, $options)
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
    public function aroExistsRule($entity, $options)
    {
        $rules = new RulesChecker($options);
        $aro = Inflector::pluralize($entity->aro);
        if (in_array($aro, ['Users', 'Groups'])) {
            $existRule = $rules->existsIn('aro_foreign_key', $aro);
            $existIn = $existRule($entity, $options);
            $isNotSoftDeletedRule = new IsNotSoftDeletedRule();
            $isNotSoftDeleted = $isNotSoftDeletedRule($entity, [
                'table' => $aro,
                'errorField' => 'aro_foreign_key',
            ]);

            return $existIn && $isNotSoftDeleted;
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
    public function findViewAcoPermissions($acoForeignKey, $options = [])
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
            $query->contain('Users.Profiles');
            // @TODO when Avatars model is implemented.
            // ->contain('Users.Profiles.Avatars');
        }

        return $query;
    }

    /**
     * User alias for findSharedResourcesAroIsSoleOwner
     *
     * @param string $userId uuid of the user
     * @return array $results the uuids of the resources
     */
    public function findSharedResourcesUserIsSoleOwner($userId)
    {
        return $this->findSharedResourcesAroIsSoleOwner($userId);
    }

    /**
     * Group alias for findSharedResourcesAroIsSoleOwner
     *
     * @param string $groupId uuid of the group
     * @return array $results the uuids of the resources
     */
    public function findSharedResourcesGroupIsSoleOwner($groupId)
    {
        return $this->findSharedResourcesAroIsSoleOwner($groupId);
    }

    /**
     * Returns an array of resources the given ARO is the owner of
     * and that are shared with somebody else. Useful to know which resources
     * need to be transferred when deleting the user or a group
     *
     * @param string $aroId uuid of the user|group
     * @return array $results the uuids of the resources
     */
    public function findSharedResourcesAroIsSoleOwner($aroId)
    {
        $results = [];

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
     * @return array $results the uuids of the resources
     */
    public function findSharedResourcesSoleGroupManagerIsSoleOwner($userId)
    {
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
    private function _extractResourcesWhereAroIsSoleOwner($resources)
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
                $someRead = (isset($rights[Permission::READ]) && count($rights[Permission::READ]));
                $someUpdate = (isset($rights[Permission::UPDATE]) && count($rights[Permission::UPDATE]));
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
     * @return array list of resource uuid
     */
    public function findResourcesOnlyAroCanAccess($aroId)
    {
        // SELECT aco_foreign_key, count(aro_foreign_key) as aro_count
        // FROM permissions
        // GROUP by aco_foreign_key
        // HAVING aro_foreign_key=$aroId
        // AND aro_count=1;

        $query = $this->find();
        $resources = $query
            ->select([
                'aco_foreign_key' => 'aco_foreign_key',
                'aro_foreign_key' => 'aro_foreign_key',
                'aro_count' => $query->func()->count('id')
            ])
            ->group(['aco_foreign_key'])
            ->having(['aro_foreign_key' => $aroId, 'aro_count' => 1])
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
     * @return array list of resource uuid
     */
    public function findResourcesOnlyUserCanAccess($userId, $checkGroupsUsers = false)
    {
        $resources = $this->findResourcesOnlyAroCanAccess($userId);
        if ($checkGroupsUsers) {
            // @TODO is it doable in one request?
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
     * @return array list of resource uuid
     */
    public function findResourcesOnlyGroupCanAccess($groupId)
    {
        return $this->findResourcesOnlyAroCanAccess($groupId);
    }
}
