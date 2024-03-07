<?php
declare(strict_types=1);

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
 * @since         4.1.0
 */

namespace Passbolt\Rbacs\Model\Table;

use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Model\Rule\IsControlFunctionAllowedRule;

/**
 * Rbacs Model
 *
 * @method \Passbolt\Rbacs\Model\Entity\Rbac get($primaryKey, $options = [])
 * @method \Passbolt\Rbacs\Model\Entity\Rbac newEntity(array $data, array $options = [])
 * @method \Passbolt\Rbacs\Model\Entity\Rbac[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Rbacs\Model\Entity\Rbac|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Rbacs\Model\Entity\Rbac saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Rbacs\Model\Entity\Rbac patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Rbacs\Model\Entity\Rbac[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Rbacs\Model\Entity\Rbac findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Creator
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Modifier
 * @property \App\Model\Table\RolesTable&\Cake\ORM\Association\HasOne $Role
 * @property \Passbolt\Log\Model\Table\ActionsTable&\Cake\ORM\Association\HasOne $Action
 * @property \Passbolt\Rbacs\Model\Table\UiActionsTable&\Cake\ORM\Association\HasOne $UiAction
 * @method \Passbolt\Rbacs\Model\Entity\Rbac newEmptyEntity()
 * @method iterable<\Passbolt\Rbacs\Model\Entity\Rbac>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Rbacs\Model\Entity\Rbac>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Rbacs\Model\Entity\Rbac>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findById(string $id)
 */
class RbacsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('rbacs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior(TimestampBehavior::class);

        $this->hasOne('Creator', [
            'className' => 'Users',
            'bindingKey' => 'created_by',
            'foreignKey' => 'id',
        ]);

        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id',
        ]);

        $this->hasOne('Action', [
            'className' => 'Passbolt/Logs.Actions',
            'bindingKey' => 'foreign_id',
            'foreignKey' => 'id',
            'conditions' => [
                'Rbacs.foreign_model' => 'Action',
            ],
        ]);

        $this->hasOne('UiAction', [
            'className' => 'Passbolt/Rbacs.UiActions',
            'bindingKey' => 'foreign_id',
            'foreignKey' => 'id',
            'conditions' => [
                'Rbacs.foreign_model' => 'UiAction',
            ],
        ]);

        $this->hasOne('Role', [
            'className' => 'Roles',
            'bindingKey' => 'role_id',
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
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->uuid('role_id', __('The role identifier should be a valid UUID.'))
            ->requirePresence('role_id', 'create', __('A role identifier is required.'));

        $validator
            ->uuid('foreign_id', __('An action identifier should be a valid UUID.'))
            ->requirePresence('foreign_id', 'create', __('An action identifier is required.'));

        $validator
            ->ascii('foreign_model', __('The foreign model should be a valid ASCII string.'))
            ->inList('foreign_model', Rbac::ALLOWED_FOREIGN_MODELS, __(
                'The foreign model should be one of the following: {0}.',
                implode(', ', Rbac::ALLOWED_FOREIGN_MODELS)
            ))
            ->maxLength(
                'foreign_model',
                Rbac::MAX_FOREIGN_MODEL_LENGTH,
                __(
                    'The foreign model name used length should be maximum {0} characters.',
                    Rbac::MAX_FOREIGN_MODEL_LENGTH
                )
            )
            ->requirePresence('foreign_model', 'create', __('A foreign model is required.'))
            ->notEmptyString('foreign_model', __('The foreign model should not be empty.'));

        $validator
            ->ascii('control_function', __('The control function should be a valid UTF8 string.'))
            ->maxLength(
                'control_function',
                Rbac::MAX_CONTROL_FUNCTION_NAME_LENGTH,
                __(
                    'The control function name used length should be maximum {0} characters.',
                    Rbac::MAX_CONTROL_FUNCTION_NAME_LENGTH
                )
            )
            ->inList('control_function', Rbac::ALLOWED_CONTROL_FUNCTIONS, __('The control function is not supported.'))
            ->requirePresence('control_function', true, __('A control function is required.'))
            ->allowEmptyString('control_function', __('The control function should not be empty.'), false);

        $validator
            ->uuid('created_by', __('The identifier of the user who created the Rbac should be a valid UUID.'))
            ->allowEmptyString('created_by');

        $validator
            ->uuid('modified_by', __('The identifier of the user who modified the Rbac should be a valid UUID.'))
            ->allowEmptyString('modified_by', null, 'create')
            ->notEmptyString(
                'modified_by',
                __('The identifier of the user who modified the Rbac should not be empty.'),
                'update'
            );

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add(
            $rules->isUnique(
                ['id'],
                __('An RBAC entry already exists for the given id.')
            ),
            ['errorField' => 'id']
        );

        $rules->add(
            $rules->isUnique(
                ['role_id', 'foreign_id'],
                __('An entry already exists for the given role and action ids.')
            ),
            ['errorField' => 'role_id']
        );

        $rules->add(new IsControlFunctionAllowedRule(), 'isControlFunctionAllowed', [
            'errorField' => 'control_function',
            'message' => __('The control function is not allowed for this UI Action.'),
        ]);

        return $rules;
    }
}
