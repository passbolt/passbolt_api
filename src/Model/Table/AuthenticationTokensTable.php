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

use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * AuthenticationTokens Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\AuthenticationToken get($primaryKey, $options = [])
 * @method \App\Model\Entity\AuthenticationToken newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AuthenticationToken patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AuthenticationTokensTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('authentication_tokens');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
            ->allowEmpty('id', null, 'create');

        $validator
            ->uuid('token')
            ->requirePresence('token', 'create')
            ->allowEmptyString('token', __('Then authentication token should not be empty.'), false);

        $validator
            ->scalar('type')
            ->requirePresence('type', 'create')
            ->allowEmptyString('token', __('Then authentication type should not be empty.'), false)
            ->add('type', ['type' => [
                'rule' => [$this, 'isValidAuthenticationTokenType'],
                'message' => __('This authentication type is not supported.')
            ]]);

        $validator
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->allowEmptyString('user_id', __('Then authentication user id should not be empty.'), false);

        $validator
            ->boolean('active')
            ->requirePresence('active', true);

        return $validator;
    }

    /**
     * Check true if field is a valid gpg message.
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function isValidAuthenticationTokenType(string $check, array $context)
    {
        return(AuthenticationToken::TYPE_REGISTER === $check ||
            AuthenticationToken::TYPE_RECOVER === $check ||
            AuthenticationToken::TYPE_LOGIN === $check ||
            AuthenticationToken::TYPE_MFA === $check);
    }

    /**
     * Register validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationRegister(Validator $validator)
    {
        return self::validationDefault($validator);
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_not_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.')
        ]);

        return $rules;
    }

    /**
     * Build the authentication token
     *
     * @param string $userId uuid
     * @param string $type AuthenticationToken::TYPE_*
     * @throws ValidationException is the user is not a valid uuid
     * @throws ValidationException is the user is not found
     * @throws ValidationException is the user is deleted
     * @return \App\Model\Entity\AuthenticationToken $token
     */
    public function generate(string $userId, string $type)
    {
        $token = $this->newEntity(
            [
                'user_id' => $userId,
                'token' => UuidFactory::uuid(),
                'active' => true,
                'type' => $type
            ],
            ['accessibleFields' => [
                'user_id' => true,
                'token' => true,
                'active' => true,
                'type' => true,
            ]]
        );
        $errors = $token->getErrors();
        if (!empty($errors)) {
            $msg = __('It is not possible to create an authentication token for this user.');
            throw new ValidationException($msg);
        }
        if (!$this->save($token)) {
            $msg = __('It is not possible to create an authentication token for this user.');
            throw new ValidationException($msg);
        }

        return $token;
    }

    /**
     * Check if a token exist and is valid for a given user.
     *
     * A valid token :
     *  - belongs to the given user &&
     *  - is active &&
     *  - is not expired ;
     *
     * @param string $tokenId uuid of the token to check
     * @param string $userId uuid of the user
     * @param string $type token type
     * @param string|int $expiry the numeric value with space then time type.
     *    Example of valid types: 6 hours, 2 days, 1 minute.
     * @return bool true if it is valid
     */
    public function isValid(string $tokenId, string $userId, string $type = null, $expiry = null)
    {
        // Are ids valid uuid?
        if (!Validation::uuid($tokenId) || !Validation::uuid($userId)) {
            return false;
        }

        // Does token exist?
        $where = ['token' => $tokenId, 'user_id' => $userId, 'active' => true];
        if ($type) {
            $where['type'] = $type;
        }
        $token = $this->find('all')
            ->where($where)
            ->first();
        if (empty($token)) {
            return false;
        }

        // Is it expired
        if ($this->isExpired($token, $expiry)) {
            return false;
        }

        return true;
    }

    /**
     * Check if a token is expired
     *
     * @param EntityInterface $token uuid
     * @param string|int $expiry the numeric value with space then time type.
     *    Example of valid types: 6 hours, 2 days, 1 minute.
     * @return bool
     */
    public function isExpired(EntityInterface $token, $expiry = null)
    {
        if ($expiry === null) {
            $expiry = Configure::read('passbolt.auth.tokenExpiry');
        }
        $valid = $token->created->wasWithinLast($expiry);
        if (!$valid) {
            // update the token to inactive
            $token->active = false;
            $this->save($token);

            return true;
        }

        return false;
    }

    /**
     * Set a token as inactive
     *
     * @param string $tokenId uuid
     * @throws \InvalidArgumentException is the token is not a valid uuid
     * @return bool save result
     */
    public function setInactive(string $tokenId)
    {
        if (!Validation::uuid($tokenId)) {
            throw new \InvalidArgumentException(__('The token id should be a valid uuid.'));
        }
        $token = $this->find('all')
            ->where(['token' => $tokenId, 'active' => true ])
            ->first();

        if (empty($token)) {
            return false;
        }
        $token->active = false;

        if (!$this->save($token)) {
            return false;
        }

        return true;
    }

    /**
     * Get a token entity using the token id
     * (e.g. get using token->token, not token->id )
     *
     * @param string $tokenId uuid
     * @throws \InvalidArgumentException is the token is not a valid uuid
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getByToken(string $tokenId)
    {
        if (!Validation::uuid($tokenId)) {
            throw new \InvalidArgumentException(__('The token id should be a valid uuid.'));
        }
        $token = $this->find('all')
            ->where(['token' => $tokenId, 'active' => true ])
            ->first();

        return $token;
    }

    /**
     * Get a token entity using a user id
     *
     * @param string $userId uuid
     * @param string $type token type
     * @throws \InvalidArgumentException is the token is not a valid uuid
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getByUserId(string $userId, $type = null)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }
        $where = ['user_id' => $userId, 'active' => true ];
        if ($type !== null) {
            $where['type'] = $type;
        }
        $token = $this->find('all')
            ->where($where)
            ->order(['created' => 'DESC'])
            ->first();

        return $token;
    }
}
