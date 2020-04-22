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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Model\Table;

use App\Model\Rule\IsNotSoftDeletedRule;
use Cake\Datasource\EntityInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Traits\Folders\FoldersRelationsFindersTrait;

/**
 * FoldersRelations Model
 *
 * @method FoldersRelation get($primaryKey, $options = [])
 * @method FoldersRelation newEntity($data = null, array $options = [])
 * @method FoldersRelation[] newEntities(array $data, array $options = [])
 * @method FoldersRelation|false save(EntityInterface $entity, $options = [])
 * @method FoldersRelation saveOrFail(EntityInterface $entity, $options = [])
 * @method FoldersRelation patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method FoldersRelation[] patchEntities($entities, array $data, array $options = [])
 * @method FoldersRelation findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class FoldersRelationsTable extends Table
{
    use FoldersRelationsFindersTrait;

    /**
     * List of allowed item models on which a folder relation can be plugged.
     */
    const ALLOWED_FOREIGN_MODELS = [
        FoldersRelation::FOREIGN_MODEL_FOLDER,
        FoldersRelation::FOREIGN_MODEL_RESOURCE,
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

        $this->setTable('folders_relations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Resources', [
            'foreignKey' => 'foreign_id',
        ]);
        $this->belongsTo('Folders', [
            'foreignKey' => 'foreign_id',
        ]);
        $this->belongsTo('Users');
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->inList('foreign_model', self::ALLOWED_FOREIGN_MODELS, __(
                'The foreign model must be one of the following: {0}.',
                implode(', ', self::ALLOWED_FOREIGN_MODELS)
            ))
            ->requirePresence('foreign_model', 'create', __('The foreign model is required.'))
            ->notEmptyString('foreign_model', __('The foreign model cannot be empty'));

        $validator
            ->uuid('foreign_id')
            ->requirePresence('foreign_id', 'create', __('The foreign id is required.'))
            ->notEmptyString('foreign_id', __('The foreign id cannot be empty.'), false);

        $validator
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id', __('The user id cannot be empty.'), false);

        $validator
            ->uuid('folder_parent_id')
            ->allowEmptyString('folder_parent_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->addCreate(
            $rules->isUnique(
                ['foreign_id', 'user_id'],
                __('A folder relation already exists for the given foreign model and user.')
            ),
            'folder_relation_unique'
        );
        $rules->addCreate([$this, 'foreignIdExistsRule'], 'foreign_model_exists', [
            'errorField' => 'foreign_id',
            'message' => __('The foreign model does not exist.'),
        ]);
        $rules->addCreate($rules->existsIn(['user_id'], 'Users'), 'user_exists', [
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);
        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_not_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);

        return $rules;
    }

    /**
     * Checks that the foreign id exists
     *
     * @param FoldersRelation $foldersRelation The folder_relation to test
     * @param array $options The additional options for this rule
     * @return bool
     */
    public function foreignIdExistsRule(FoldersRelation $foldersRelation, array $options)
    {
        $rules = new RulesChecker($options);
        $exist = false;

        switch ($foldersRelation->foreign_model) {
            case FoldersRelation::FOREIGN_MODEL_RESOURCE:
                $rule = $rules->existsIn('foreign_id', 'Resources');
                $existIn = $rule($foldersRelation, $options);
                $rule = new IsNotSoftDeletedRule();
                $isNotSoftDeleted = $rule($foldersRelation, [
                    'table' => 'Resources',
                    'errorField' => 'foreign_id',
                ]);
                $exist = $existIn && $isNotSoftDeleted;
                break;
            case FoldersRelation::FOREIGN_MODEL_FOLDER:
                $rule = $rules->existsIn('foreign_id', 'Folders');
                $exist = $rule($foldersRelation, $options);
                break;
        }

        return $exist;
    }

    /**
     * Count the number of occurrences of a given relation.
     *
     * @param string $foreignId The relation child id
     * @param string $folderParentId The relation parent id
     * @return int
     */
    public function countRelationUsage(string $foreignId, string $folderParentId = FoldersRelation::ROOT)
    {
        $conditions = [
            'foreign_id' => $foreignId,
            'folder_parent_id' => $folderParentId,
        ];

        return $this->find()
            ->where($conditions)
            ->count();
    }

    /**
     * Get an item folder parent id in a user tree.
     *
     * @param string $userId The target user to look for
     * @param string $foreignId The item identifier
     * @return string
     */
    public function getItemFolderParentIdInUserTree(string $userId, string $foreignId)
    {
        $foldersParentIds = $this->getItemFoldersParentIdsInUsersTrees([$userId], $foreignId);

        return reset($foldersParentIds);
    }

    /**
     * Get an item folders parent ids in multiple users trees.
     *
     * @param array $usersIds The list of users to get the item folder parent id
     * @param string $foreignId The target entity id
     * @param bool $excludeRoot Exclude the root folder. Default false.
     * @return array
     */
    public function getItemFoldersParentIdsInUsersTrees(array $usersIds, string $foreignId, bool $excludeRoot = false)
    {
        $conditions = [
            'user_id IN' => $usersIds,
            'foreign_id' => $foreignId,
        ];

        if ($excludeRoot) {
            $conditions[] = 'folder_parent_id IS NOT NULL';
        }

        return $this->find()
            ->where($conditions)
            ->select('folder_parent_id')
            ->distinct('folder_parent_id')
            ->extract('folder_parent_id')
            ->toArray();
    }

    /**
     * Get the oldest usage of a relation.
     *
     * @param string $foreignId The target entity id
     * @param string $folderParentId The target entity parent id
     * @return string
     */
    public function getRelationOldestCreatedDate(string $foreignId, string $folderParentId = FoldersRelation::ROOT)
    {
        $conditions = [
            'foreign_id' => $foreignId,
            'folder_parent_id' => $folderParentId,
        ];

        return $this->find()
            ->where($conditions)
            ->order('created ')
            ->select('created')
            ->extract('created')
            ->first();
    }

    /**
     * Return a list of users ids having access to a list of items.
     *
     * @param array $foreignIds The list of items to check for.
     * @return array
     */
    public function getUsersIdsHavingAccessToMultipleItems(array $foreignIds)
    {
        if (empty($foreignIds)) {
            throw new InternalErrorException('The foreignIds parameter cannot be empty.');
        }

        // R = All the users that have access to all the given items.
        //
        // Details :
        // USERS_HAVING_ACCESS_TO_ITEM_1 = All the users having access to the first item of the list
        // USERS_HAVING_ACCESS_TO_ITEM_1 = All the users having access to the second item of the list
        // ....
        // USERS_HAVING_ACCESS_TO_ITEM_N = All the users having access to the N item of the list
        // R = USERS_HAVING_ACCESS_TO_ITEM_1 ⋂ USERS_HAVING_ACCESS_TO_ITEM_2 ⋂ ... ⋂ USERS_HAVING_ACCESS_TO_ITEM_N

        $query = $this->find();

        foreach ($foreignIds as $foreignId) {
            // R = R ⋂ USERS_HAVING_ACCESS_TO_ITEM_N
            $query->where([
                'user_id IN' => $this->findUsersIdsHavingAccessToItem($foreignId),
            ]);
        }

        return $query
            ->select('user_id')
            ->distinct('user_id')
            ->extract('user_id')
            ->toArray();
    }

    /**
     * Check if an item is personal.
     *
     * @param string $foreignId The item id
     * @return bool
     */
    public function isItemPersonal(string $foreignId = null)
    {
        if (is_null($foreignId)) {
            return false;
        }

        return $this->findByForeignId($foreignId)
                ->count() === 1;
    }

    /**
     * Check if an item is in a user tree.
     *
     * @param string $userId The target user
     * @param string $foreignId The target item id
     * @return bool
     */
    public function isItemInUserTree(string $userId, string $foreignId)
    {
        $conditions = ['foreign_id' => $foreignId, 'user_id' => $userId];

        return $this->exists($conditions);
    }

    /**
     * Check if an item is organized in a specified folder for a given user
     *
     * @param string $userId The target user
     * @param string $foreignId The target item id
     * @param string $folderParentId The target folder parent id
     * @return bool
     */
    public function isItemOrganizedInUserTree(string $userId, string $foreignId, string $folderParentId = FoldersRelation::ROOT)
    {
        $conditions = [
            'foreign_id' => $foreignId,
            'folder_parent_id' => $folderParentId,
            'user_id' => $userId,
        ];

        return $this->exists($conditions);
    }

    /**
     * Move an item from multiple locations to a target location.
     *
     * @param string $foreignId The target item
     * @param array $fromFoldersIds The list of folders ids to move from
     * @param string|null $folderParentId (optional) The destination folder location. Set it to null to null to move the
     * item to the root. Default null.
     * @return void
     */
    public function moveItemFrom(string $foreignId, array $fromFoldersIds, string $folderParentId = null)
    {
        $fields = [
            'folder_parent_id' => $folderParentId,
        ];
        $conditions = [
            'foreign_id' => $foreignId,
            'folder_parent_id IN' => $fromFoldersIds,
        ];
        $this->updateAll($fields, $conditions);
    }

    /**
     * Move an item for users from wherever they are to a target location
     * .
     * @param string $foreignId The target item
     * @param array $forUsersIds The list of users to move the item for
     * @param string $folderParentId The destination folder
     * @return void
     */
    public function moveItemFor(string $foreignId, array $forUsersIds, $folderParentId = null)
    {
        $fields = [
            'folder_parent_id' => $folderParentId,
        ];
        $conditions = [
            'foreign_id' => $foreignId,
            'user_id IN' => $forUsersIds,
        ];
        $this->updateAll($fields, $conditions);
    }
}
