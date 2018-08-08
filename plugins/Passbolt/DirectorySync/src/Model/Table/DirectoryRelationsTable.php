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
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class DirectoryRelationsTable extends Table
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

        $this->setTable('directory_relations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasOne('GroupUser', [
            'dependent' => false,
            'className' => 'GroupsUsers',
            'bindingKey' => 'id',
            'foreignKey' => 'id'
        ]);

        $this->hasOne('UserDirectoryEntry', [
            'className' => 'DirectoryEntries',
            'bindingKey' => 'child_key',
            'foreignKey' => 'id'
        ]);

        $this->hasOne('GroupDirectoryEntry', [
            'className' => 'DirectoryEntries',
            'bindingKey' => 'parent_key',
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
            ->scalar('parent_key')
            ->uuid('parent_key')
            ->requirePresence('parent_key');

        $validator
            ->scalar('child_key')
            ->uuid('child_key')
            ->requirePresence('child_key');

        return $validator;
    }

    /**
     * Cleanup orphan DirectoryRelations
     * An orphan directoryRelations is a directoryRelation that doesn't have a corresponding userGroup.
     * It means that userGroup has been deleted manually.
     */
    public function cleanupHardDeletedUserGroups() {
        $orphans = $this->find()
            ->select(['DirectoryRelations.id', 'GroupUser.id'])
            ->contain('GroupUser')
            ->where(['GroupUser.id IS NULL'])
            ->all();

        $records = Hash::extract($orphans->toArray(), '{n}.id');
        if (count($records) > 0) {
            return $this->deleteAll(['id IN' => $records]);
        }
    }

    /**
     * @param $data
     * @return \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore|bool
     */
    public function create(array $data)
    {
        $entity = $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'parent_key' => true,
                'child_key' => true
            ]
        ]);
        $this->save($entity);

        return $entity;
    }
}
