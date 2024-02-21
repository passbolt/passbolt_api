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

namespace App\Model\Table;

use App\Model\Entity\Permission;
use App\Model\Rule\IsActiveRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Permissions\PermissionsFindersTrait;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;

/**
 * Permissions Model
 *
 * @property \App\Model\Table\GroupsTable&\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\Permission get($primaryKey, $options = [])
 * @method \App\Model\Entity\Permission newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Permission[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Permission|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Permission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Permission[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Permission findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \Passbolt\Log\Model\Table\PermissionsHistoryTable&\Cake\ORM\Association\BelongsTo $PermissionsHistory
 * @method \App\Model\Entity\Permission newEmptyEntity()
 * @method \App\Model\Entity\Permission saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\Permission>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Permission>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Permission>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Permission>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findByAcoForeignKeyAndType(string $acoForeignKey, int $type)
 * @method \Cake\ORM\Query findByAroAndAcoForeignKey(string $aro, string $acoForeignKey)
 * @method \Cake\ORM\Query findByIdAndAcoForeignKey(string $id, string $acoForeignKey)
 * @method \Cake\ORM\Query findByAcoForeignKeyAndAroForeignKey(string $acoForeignKey, string $aroForeignKey)
 */
class PermissionsTable extends Table
{
    use PermissionsFindersTrait;
    use TableCleanupTrait;

    public const RESOURCE_ACO = 'Resource';
    public const FOLDER_ACO = 'Folder';

    public const USER_ARO = 'User';
    public const GROUP_ARO = 'Group';

    /**
     * List of allowed aco models on which Permissions can be plugged.
     */
    public const ALLOWED_ACOS = [
        self::RESOURCE_ACO,
        self::FOLDER_ACO,
    ];

    /**
     * List of allowed aro models on which Permissions can be plugged.
     */
    public const ALLOWED_AROS = [
        self::GROUP_ARO,
        self::USER_ARO,
    ];

    /**
     * List of allowed permission types.
     */
    public const ALLOWED_TYPES = [
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
    public function initialize(array $config): void
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
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->inList('aco', self::ALLOWED_ACOS, __(
                'The type of the access control object should be one of the following: {0}.',
                implode(', ', self::ALLOWED_ACOS)
            ))
            ->requirePresence('aco', 'create', __('The type of the access control object is required.'))
            ->notEmptyString('aco', __('The type of the access control object should not be empty.'));

        $validator
            ->uuid('aco_foreign_key', __('The identifier of the access control object should be a valid UUID.'))
            ->requirePresence(
                'aco_foreign_key',
                'create',
                __('The identifier of the access control object is required.')
            )
            ->notEmptyString('aco_foreign_key', __('The identifier of the access control object should not be empty.'));

        $validator
            ->inList('aro', self::ALLOWED_AROS, __(
                'The access request object type should be one of the following: {0}.',
                implode(', ', self::ALLOWED_AROS)
            ))
            ->requirePresence('aro', 'create', __('The type of the access request object is required.'))
            ->notEmptyString('aro', __('The access request object type should not be empty.'));

        $validator
            ->uuid('aro_foreign_key', __('The identifier of the access request object should be a valid UUID.'))
            ->requirePresence(
                'aro_foreign_key',
                'create',
                __('The identifier of the access request object is required.')
            )
            ->notEmptyString('aro_foreign_key', __('The identifier of the access request object should not be empty.'));

        $validator
            ->inList('type', self::ALLOWED_TYPES, __(
                'The type should be one of the following: {0}.',
                implode(', ', self::ALLOWED_TYPES)
            ))
            ->requirePresence('type', 'create', __('A type is required.'))
            ->notEmptyString('type', __('The type should not be empty.'));

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
        return in_array($value, self::ALLOWED_TYPES);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addCreate(
            $rules->isUnique(
                ['aco_foreign_key', 'aro_foreign_key'],
                __('A permission already exists for the given access control object and access request object.')
            ),
            'permission_unique'
        );
        $rules->addCreate([$this, 'acoExistsRule'], 'aco_exists', [
            'errorField' => 'aco_foreign_key',
            'message' => __('The access control object does not exist.'),
        ]);
        $rules->addCreate([$this, 'aroExistsRule'], 'aro_exists', [
            'errorField' => 'aro_foreign_key',
            'message' => __('The access request object does not exist.'),
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

        switch ($entity->get('aco')) { // Change this implementation  next time a new ACO is created
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
     * @param \Cake\Datasource\EntityInterface $entity The entity to test
     * @param array $options The additional options for this rule
     * @return bool
     */
    public function aroExistsRule(EntityInterface $entity, array $options)
    {
        $rules = new RulesChecker($options);
        $aro = Inflector::pluralize($entity->get('aro'));
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
     * @return int Number of affected records
     */
    public function cleanupSoftDeletedAro(string $modelName, $dryRun = false): int
    {
        $query = $this->selectQuery()
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
     * @return int Number of affected records
     */
    public function cleanupHardDeletedAro(string $modelName, $dryRun = false): int
    {
        $query = $this->selectQuery()
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
     * @return int Number of affected records
     */
    public function cleanupSoftDeletedAco(string $modelName, $dryRun = false): int
    {
        $query = $this->selectQuery()
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
     * @return int Number of affected records
     */
    public function cleanupHardDeletedAco(string $modelName, $dryRun = false): int
    {
        $query = $this->selectQuery()
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
     * @return int Number of affected records
     */
    public function cleanupSoftDeletedUsers($dryRun = false): int
    {
        return $this->cleanupSoftDeletedAro('Users', $dryRun);
    }

    /**
     * Delete all records where associated users are deleted
     *
     * @param bool $dryRun false
     * @return int Number of affected records
     */
    public function cleanupHardDeletedUsers($dryRun = false): int
    {
        return $this->cleanupHardDeletedAro('Users', $dryRun);
    }

    /**
     * Delete all records where associated groups are soft deleted
     *
     * @param bool $dryRun false
     * @return int Number of affected records
     */
    public function cleanupSoftDeletedGroups($dryRun = false): int
    {
        return $this->cleanupSoftDeletedAro('Groups', $dryRun);
    }

    /**
     * Delete all records where associated groups are deleted
     *
     * @param bool $dryRun false
     * @return int Number of affected records
     */
    public function cleanupHardDeletedGroups($dryRun = false): int
    {
        return $this->cleanupHardDeletedAro('Groups', $dryRun);
    }

    /**
     * Delete all records where associated resources are deleted
     *
     * @param bool $dryRun false
     * @return int Number of affected records
     */
    public function cleanupHardDeletedResources(bool $dryRun = false): int
    {
        return $this->cleanupHardDeletedAco('Resources', $dryRun);
    }

    /**
     * Delete all records where associated resources are deleted
     *
     * @param bool $dryRun false
     * @return int Number of affected records
     */
    public function cleanupSoftDeletedResources(bool $dryRun = false): int
    {
        return $this->cleanupSoftDeletedAco('Resources', $dryRun);
    }

    /**
     * Delete duplicated permissions
     *
     * @param bool $dryRun false
     * @return int of affected records
     */
    public function cleanupDuplicatedPermissions(?bool $dryRun = false): int
    {
        $keys = ['aco', 'aco_foreign_key', 'aro', 'aro_foreign_key', 'type'];

        return $this->cleanupDuplicates($keys, $dryRun);
    }
}
