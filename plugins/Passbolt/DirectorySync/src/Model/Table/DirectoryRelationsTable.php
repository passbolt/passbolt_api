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

use App\Model\Entity\GroupsUser;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use Passbolt\DirectorySync\Model\Entity\DirectoryRelation;
use Passbolt\DirectorySync\Utility\Alias;

/**
 * @property \App\Model\Table\GroupsUsersTable&\Cake\ORM\Association\HasOne $GroupUser
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $UserDirectoryEntry
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $GroupDirectoryEntry
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation newEmptyEntity()
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation newEntity(array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation[] newEntities(array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation get($primaryKey, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DirectoryRelationsTable extends Table
{
    use TableCleanupTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
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
            'foreignKey' => 'id',
        ]);

        $this->hasOne('UserDirectoryEntry', [
            'className' => 'DirectoryEntries',
            'bindingKey' => 'child_key',
            'foreignKey' => 'id',
        ]);

        $this->hasOne('GroupDirectoryEntry', [
            'className' => 'DirectoryEntries',
            'bindingKey' => 'parent_key',
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
            ->uuid('parent_key', __('The parent identifier should be a valid UUID.'))
            ->requirePresence('parent_key', __('A parent identifier is required.'));

        $validator
            ->uuid('child_key', __('The child identifier should be a valid UUID.'))
            ->requirePresence('child_key', __('The child identifier is required.'));

        return $validator;
    }

    /**
     * Cleanup orphan DirectoryRelations
     * An orphan directoryRelations is a directoryRelation that doesn't have a corresponding userGroup.
     * It means that userGroup has been deleted manually.
     *
     * @param array $entryIds entry ids
     * @return int number of deleted records
     */
    public function cleanupHardDeletedUserGroups(array $entryIds): int
    {
        $orphans = $this->find()
            ->select(['DirectoryRelations.id'])
            ->contain('GroupUser', function (Query $q) {
                return $q->where(function (QueryExpression $exp) {
                    return $exp->isNull('GroupUser.id');
                });
            });

        if (!empty($entryIds)) {
            $orphans->where(['DirectoryRelations.parent_key NOT IN' => $entryIds]);
        }

        $records = Hash::extract($orphans->toArray(), '{n}.id');
        if (count($records) > 0) {
            return $this->deleteAll(['id IN' => $records]);
        }

        return 0;
    }

    /**
     * Create group from user
     *
     * @param \App\Model\Entity\GroupsUser $groupUser groupUser
     * @return bool|\Cake\Datasource\EntityInterface|false|mixed|\Passbolt\DirectorySync\Model\Entity\DirectoryIgnore
     * @throws \Exception
     */
    public function createFromGroupUser(GroupsUser $groupUser)
    {
        $DirectoryEntries = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryEntries');

        $groupEntry = $DirectoryEntries
            ->find()
            ->select('id')
            ->where(['foreign_model' => Alias::MODEL_GROUPS, 'foreign_key' => $groupUser->group_id])
            ->first();

        $userEntry = $DirectoryEntries
            ->find()
            ->select('id')
            ->where(['foreign_model' => Alias::MODEL_USERS, 'foreign_key' => $groupUser->user_id])
            ->first();

        if (!$groupEntry || !$userEntry) {
            throw new \Exception('Relation creation error: Could not retrieve corresponding entries');
        }

        $relation = [
            'id' => $groupUser->id,
            'parent_key' => $groupEntry->get('id'),
            'child_key' => $userEntry->get('id'),
        ];

        return $this->createOrUpdate($relation);
    }

    /**
     * Create or update.
     *
     * @param array $data data
     * @return bool|\Cake\Datasource\EntityInterface|false|mixed|\Passbolt\DirectorySync\Model\Entity\DirectoryIgnore
     */
    public function createOrUpdate(array $data)
    {
        $r = $this->find()->select(['id'])->where(['id' => $data['id']])->first();
        if (!$r) {
            return $this->create($data);
        } else {
            unset($data['id']);
            $this->patchEntity($r, $data);

            return $this->save($r);
        }
    }

    /**
     * Return a directory relation matching the groupUser provided.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser groupUser
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function lookupByGroupUser(GroupsUser $groupUser)
    {
        return $this->find()
            ->where(['id' => $groupUser->id])
            ->first();
    }

    /**
     * Create a directory Relation
     *
     * @param array $data data
     * @return \Passbolt\DirectorySync\Model\Entity\DirectoryRelation
     */
    public function create(array $data): DirectoryRelation
    {
        $entity = $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'parent_key' => true,
                'child_key' => true,
            ],
        ]);
        $this->save($entity);

        return $entity;
    }
}
