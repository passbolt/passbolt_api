<?php
declare(strict_types=1);

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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * DirectoryIgnore Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Users
 * @property \App\Model\Table\GroupsTable&\Cake\ORM\Association\HasOne $Groups
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $DirectoryEntries
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore get($primaryKey, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore newEntity(array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore[] newEntities(array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore newEmptyEntity()
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DirectoryIgnoreTable extends Table
{
    use TableCleanupTrait;

    /**
     * @var string[]
     */
    public static $SUPPORTED_FOREIGN_MODEL = [
        'Users',
        'Groups',
        'DirectoryEntries',
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

        $this->setTable('directory_ignore');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasOne('Users', [
            'dependent' => false,
            'className' => 'Users',
            'bindingKey' => 'id',
            'foreignKey' => 'id',
        ]);

        $this->hasOne('Groups', [
            'className' => 'Groups',
            'bindingKey' => 'id',
            'foreignKey' => 'id',
        ]);

        $this->hasOne('DirectoryEntries', [
            'className' => 'DirectoryEntries',
            'bindingKey' => 'id',
            'foreignKey' => 'id',
        ]);
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
            ->requirePresence('id', __('An identifier is required.'));

        $validator
            ->inList(
                'foreign_model',
                self::$SUPPORTED_FOREIGN_MODEL,
                __(
                    'The object type should be one of the following: {0}.',
                    implode(', ', self::$SUPPORTED_FOREIGN_MODEL)
                )
            )
            ->requirePresence('foreign_model', __('An object type is required.'));

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): \Cake\ORM\RulesChecker
    {
        $rules->add(
            function ($entity, $options) {
                if ($entity->foreign_model !== 'DirectoryEntries') {
                    $model = TableRegistry::getTableLocator()->get($entity->foreign_model);
                    try {
                        $model->get($entity->id);
                    } catch (RecordNotFoundException $exception) {
                        return false;
                    }
                }

                return true;
            },
            'AssociatedRecordExists',
            [
                'errorField' => 'id',
                'message' => __('The associated record could not be found.'),
            ]
        );

        return $rules;
    }

    /**
     * Create DirectoryIgnore
     *
     * @param array $data data
     * @return \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore|bool
     */
    public function create(array $data)
    {
        $entity = $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'foreign_model' => true,
            ],
        ]);
        $this->save($entity);

        return $entity;
    }

    /**
     * Create or fail
     *
     * @param string $foreignModel foreign model
     * @param string $foreignKey foreign key
     * @return bool|\Passbolt\DirectorySync\Model\Entity\DirectoryIgnore
     * @throws \Cake\Http\Exception\BadRequestException if the $foreignKey is not a valid UUID
     */
    public function createOrFail(string $foreignModel, string $foreignKey)
    {
        if (!Validation::uuid($foreignKey)) {
            throw new BadRequestException(__('The identifier should be a valid UUID.'));
        }
        try {
            $entry = $this->get($foreignKey);
        } catch (RecordNotFoundException $exception) {
        }
        if (isset($entry)) {
            throw new BadRequestException(__('This record is already marked as to be ignored.'));
        }

        $ignore = $this->newEntity(
            [
                'id' => $foreignKey,
                'foreign_model' => $foreignModel,
            ],
            [
                'accessibleFields' => [
                    'id' => true,
                    'foreign_model' => true,
                ]]
        );
        if ($ignore->getErrors()) {
            throw new ValidationException(__('This is not a valid record to ignore.'), $ignore, $this);
        }
        $this->checkRules($ignore);
        if ($ignore->getErrors()) {
            throw new ValidationException(__('This is not a valid record to ignore.'), $ignore, $this);
        }
        if (!$this->save($ignore, ['checkrules' => false])) {
            throw new InternalErrorException('Could not ignore the record, please try again later.');
        }

        return $ignore;
    }

    /**
     * Delete all association records where associated users entities are deleted
     *
     * @param string $entityType Users or Groups
     * @param bool $dryRun false
     * @return int number of affected records
     */
    public function cleanupHardDeletedEntities(string $entityType, ?bool $dryRun = false): int
    {
        $query = $this->query()
            ->select(['id'])
            ->leftJoinWith($entityType)
            ->where(function ($exp, $q) use ($entityType) {
                return $exp
                    ->isNull($entityType . '.id')
                    ->eq('DirectoryIgnore.foreign_model', $entityType);
            });

        return $this->cleanupHardDeleted($entityType, $dryRun, $query);
    }

    /**
     * Cleanup hard deleted entries
     *
     * @param array|null $entryIds entry ids
     * @param bool $dryRun dry run
     * @return int number
     */
    public function cleanupHardDeletedDirectoryEntries(?array $entryIds = null, ?bool $dryRun = false): int
    {
        $query = $this->query()
            ->select(['id']);

        $query = $query
            ->leftJoinWith('DirectoryEntries')
            ->where(function ($exp, $q) {
                return $exp
                    ->isNull('DirectoryEntries.id')
                    ->eq('DirectoryIgnore.foreign_model', 'DirectoryEntries');
            });

        if (isset($entryIds) && !empty($entryIds)) {
            $query = $query->where(['DirectoryIgnore.id NOT IN' => $entryIds]);
        }

        return $this->cleanupHardDeleted('DirectoryEntries', $dryRun, $query);
    }
}
