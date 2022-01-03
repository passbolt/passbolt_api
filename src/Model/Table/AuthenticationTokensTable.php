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
 * @since         2.0.0
 */
namespace App\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\AuthenticationTokens\AuthenticationTokensFindersTrait;
use App\Utility\AuthToken\AuthTokenExpiry;
use App\Utility\UuidFactory;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * AuthenticationTokens Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\AuthenticationToken get($primaryKey, $options = [])
 * @method \App\Model\Entity\AuthenticationToken newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AuthenticationToken patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\AuthenticationToken newEmptyEntity()
 * @method \App\Model\Entity\AuthenticationToken saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AuthenticationToken[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AuthenticationToken[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AuthenticationToken[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AuthenticationToken[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AuthenticationTokensTable extends Table
{
    use AuthenticationTokensFindersTrait;

    public const ALLOWED_TYPES = [
        AuthenticationToken::TYPE_REGISTER,
        AuthenticationToken::TYPE_RECOVER,
        AuthenticationToken::TYPE_LOGIN,
        AuthenticationToken::TYPE_MFA,
        AuthenticationToken::TYPE_MOBILE_TRANSFER,
        AuthenticationToken::TYPE_REFRESH_TOKEN,
        AuthenticationToken::TYPE_VERIFY_TOKEN,
    ];

    /**
     * @var \App\Utility\AuthToken\AuthTokenExpiry
     */
    private $authTokenExpiry;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->authTokenExpiry = new AuthTokenExpiry();

        $this->setTable('authentication_tokens');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
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
            ->uuid('token', __('The token should be a valid UUID.'))
            ->requirePresence('token', 'create', __('A token is required.'))
            ->allowEmptyString('token', __('The token should not be empty.'), false);

        $validator
            ->add('type', ['type' => [
                'rule' => [$this, 'isValidAuthenticationTokenType'],
                'message' => __(
                    'The type should be one of the following: {0}.',
                    implode(', ', self::ALLOWED_TYPES)
                ),
            ]])
            ->requirePresence('type', 'create', __('A type is required.'))
            ->allowEmptyString('token', __('The type should not be empty.'), false);

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->allowEmptyString('user_id', __('The user identifier should not be empty.'), false);

        $validator
            ->boolean('active', __('The active status should be a valid boolean.'))
            ->requirePresence('active', true, __('An active status is required'));

        return $validator;
    }

    /**
     * Check true if field is a valid gpg message.
     *
     * @param mixed $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function isValidAuthenticationTokenType($check, array $context)
    {
        return is_string($check) && (in_array($check, self::ALLOWED_TYPES));
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
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_not_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);

        return $rules;
    }

    /**
     * Build the authentication token
     *
     * @param string $userId uuid
     * @param string $type AuthenticationToken::TYPE_*
     * @param ?string $token token value (optional)
     * @param ?array $data data value (optional)
     * @throws \App\Error\Exception\ValidationException is the user is not a valid uuid
     * @throws \App\Error\Exception\ValidationException is the user is not found
     * @throws \App\Error\Exception\ValidationException is the user is deleted
     * @return \App\Model\Entity\AuthenticationToken $token
     */
    public function generate(
        string $userId,
        string $type,
        ?string $token = null,
        ?array $data = []
    ): AuthenticationToken {
        $token = $this->newEntity(
            [
                'user_id' => $userId,
                'token' => $token ?? UuidFactory::uuid(),
                'active' => true,
                'type' => $type,
                'data' => empty($data) ? null : json_encode($data),
            ],
            ['accessibleFields' => [
                'user_id' => true,
                'token' => true,
                'active' => true,
                'type' => true,
                'data' => true,
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
     * @param string $token uuid of the token to check
     * @param string $userId uuid of the user
     * @param string|null $type token type
     * @param string|int $expiry the numeric value with space then time type.
     *    Example of valid types: 6 hours, 2 days, 1 minute.
     * @return bool true if it is valid
     */
    public function isValid(string $token, string $userId, ?string $type = null, $expiry = null): bool
    {
        // Are ids valid uuid?
        if (!Validation::uuid($token) || !Validation::uuid($userId)) {
            return false;
        }

        // Does token exist?
        $where = ['token' => $token, 'user_id' => $userId, 'active' => true];
        if ($type) {
            $where['type'] = $type;
        }

        /** @var \App\Model\Entity\AuthenticationToken|null $token */
        $token = $this->find()
            ->where($where)
            ->first();
        if (empty($token)) {
            return false;
        }

        // Is it expired
        if ($this->isExpired($token, $expiry)) {
            // update the token to inactive
            $token->set('active', false);
            $this->save($token);

            return false;
        }

        return true;
    }

    /**
     * Check if a token is expired
     *
     * @param \App\Model\Entity\AuthenticationToken $token uuid
     * @param string|int $expiry the numeric value with space then time type.
     *    Example of valid types: 6 hours, 2 days, 1 minute.
     * @return bool
     */
    public function isExpired(AuthenticationToken $token, $expiry = null): bool
    {
        return $token->isExpired($expiry);
    }

    /**
     * Set a token as inactive
     *
     * @param string $tokenValue uuid
     * @return bool save result
     * @throws \InvalidArgumentException is the token is not a valid uuid
     */
    public function setInactive(string $tokenValue): bool
    {
        if (!Validation::uuid($tokenValue)) {
            throw new \InvalidArgumentException('The token should be a valid UUID.');
        }
        $token = $this->find('all')
            ->where(['token' => $tokenValue, 'active' => true ])
            ->first();

        if (empty($token)) {
            return false;
        }
        $token->set('active', false);

        if (!$this->save($token)) {
            return false;
        }

        return true;
    }

    /**
     * Get a token entity using the token value
     * (e.g. get using token->token, not token->id )
     *
     * @param string $tokenValue uuid
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \InvalidArgumentException is the token is not a valid uuid
     * @throws \Cake\Datasource\Exception\RecordNotFoundException is the token is not found
     */
    public function getByToken(string $tokenValue): AuthenticationToken
    {
        if (!Validation::uuid($tokenValue)) {
            throw new \InvalidArgumentException(__('The token should be a valid UUID.'));
        }

        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = $this->find()->where(['token' => $tokenValue, 'active' => true ])->firstOrFail();

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
            throw new \InvalidArgumentException('The user identifier should be a valid UUID.');
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

    /**
     * getExpiryDate for a given type
     *
     * @param string $type type
     * @return \Cake\I18n\FrozenTime
     */
    private function getExpiryDate(string $type)
    {
        $expiryPeriod = $this->authTokenExpiry->getExpiryForTokenType($type);
        if (!isset($expiryPeriod) || empty($expiryPeriod)) {
            $msg = 'AuthenticationTokensTable::findExpiredByType no expiry in config for token type ' . $type;
            throw new InternalErrorException($msg);
        }

        return new FrozenTime($expiryPeriod . ' ago');
    }

    /**
     * Finder to return authentication token that have expired
     * Requires a type to be provided
     *
     * @param \Cake\ORM\Query $query query
     * @param array $options options
     * @throws \InvalidArgumentException if type is missing in options
     * @throws \Cake\Http\Exception\InternalErrorException if token type does not have expiry in config
     * @return \Cake\ORM\Query
     */
    public function findExpiredByType(Query $query, array $options): Query
    {
        if (count($options) === 0 || !isset($options['type'])) {
            $msg = 'AuthenticationTokensTable::findExpiredByType error, a token type is required';
            throw new \InvalidArgumentException($msg);
        }
        $type = $options['type'];

        return $query->where([
            'type' => $type,
            'created <' => $this->getExpiryDate($type),
        ]);
    }

    /**
     * Set all expired tokens to inactive
     *
     * @return void
     */
    public function setAllActiveExpiredTokenToInactive(): void
    {
        $types = self::ALLOWED_TYPES;
        foreach ($types as $type) {
            $this->setActiveExpiredTokenToInactive($type);
        }
    }

    /**
     * Set all expired tokens to inactive
     *
     * @param string $type type
     * @return void
     */
    public function setActiveExpiredTokenToInactive(string $type): void
    {
        $this->query()
            ->update()
            ->set(['active' => false])
            ->where([
                'active' => true,
                'type' => $type,
                'created <' => $this->getExpiryDate($type),
            ])
            ->execute();
    }
}
