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

namespace App\Model\Table;

use App\Model\Entity\Permission;
use App\Model\Rule\IsActiveRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Cleanup\ResourcesCleanupTrait;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Permissions\PermissionsFindersTrait;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
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
    use PermissionsFindersTrait;
    use TableCleanupTrait;

    const RESOURCE_ACO = 'Resource';
    const FOLDER_ACO = 'Folder';

    const USER_ARO = 'User';
    const GROUP_ARO = 'Group';

    /**
     * List of allowed aco models on which Permissions can be plugged.
     */
    const ALLOWED_ACOS = [
        self::RESOURCE_ACO,
        self::FOLDER_ACO,
    ];

    /**
     * List of allowed aro models on which Permissions can be plugged.
     */
    const ALLOWED_AROS = [
        self::GROUP_ARO,
        self::USER_ARO,
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
            'foreignKey' => 'aro_foreign_key',
        ]);
        $this->belongsTo('Resources', [
            'foreignKey' => 'aco_foreign_key',
        ]);

        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $this->belongsTo('Folders', [
                'foreignKey' => 'aco_foreign_key',
            ]);
        }

        $this->belongsTo('Users', [
            'foreignKey' => 'aro_foreign_key',
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->inList('aco', self::ALLOWED_ACOS, __(
                'The aco must be one of the following: {0}.',
                implode(', ', self::ALLOWED_ACOS)
            ))
            ->requirePresence('aco', 'create', __('The aco is required.'))
            ->notEmptyString('aco', __('The aco cannot be empty.'));

        $validator
            ->uuid('aco_foreign_key')
            ->requirePresence('aco_foreign_key', 'create')
            ->notEmptyString('aco_foreign_key');

        $validator
            ->inList('aro', self::ALLOWED_AROS, __(
                'The aro must be one of the following: {0}.',
                implode(', ', self::ALLOWED_AROS)
            ))
            ->requirePresence('aro', 'create', __('The aro is required.'))
            ->notEmptyString('aro', __('The aro cannot be empty.'));

        $validator
            ->uuid('aro_foreign_key')
            ->requirePresence('aro_foreign_key', 'create')
            ->notEmptyString('aro_foreign_key');

        $validator
            ->inList('type', self::ALLOWED_TYPES, __(
                'The type must be one of the following: {0}.',
                implode(', ', self::ALLOWED_TYPES)
            ))
            ->requirePresence('type', 'create', __('The type is required.'))
            ->notEmptyString('type', __('The type cannot be empty.'));

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
        return is_int($value) && in_array($value, self::ALLOWED_TYPES);
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
            'message' => __('The aco does not exist.'),
        ]);
        $rules->addCreate([$this, 'aroExistsRule'], 'aro_exists', [
            'errorField' => 'aro_foreign_key',
            'message' => __('The aro does not exist.'),
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
        $exist = false;

        switch ($entity->aco) { // Change this implementation  next time a new ACO is created
            case static::RESOURCE_ACO:
                $rule = $rules->existsIn('aco_foreign_key', 'Resources');
                $existIn = $rule($entity, $options);
                $rule = new IsNotSoftDeletedRule();
                $isNotSoftDeleted = $rule($entity, [
                    'table' => 'Resources',
                    'errorField' => 'aco_foreign_key',
                ]);
                $exist = $existIn && $isNotSoftDeleted;
                break;
            case static::FOLDER_ACO:
                $rule = $rules->existsIn('aco_foreign_key', 'Folders');
                $existIn = $rule($entity, $options);
                $rule = new IsNotSoftDeletedRule();
                $isNotSoftDeleted = $rule($entity, [
                    'table' => 'Folders',
                    'errorField' => 'aco_foreign_key',
                ]);
                $exist = $existIn && $isNotSoftDeleted;
                break;
        }

        return $exist;
    }

    /**
     * Checks that the aro exists
     *
     * @param EntityInterface $entity The entity to test
     * @param array $options The additional options for this rule
     * @return bool
     */
    public function aroExistsRule(EntityInterface $entity, array $options)
    {
        $rules = new RulesChecker($options);
        $aro = Inflector::pluralize($entity->aro);
        $singularizedAro = Inflector::singularize($aro);
        if (in_array($singularizedAro, self::ALLOWED_AROS)) {
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
            if ($singularizedAro === self::USER_ARO) {
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
                'aro' => ucfirst(Inflector::singularize($modelName)),
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
     * Delete all association records where associated model entities are soft deleted
     *
     * @param string $modelName model
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupSoftDeletedAco(string $modelName, $dryRun = false)
    {
        $query = $this->query()
            ->select(['id'])
            ->leftJoinWith($modelName)
            ->where([
                $modelName . '.deleted' => true,
                'aco' => ucfirst(Inflector::singularize($modelName)),
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
    public function cleanupHardDeletedAco(string $modelName, $dryRun = false)
    {
        $query = $this->query()
            ->select(['id'])
            ->leftJoinWith($modelName)
            ->where(function ($exp, $q) use ($modelName) {
                return $exp
                    ->isNull($modelName . '.id')
                    ->eq('aco', ucfirst(Inflector::singularize($modelName)));
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

    /**
     * Delete all records where associated resources are deleted
     *
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupHardDeletedResources(bool $dryRun = false)
    {
        return $this->cleanupHardDeletedAco('Resources', $dryRun);
    }

    /**
     * Delete all records where associated resources are deleted
     *
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupSoftDeletedResources(bool $dryRun = false)
    {
        return $this->cleanupSoftDeletedAco('Resources', $dryRun);
    }
}
