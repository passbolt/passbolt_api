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
use App\Utility\AuthToken\AuthTokenExpiry;
use App\Utility\UuidFactory;
use Cake\Datasource\EntityInterface;
use Cake\I18n\DateTime;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use InvalidArgumentException;

/**
 * AuthenticationTokens Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\AuthenticationToken get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\AuthenticationToken newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AuthenticationToken patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken firstOrFail()
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\AuthenticationToken newEmptyEntity()
 * @method \App\Model\Entity\AuthenticationToken saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\AuthenticationToken>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\AuthenticationToken>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\AuthenticationToken>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\AuthenticationToken>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 */
class AuthenticationTokensTable extends Table
{
    public const ALLOWED_TYPES = [
        AuthenticationToken::TYPE_REGISTER,
        AuthenticationToken::TYPE_RECOVER,
        AuthenticationToken::TYPE_LOGIN,
        AuthenticationToken::TYPE_MFA,
        AuthenticationToken::TYPE_MFA_SETUP,
        AuthenticationToken::TYPE_MFA_VERIFY,
        AuthenticationToken::TYPE_MOBILE_TRANSFER,
        AuthenticationToken::TYPE_REFRESH_TOKEN,
        AuthenticationToken::TYPE_VERIFY_TOKEN,
    ];

    /**
     * @var \App\Utility\AuthToken\AuthTokenExpiry
     */
    private AuthTokenExpiry $authTokenExpiry;

    /**
     * @return array self::ALLOWED_TYPES
     */
    public function getAllowedTypes(): array
    {
        return self::ALLOWED_TYPES;
    }

    /**
     * @return \App\Utility\AuthToken\AuthTokenExpiry
     */
    public function tokenExpiryFactory(): AuthTokenExpiry
    {
        return new AuthTokenExpiry();
    }

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->authTokenExpiry = $this->tokenExpiryFactory();

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
                    implode(', ', $this->getAllowedTypes())
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
    public function isValidAuthenticationTokenType(mixed $check, array $context): bool
    {
        return is_string($check) && (in_array($check, $this->getAllowedTypes()));
    }

    /**
     * Register validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationRegister(Validator $validator): Validator
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
     * @param string|null $token token value (optional)
     * @param array|null $data data value (optional)
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
        $msg = __('It is not possible to create an authentication token for this user.');
        if (!empty($errors)) {
            throw new ValidationException($msg);
        }
        if (!$this->save($token)) {
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
     * @return bool true if it is valid
     * @deprecated use AuthenticationTokenGetService
     */
    public function isValid(string $token, string $userId, ?string $type = null): bool
    {
        // Are ids valid uuid?
        if (!Validation::uuid($token) || !Validation::uuid($userId)) {
            return false;
        }

        // Does token exist?
        $where = ['token' => $token, 'user_id' => $userId, 'active' => true];
        if (isset($type)) {
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
        if ($token->isExpired()) {
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
     * @return bool
     */
    public function isExpired(AuthenticationToken $token): bool
    {
        return $token->isExpired();
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
            throw new InvalidArgumentException('The token should be a valid UUID.');
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
     * Get a token entity using a user id
     *
     * @param string $userId uuid
     * @param string|null $type token type
     * @throws \InvalidArgumentException is the token is not a valid uuid
     * @return \Cake\Datasource\EntityInterface|array|null
     */
    public function getByUserId(string $userId, ?string $type = null): array|EntityInterface|null
    {
        if (!Validation::uuid($userId)) {
            throw new InvalidArgumentException('The user identifier should be a valid UUID.');
        }
        $where = ['user_id' => $userId, 'active' => true ];
        if ($type !== null) {
            $where['type'] = $type;
        }
        $token = $this->find('all')
            ->where($where)
            ->orderBy(['created' => 'DESC'])
            ->first();

        return $token;
    }

    /**
     * getExpiryDate for a given type
     *
     * @param string $type type
     * @return \Cake\I18n\DateTime
     */
    private function getExpiryDate(string $type): DateTime
    {
        $expiryPeriod = $this->authTokenExpiry->getExpiryForTokenType($type);

        return new DateTime($expiryPeriod . ' ago');
    }

    /**
     * Finder to return authentication token that have expired
     * Requires a type to be provided
     *
     * @param \Cake\ORM\Query\SelectQuery $query query
     * @param ?string $tokenType type
     * @throws \InvalidArgumentException if type is missing in options
     * @throws \Cake\Http\Exception\InternalErrorException if token type does not have expiry in config
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findExpiredByType(SelectQuery $query, ?string $tokenType = null): SelectQuery
    {
        if (!isset($tokenType)) {
            $msg = 'AuthenticationTokensTable::findExpiredByType error, a token type is required';
            throw new InvalidArgumentException($msg);
        }

        return $query->where([
            'type' => $tokenType,
            'created <' => $this->getExpiryDate($tokenType),
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
        $this->updateQuery()
            ->set(['active' => false])
            ->where([
                'active' => true,
                'type' => $type,
                'created <' => $this->getExpiryDate($type),
            ])
            ->execute();
    }
}
