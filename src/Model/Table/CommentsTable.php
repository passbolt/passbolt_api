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
 * @since         2.0.0
 */

namespace App\Model\Table;

use App\Model\Rule\HasResourceAccessRule;
use App\Model\Rule\HasValidParentRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * Comments Model
 *
 * @property \App\Model\Table\ResourcesTable|\Cake\ORM\Association\BelongsTo $Resources
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasOne $Users
 *
 * @method \App\Model\Entity\Comment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Comment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Comment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Comment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Comment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Comment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Comment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CommentsTable extends Table
{

    /**
     * List of allowed foreign models on which Comments can be plugged.
     */
    const ALLOWED_FOREIGN_MODELS = [
        'Resource'
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

        $this->setTable('comments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Resources', [
            'foreignKey' => 'foreign_id'
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);

        $this->hasOne('Creator', [
            'className' => 'Users',
            'bindingKey' => 'created_by',
            'foreignKey' => 'id'
        ]);
        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
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
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->uuid('user_id')
            ->notEmpty('user_id')
            ->requirePresence('user_id');

        $validator
            ->uuid('parent_id')
            ->allowEmpty('parent_id');

        $validator
            ->inList('foreign_model', self::ALLOWED_FOREIGN_MODELS)
            ->requirePresence('foreign_model', 'create')
            ->notEmpty('foreign_model');

        $validator
            ->uuid('foreign_id')
            ->requirePresence('foreign_id', 'create')
            ->notEmpty('foreign_id');

        $validator
            ->scalar('content')
            ->requirePresence('content', 'create')
            ->notEmpty('content')
            ->utf8Extended('content')
            ->lengthBetween('content', [1, 255]);

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
        $rules->addCreate($rules->existsIn('foreign_id', 'Resources'), 'resource_exists');
        $rules->addCreate(new IsNotSoftDeletedRule(), 'resource_is_soft_deleted', [
            'table' => 'Resources',
            'errorField' => 'foreign_id',
            'message' => __('The resource is soft deleted.')
        ]);
        $rules->addCreate($rules->existsIn('user_id', 'Users'), 'user_exists');
        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user is soft deleted.')
        ]);
        $rules->addCreate(new HasResourceAccessRule(), 'has_resource_access', [
            'errorField' => 'foreign_id',
            'message' => __('Access denied.'),
            'userField' => 'user_id',
            'resourceField' => 'foreign_id',
        ]);
        $rules->addCreate(new HasValidParentRule(), 'has_valid_parent_id', [
            'table' => 'Comments',
            'errorField' => 'parent_id',
            'message' => __('The parent comment is invalid.'),
            'resourceField' => 'foreign_id',
        ]);

        return $rules;
    }

    /**
     * Build the query that fetches data for comment viewForeignComments
     *
     * @param string $userId The id of the user that tries to retrieve comments.
     * @param string $foreignModelName The foreign model name to find comments for (example: 'Resource')
     * @param string $foreignId The foreign model uuid to find comments for
     * @param array $options options
     * @throws \InvalidArgumentException if the groupId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findViewForeignComments($userId, $foreignModelName, $foreignId, array $options = [])
    {
        // Check model sanity.
        if (!in_array($foreignModelName, self::ALLOWED_FOREIGN_MODELS)) {
            throw new \InvalidArgumentException(__('The foreign model provided is not supported'));
        }

        // Check uuid format.
        if (!Validation::uuid($foreignId)) {
            throw new \InvalidArgumentException(__('The parameter groupId should be a valid uuid.'));
        }

        // Retrieve the resource.
        // This will break if the resource doesn't exist, if it is soft deleted, or if the user is not allowed to access it.
        $ResourcesTable = TableRegistry::get('Resources');
        $foreignModelLookup = $ResourcesTable->findView($userId, $foreignId)->first();
        if (empty($foreignModelLookup)) {
            throw new RecordNotFoundException(__('The foreign model does not exist.'));
        }

        $query = $this->find('threaded');
        $query->where([
            'Comments.foreign_model' => $foreignModelName,
            'Comments.foreign_id' => $foreignId
        ]);
        $query->order([
            'Comments.modified' => 'DESC'
        ]);

        // If contains creator.
        if (isset($options['contain']['creator'])) {
            $query->contain('Creator');
        }

        // If contains modifier.
        if (isset($options['contain']['modifier'])) {
            $query->contain('Modifier');
        }

        return $query;
    }
}
