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
use App\Model\Traits\Cleanup\UsersCleanupTrait;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
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

    const DN_MAX_LENGTH = 255;

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
            'foreignKey' => 'id',
        ]);

        $this->hasOne('Groups', [
            'dependent' => false,
            'className' => 'Groups',
            'bindingKey' => 'foreign_key',
            'foreignKey' => 'id',
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
            ->utf8('directory_name', __('The directory name is not a valid utf8 string.'))
            ->lengthBetween('directory_name', [0, self::DN_MAX_LENGTH], __('The directory_name length should be maximum {0} characters.', self::DN_MAX_LENGTH))
            ->requirePresence('directory_name', 'create', __('A directory_name is required.'))
            ->notEmpty('directory_name', __('The directory_name cannot be empty.'));

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
     * @param array $data data
     *
     * @return \Passbolt\DirectorySync\Model\Entity\DirectoryEntry
     */
    public function buildEntity(array $data)
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'foreign_model' => true,
                'foreign_key' => true,
                'directory_name' => true,
                'directory_created' => true,
                'directory_modified' => true,
            ],
        ]);
    }

    /**
     * Update the foreign key
     *
     * @param DirectoryEntry $entity entity
     * @param string $foreignKey uuid
     * @return DirectoryEntry|bool
     */
    public function updateForeignKey(DirectoryEntry $entity, string $foreignKey = null)
    {
        $entity->foreign_key = $foreignKey;

        return $this->save($entity);
    }

    /**
     * Update the foreign key
     *
     * @param DirectoryEntry $entity entity
     * @param string $directoryName DN or equivalent
     * @return DirectoryEntry|bool
     */
    public function updateDirectoryName(DirectoryEntry $entity, string $directoryName = null)
    {
        $entity = $this->get($entity->id);
        $this->patchEntity($entity, ['directory_name' => $directoryName], [
            'fieldList' => ['directory_name'],
            'accessibleFields' => ['directory_name' => true],
            'associated' => [],
        ]);
        $updatedEntity = $this->save($entity);
        if (!$updatedEntity) {
            return $entity;
        } else {
            return $updatedEntity;
        }
    }

    /**
     * Build entity from data
     * @param array $data data
     *
     * @return DirectoryEntry
     */
    public function buildEntityFromData(array $data)
    {
        if (strlen($data['directory_name']) > self::DN_MAX_LENGTH) {
            $data['directory_name'] = substr($data['directory_name'], 0, self::DN_MAX_LENGTH - 1);
        }

        $directoryEntry = $this->buildEntity($data);

        return $directoryEntry;
    }

    /**
     * Create a new directory entry.
     *
     * @param array $data data
     * @return bool|DirectoryEntry
     */
    public function create(array $data)
    {
        // Check validation rules.
        $directoryEntry = $this->buildEntityFromData($data);
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
     * Find a directory entries if it exist or create one
     * Will update the name if it changed in the directory (example: DN changed)
     *
     * @param array $data data
     * @param string $model model
     * @return array|bool|DirectoryEntry
     */
    public function updateOrCreate(array $data, string $model)
    {
        try {
            $entry = $this->get($data['id'], ['contain' => [$model]]);
            if (is_string($data['directory_name']) && $entry->directory_name !== $data['directory_name']) {
                if (strlen($data['directory_name']) > self::DN_MAX_LENGTH) {
                    $data['directory_name'] = substr($data['directory_name'], 0, self::DN_MAX_LENGTH - 1);
                }
                $entry->directory_name = $data['directory_name'];
                $this->save($entry);
            }

            return $entry;
        } catch (RecordNotFoundException $exception) {
            $data['foreign_model'] = $model;
            $data['foreign_key'] = null;

            return $this->create($data);
        }
    }

    /**
     * Find all the directory entries previously stored
     * that are not in the directory anymore
     *
     * @param string $model name
     * @param array $directoryIds directory ids list
     * @return mixed
     */
    public function lookupEntriesForDeletion(string $model, array $directoryIds = null)
    {
        $query = $this->find()
            ->select()
            ->where(['foreign_model' => $model])
            ->contain([$model]);
        if (!empty($directoryIds)) {
            $query = $query->where(['DirectoryEntries.id NOT IN' => $directoryIds]);
        }

        return $query->all();
    }
}
