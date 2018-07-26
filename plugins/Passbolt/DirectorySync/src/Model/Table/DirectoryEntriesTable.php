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
use App\Model\Entity\User;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Cleanup\UsersCleanupTrait;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\Entity;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;

/**
 * DirectoryEntries Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasOne $Users
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasOne $Groups
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasOne $DirectoryIgnore
 *
 * @method DirectoryEntry get($primaryKey, $options = [])
 * @method DirectoryEntry newEntity($data = null, array $options = [])
 * @method DirectoryEntry[] newEntities(array $data, array $options = [])
 * @method DirectoryEntry|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method DirectoryEntry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method DirectoryEntry[] patchEntities($entities, array $data, array $options = [])
 * @method DirectoryEntry findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DirectoryEntriesTable extends Table
{
    use TableCleanupTrait;
    use UsersCleanupTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('directory_entries');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('DirectoryIgnore', [
            'className' => 'DirectoryIgnore',
            'bindingKey' => 'id',
            'foreignKey' => 'id',
            // Delete ignore entries on directory entry delete
            'dependent' => true,
        ]);

        $this->hasOne('Users', [
            'dependent' => false,
            'className' => 'Users',
            'bindingKey' => 'foreign_key',
            'foreignKey' => 'id'
        ]);

        $this->hasOne('Groups', [
            'className' => 'Groups',
            'bindingKey' => 'foreign_key',
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
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('foreign_key')
            ->uuid('foreign_key')
            ->allowEmpty('foreign_key');

        $validator
            ->scalar('foreign_model')
            ->requirePresence('foreign_model', 'create');

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
        return $rules;
    }

    /**
     * Return a DirectoryEntry entity.
     * @param array $data
     *
     * @return \Passbolt\DirectorySync\Model\Entity\DirectoryEntry
     */
    public function buildEntity(array $data) {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'foreign_model' => true,
                'foreign_key' => true,
                'directory_name' => true,
                'directory_created' => true,
                'directory_modified' => true,
                'status' => true,
            ],
        ]);
    }

    /**
     * Update the status
     *
     * @param DirectoryEntry $entity
     * @param $status
     * @return bool
     */
    public function updateStatus(DirectoryEntry $entity, $status)
    {
        $entity = $this->get($entity->id);
        $this->patchEntity($entity, ['status' => $status], [
            'fieldList' => ['status'],
            'accessibleFields' => ['status' => true],
            'associated' => []
        ]);
        return $this->save($entity);
    }

    /**
     * Update the foreign key
     *
     * @param DirectoryEntry $entity
     * @param string $foreignKey uuid
     * @return bool
     */
    public function updateForeignKey(DirectoryEntry $entity, string $foreignKey = null)
    {
        $entity = $this->get($entity->id);
        $this->patchEntity($entity, ['foreign_key' => $foreignKey], [
            'fieldList' => ['foreign_key'],
            'accessibleFields' => ['foreign_key' => true],
            'associated' => []
        ]);
        return $this->save($entity);
    }

    /**
     * Create a new directory entry.
     *
     * @param $data
     * @return bool|DirectoryEntry
     */
    public function create($data)
    {
        // Check validation rules.
        $directoryEntry = $this->buildEntity($data);
        if (!empty($directoryEntry ->getErrors())) {
            throw new ValidationException(__('Could not validate directoryEntry.'), $directoryEntry, $this);
        }

        $de = $this->save($directoryEntry);

        // Check for validation errors. (associated models too).
        if (!empty($directoryEntry->getErrors())) {
            throw new ValidationException(__('Could not validate directoryEntry data.'), $directoryEntry, $this);
        }

        // Check for errors while saving.
        if (!$de) {
            throw new InternalErrorException(__('The directoryEntry could not be saved.'));
        }

        return $de;
    }

    /**
     * Update a directory entries status if it exist or create one
     *
     * @param array $data
     * @param string $status
     * @param string $model
     * @param Entity|null $entity
     * @param DirectoryEntry|null $entry
     * @return array|bool|DirectoryEntry
     */
    public function updateStatusOrCreate(array $data, string $status, string $model, Entity $entity = null, DirectoryEntry $entry = null)
    {
        if (isset($entry)) {
            if ($entry->status !== $status) {
                $entry = $this->updateStatus($entry, $status);
            }
            $entityForeignKey = (isset($entity) ? $entity->id : null);
            if ($entry->foreign_key !== $entityForeignKey) {
                $entry = $this->updateForeignKey($entry, $entityForeignKey);
            }
            return $entry;
        } else {
            $entry = $data;
            //unset($deData['user']);
            $entry['foreign_model'] = $model;
            if (isset($entity) && Validation::uuid($entity->id)) {
                $entry['foreign_key'] = $entity->id;
            } else {
                $entry['foreign_key'] = null;
            }
            $entry['status'] = $status;
            return $this->create($entry);
        }
    }

    /**
     * Find all the directory entries previously stored
     * that are not in the directory anymore
     *
     * @return string $model name
     * @return mixed
     */
    public function lookupEntriesForDeletion(string $model, array $directoryIds = null)
    {
        $query = $this->find()
            ->select()
            ->contain([$model]);
        if (!empty($directoryIds)) {
            $query = $query->where(['DirectoryEntries.id NOT IN' => $directoryIds]);
        }
        return $query->all();
    }
}
