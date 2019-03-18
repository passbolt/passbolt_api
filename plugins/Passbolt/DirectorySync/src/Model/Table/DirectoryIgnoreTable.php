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
use Cake\Validation\Validator;

/**
 * DirectoryIgnore Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasOne $Users
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasOne $Groups
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasOne $DirectoryEntries
 *
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore get($primaryKey, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore newEntity($data = null, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore[] newEntities(array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore[] patchEntities($entities, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DirectoryIgnoreTable extends Table
{
    use TableCleanupTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
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
            'foreignKey' => 'id'
        ]);

        $this->hasOne('Groups', [
            'className' => 'Groups',
            'bindingKey' => 'id',
            'foreignKey' => 'id'
        ]);

        $this->hasOne('DirectoryEntries', [
            'className' => 'DirectoryEntries',
            'bindingKey' => 'id',
            'foreignKey' => 'id'
        ]);
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
            ->scalar('id')
            ->uuid('id')
            ->requirePresence('id');

        $validator
            ->scalar('foreign_model')
            ->requirePresence('foreign_model')
            ->inList('foreign_model', [
                'Users', 'Groups', 'DirectoryEntries'
            ]);

        return $validator;
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
                'message' => __('The associated record could not be found')
            ]
        );

        return $rules;
    }

    /**
     * Create DirectoryIgnore
     * @param array $data data
     * @return \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore|bool
     */
    public function create(array $data)
    {
        $entity = $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'foreign_model' => true
            ]
        ]);
        $this->save($entity);

        return $entity;
    }

    /**
     * Create or fail
     * @param string $foreignModel foreign model
     * @param string $foreignKey foreign key
     * @return bool|\Passbolt\DirectorySync\Model\Entity\DirectoryIgnore
     */
    public function createOrFail(string $foreignModel, string $foreignKey)
    {
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
                'foreign_model' => $foreignModel
            ],
            [
                'accessibleFields' => [
                    'id' => true,
                    'foreign_model' => true
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
            throw new InternalErrorException(__('Could not ignore the record. Please try again later.'));
        }

        return $ignore;
    }

    /**
     * Delete all association records where associated users entities are deleted
     *
     * @param string $entityType Users or Groups
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupHardDeletedEntities(string $entityType, bool $dryRun = false)
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
     * @return number
     */
    public function cleanupHardDeletedDirectoryEntries(array $entryIds = null, bool $dryRun = false)
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
