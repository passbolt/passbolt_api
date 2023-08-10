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
 * @since         3.11.0
 */

namespace Passbolt\Sso\Model\Table;

use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Validation\User\IsValidIpValidationRule;
use App\Model\Validation\User\IsValidUserAgentValidationRule;
use Cake\Database\Expression\QueryExpression;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Model\Validation\IsValidNonceValidationRule;
use Passbolt\Sso\Model\Validation\IsValidStateValidationRule;
use Passbolt\Sso\Model\Validation\IsValidTypeValidationRule;

/**
 * SsoStates Model
 *
 * @property \Passbolt\Sso\Model\Table\SsoSettingsTable&\Cake\ORM\Association\BelongsTo $SsoSettings
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \Passbolt\Sso\Model\Entity\SsoState newEmptyEntity()
 * @method \Passbolt\Sso\Model\Entity\SsoState newEntity(array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoState[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoState get($primaryKey, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoState findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoState patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoState[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoState|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoState saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoState[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoState[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoState[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoState[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SsoStatesTable extends Table
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

        $this->setTable('sso_states');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('SsoSettings', [
            'foreignKey' => 'sso_settings_id',
            'joinType' => 'INNER',
            'className' => 'Passbolt/Sso.SsoSettings',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className' => 'Users',
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
            ->ascii('nonce', __('The nonce should be a valid ASCII string.'))
            ->minLength(
                'nonce',
                SsoState::DEFAULT_LENGTH_NONCE,
                __('The nonce length should be minimum {0} characters.', SsoState::DEFAULT_LENGTH_NONCE)
            )
            ->maxLength(
                'nonce',
                SsoState::DEFAULT_LENGTH_NONCE,
                __('The nonce length should be maximum {0} characters.', SsoState::DEFAULT_LENGTH_NONCE)
            )
            ->requirePresence('nonce', 'create', __('A nonce is required.'))
            ->notEmptyString('nonce', __('The nonce should not be empty.'))
            ->add('nonce', 'isValidNonce', new IsValidNonceValidationRule());

        $validator
            ->ascii('type', __('The type should be a valid ASCII string.'))
            ->maxLength('type', 16, __('The type length should be maximum {0} characters.', 16))
            ->requirePresence('type', 'create', __('A type is required.'))
            ->notEmptyString('type', __('The type should not be empty.'))
            ->add('type', 'isValidType', new IsValidTypeValidationRule());

        $validator
            ->ascii('state', __('The state should be a valid ASCII string.'))
            ->minLength(
                'state',
                SsoState::DEFAULT_LENGTH_STATE,
                __('The state length should be minimum {0} characters.', SsoState::DEFAULT_LENGTH_STATE)
            )
            ->maxLength(
                'state',
                SsoState::DEFAULT_LENGTH_STATE,
                __('The state length should be maximum {0} characters.', SsoState::DEFAULT_LENGTH_STATE)
            )
            ->requirePresence('state', 'create', __('A state is required.'))
            ->notEmptyString('state', __('The state should not be empty.'))
            ->add('state', 'isValidState', new IsValidStateValidationRule());

        $validator
            ->uuid('sso_settings_id', __('The SSO settings identifier should be a valid UUID.'))
            ->notEmptyString('sso_settings_id', __('The SSO settings identifier should not be empty.'));

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->allowEmptyString('user_id', __('The user identifier should not be empty.'));

        $validator
            ->ascii('user_agent', __('The user agent should be a valid ASCII string.'))
            ->maxLength('user_agent', 255, __('The user agent length should be maximum {0} characters.', 255))
            ->requirePresence('user_agent', 'create', __('A user agent is required.'))
            ->notEmptyString('user_agent', __('The user agent should not be empty.'))
            ->add('user_agent', 'isValidUserAgent', new IsValidUserAgentValidationRule());

        $validator
            ->maxLength('ip', 45, __('The IP length should be maximum {0} characters.', 45))
            ->requirePresence('ip', 'create', __('An IP is required.'))
            ->notEmptyString('ip', __('The IP should not be empty.'))
            ->add('ip', 'isValidIp', new IsValidIpValidationRule());

        $validator->allowEmptyDateTime('deleted');

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
            $rules->isUnique(['nonce'], __('This nonce is already in use.')),
            ['errorField' => 'nonce']
        );
        $rules->add(
            $rules->isUnique(['state'], __('This state is already in use.')),
            ['errorField' => 'state']
        );

        $rules->add(
            $rules->existsIn(
                'sso_settings_id',
                'SsoSettings',
                __('The SSO setting identifier does not exist.')
            ),
            ['errorField' => 'sso_settings_id']
        );
        $rules->add(
            /**
             * Conditionally make `user_id` field mandatory.
             *
             * @param \Passbolt\Sso\Model\Entity\SsoState $entity
             */
            function ($entity, $options) use ($rules) {
                if ($entity->isUserIdMandatory()) {
                    $rule = $rules->existsIn(
                        'user_id',
                        'Users',
                        __('The user identifier does not exist.')
                    );

                    return $rule($entity, $options);
                }

                return true;
            },
            '_existsIn',
            ['errorField' => 'user_id']
        );

        $rules->addCreate(
            /** @param \Passbolt\Sso\Model\Entity\SsoState $entity */
            function ($entity, $options) {
                if ($entity->isUserIdMandatory()) {
                    $rule = new IsNotSoftDeletedRule();

                    return $rule($entity, $options);
                }

                return true;
            },
            'user_is_soft_deleted',
            [
                'table' => 'Users',
                'errorField' => 'user_id',
                'message' => __('The user must be active.'),
            ]
        );

        return $rules;
    }

    /**
     * Applies active filter on query.
     *
     * @param \Cake\ORM\Query $query Query.
     * @param array $options Options.
     * @return \Cake\ORM\Query
     */
    public function findActive(Query $query, array $options)
    {
        return $query->where(function (QueryExpression $exp) {
            return $exp->gt('deleted', FrozenTime::now());
        });
    }
}
