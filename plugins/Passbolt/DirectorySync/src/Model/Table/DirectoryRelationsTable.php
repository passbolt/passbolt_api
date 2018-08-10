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
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;

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
    public function cleanupHardDeletedUserGroups(array $entryIds) {
        $conditions = [
            'GroupUser.id IS NULL'
        ];
        if (!empty($entryIds)) {
            $conditions['DirectoryRelations.parent_key NOT IN'] = $entryIds;
        }

        $orphans = $this->find()
            ->select(['DirectoryRelations.id', 'GroupUser.id'])
            ->contain('GroupUser')
            ->where($conditions)
            ->all();

        $records = Hash::extract($orphans->toArray(), '{n}.id');
        if (count($records) > 0) {
            return $this->deleteAll(['id IN' => $records]);
        }
    }

    public function createFromGroupUser($groupUser) {
        $DirectoryEntries = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryEntries');
        $groupEntry = $DirectoryEntries->find()->select('id')->where(['foreign_model' => DirectoryEntry::FOREIGN_MODEL_GROUPS, 'foreign_key' => $groupUser->group_id])->first();
        $userEntry = $DirectoryEntries->find()->select('id')->where(['foreign_model' => DirectoryEntry::FOREIGN_MODEL_USERS, 'foreign_key' => $groupUser->user_id])->first();

        if (!$groupEntry || !$userEntry) {
            throw new \Exception('Relation creation error: Could not retrieve corresponding entries');
        }

        $relation = [
            'id' => $groupUser->id,
            'parent_key' => $groupEntry->id,
            'child_key' => $userEntry->id,
        ];
        return $this->createOrUpdate($relation);
    }

    public function createOrUpdate(array $data) {
        $r = $this->find()->select(['id'])->where(['id' => $data['id']])->first();
        if (!$r) {
            return $this->create($data);
        }

        else {
            unset($data['id']);
            $this->patchEntity($r, $data);
            return $this->save($r);
        }
    }

    /**
     * Return a directory relation matching the groupUser provided.
     * @param $groupUser
     *
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function lookupByGroupUser($groupUser) {
        return $this->find()
            ->where(['id' => $groupUser->id])
            ->first();
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
