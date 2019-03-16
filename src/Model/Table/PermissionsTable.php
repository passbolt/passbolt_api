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

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Permission;
use App\Model\Rule\IsActiveRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Cleanup\ResourcesCleanupTrait;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Permissions\PermissionsFindersTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
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
    use PermissionsFindersTrait;
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
     * @throw CustomValidationException If a change try to modify a permission that is not in the list of permissions
     * @throw CustomValidationException If a change does not validate when calling patchEntity
     * @throw CustomValidationException If a change does not validate when calling newEntity
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
                    throw new CustomValidationException(__('Validation error.'), [$changeKey => $errors]);
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
                        throw new CustomValidationException(__('Validation error.'), [$changeKey => $errors]);
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
                    throw new CustomValidationException(__('Validation error.'), [$changeKey => $errors]);
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
