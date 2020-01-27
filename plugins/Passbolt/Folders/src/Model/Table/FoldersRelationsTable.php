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
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FoldersRelations Model
 *
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation get($primaryKey, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation newEntity($data = null, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation[] patchEntities($entities, array $data, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FoldersRelationsTable extends Table
{
    /**
     * List of allowed item models on which a folder relation can be plugged.
     */
    const ALLOWED_FOREIGN_MODELS = [
        'Folder',
        'Resource',
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
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
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
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
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
     * @param \Cake\Datasource\EntityInterface $entity The entity to test
     * @param array $options The additional options for this rule
     * @return bool
     */
    public function foreignIdExistsRule(\Cake\Datasource\EntityInterface $entity, array $options)
    {
        $rules = new RulesChecker($options);
        $exist = false;

        switch ($entity->foreign_model) {
            case 'Resource':
                $rule = $rules->existsIn('foreign_id', 'Resources');
                $existIn = $rule($entity, $options);
                $rule = new IsNotSoftDeletedRule();
                $isNotSoftDeleted = $rule($entity, [
                    'table' => 'Resources',
                    'errorField' => 'foreign_id',
                ]);
                $exist = $existIn && $isNotSoftDeleted;
                break;
            case 'Folder':
                $rule = $rules->existsIn('foreign_id', 'Folders');
                $exist = $rule($entity, $options);
                break;
        }

        return $exist;
    }
}
