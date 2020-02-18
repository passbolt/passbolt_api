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
     * Check if an item exists in a user tree.
     *
     * @param string $userId The target user
     * @param string $foreignId The target item id
     * @return bool
     */
    public function existsInUserTree(string $userId, string $foreignId)
    {
        $conditions = ['foreign_id' => $foreignId, 'user_id' => $userId];

        return $this->exists($conditions);
    }

    /**
     * Move an item from multiple locations to a target location.
     *
     * @param string $foreignId The target item
     * @param array $fromFoldersIds The list of folders ids to move from
     * @param string|null $folderParentId (optional) The destination folder location. Set it to null to null to move the
     * item to the root. Default null.
     */
    public function moveItemFrom(string $foreignId, array $fromFoldersIds, string $folderParentId = null)
    {
        $fields = [
            'folder_parent_id' => $folderParentId
        ];
        $conditions = [
            'foreign_id' => $foreignId,
            'folder_parent_id IN' => $fromFoldersIds
        ];
        $this->updateAll($fields, $conditions);
    }

    /**
     * Move an item for users from wherever they are to a target location
     * .
     * @param string $foreignId The target item
     * @param array $forUsersIds The list of users to move the item for
     * @param string $folderParentId The destination folder
     */
    public function moveItemFor(string $foreignId, array $forUsersIds, $folderParentId = null)
    {
        $fields = [
            'folder_parent_id' => $folderParentId
        ];
        $conditions = [
            'foreign_id' => $foreignId,
            'user_id IN' => $forUsersIds,
        ];
        $this->updateAll($fields, $conditions);
    }
}
