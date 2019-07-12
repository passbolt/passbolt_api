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
 * @since         2.0.0
 */

namespace App\Model\Table;

use App\Model\Rule\HasResourceAccessRule;
use App\Model\Rule\HasValidParentRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Table\AvatarsTable;
use App\Model\Traits\Cleanup\ResourcesCleanupTrait;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Cleanup\UsersCleanupTrait;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
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

    use ResourcesCleanupTrait;
    use TableCleanupTrait;
    use UsersCleanupTrait;

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
            'foreignKey' => 'foreign_key'
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->uuid('user_id', __('user_id should be a uuid'))
            ->allowEmptyString('user_id', __('The user_id should not be empty'), false)
            ->requirePresence('user_id', 'create', __('A user_id is required'));

        $validator
            ->uuid('parent_id', __('parent_ud should be a uuid'))
            ->allowEmptyString('parent_id', null, true);

        $validator
            ->ascii('foreign_model')
            ->inList('foreign_model', self::ALLOWED_FOREIGN_MODELS, __('The foreign_model provided is not supported'))
            ->requirePresence('foreign_model', 'create', __('A foreign_model is required'))
            ->allowEmptyString('foreign_model', __('The foreign_model should not be empty'), false);

        $validator
            ->uuid('foreign_key', __('foreign_key should be a uuid'))
            ->requirePresence('foreign_key', 'create', __('A foreign_key is required'))
            ->allowEmptyString('foreign_key', __('The foreign_key should not be empty'), false);

        $validator
            ->scalar('content')
            ->requirePresence('content', __('A content is required'))
            ->allowEmptyString('content', __('The content should not be empty'), false)
            ->utf8Extended('content', __('The content is not a valid utf8 string (emoticons excluded)'))
            ->lengthBetween('content', [1, 255], __('The content length should be between {0} and {1} characters.', 1, 255));

        $validator
            ->uuid('created_by', __('created_by should be a uuid'))
            ->requirePresence('created_by', 'create', __('A created_by is required'))
            ->allowEmptyString('created_by', __('The created_by should not be empty'), false);

        $validator
            ->uuid('modified_by', __('modified_by should be a uuid'))
            ->requirePresence('modified_by', true, __('A modified_by is required'))
            ->allowEmptyString('modified_by', __('A modified_by is required'), false);

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
        $rules->addCreate($rules->existsIn('foreign_key', 'Resources'), 'resource_exists');
        $rules->addCreate(new IsNotSoftDeletedRule(), 'resource_is_soft_deleted', [
            'table' => 'Resources',
            'errorField' => 'foreign_key',
            'message' => __('The resource is soft deleted.')
        ]);
        $rules->addCreate($rules->existsIn('user_id', 'Users'), 'user_exists');
        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user is soft deleted.')
        ]);
        $rules->addCreate(new HasResourceAccessRule(), 'has_resource_access', [
            'errorField' => 'foreign_key',
            'message' => __('Access denied.'),
            'userField' => 'user_id',
            'resourceField' => 'foreign_key',
        ]);
        $rules->addCreate(new HasValidParentRule(), 'has_valid_parent_id', [
            'table' => 'Comments',
            'errorField' => 'parent_id',
            'message' => __('The parent comment is invalid.'),
            'resourceField' => 'foreign_key',
        ]);
        $rules->addCreate($rules->existsIn('created_by', 'Users'), 'creator_exists');
        $rules->addCreate($rules->existsIn('modified_by', 'Users'), 'modifier_exists');

        // Update rules.
        $rules->addUpdate($rules->existsIn('modified_by', 'Users'), 'modifier_exists');
        $rules->addUpdate([$this, 'ruleIsOwner'], 'is_owner', [
            'errorField' => 'user_id',
            'message' => __('The user cannot update this comment.')
        ]);

        // Delete rules.
        $rules->addDelete([$this, 'ruleIsOwner'], 'is_owner', [
            'errorField' => 'user_id',
            'message' => __('The user cannot delete this comment.')
        ]);

        return $rules;
    }

    /**
     * Build the query that fetches data for comment viewForeignComments
     *
     * @param string $userId The id of the user that tries to retrieve comments.
     * @param string $foreignModelName The foreign model name to find comments for (example: 'Resource')
     * @param string $foreignKey The foreign model uuid to find comments for
     * @param array $options options
     * @throws \InvalidArgumentException if the groupId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findViewForeignComments(string $userId, string $foreignModelName, string $foreignKey, array $options = [])
    {
        // Check model sanity.
        if (!in_array($foreignModelName, self::ALLOWED_FOREIGN_MODELS)) {
            throw new \InvalidArgumentException(__('The foreign model provided is not supported'));
        }

        // Check uuid format.
        if (!Validation::uuid($foreignKey)) {
            throw new \InvalidArgumentException(__('The parameter groupId should be a valid uuid.'));
        }

        // Retrieve the resource.
        // This will break if the resource doesn't exist, if it is soft deleted, or if the user is not allowed to access it.
        $ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $foreignModelLookup = $ResourcesTable->findView($userId, $foreignKey)->first();
        if (empty($foreignModelLookup)) {
            throw new RecordNotFoundException(__('The foreign model does not exist.'));
        }

        $query = $this->find('threaded');
        $query->where([
            'Comments.foreign_model' => $foreignModelName,
            'Comments.foreign_key' => $foreignKey
        ]);
        $query->order([
            'Comments.modified' => 'DESC'
        ]);

        // If contains creator.
        if (isset($options['contain']['creator'])) {
            $query->contain([
                'Creator' => ['Profiles' => AvatarsTable::addContainAvatar()]
            ]);
        }

        // If contains modifier.
        if (isset($options['contain']['modifier'])) {
            $query->contain([
                'Modifier' => ['Profiles' => AvatarsTable::addContainAvatar()]
            ]);
        }

        return $query;
    }

    /**
     * Validate that the comment belongs to the associated user.
     *
     * @param \App\Model\Entity\Comment $entity The entity that will be deleted.
     * @param array $options options
     *   Comments.user_id should be provided so that the check can be done.
     * @return bool
     */
    public function ruleIsOwner(\App\Model\Entity\Comment $entity, array $options = [])
    {
        if (!isset($options['Comments.user_id'])) {
            throw new BadRequestException(__('The parameter Comments.user_id should be provided'));
        }
        if ($options['Comments.user_id'] != $entity->user_id) {
            return false;
        }

        return true;
    }
}
