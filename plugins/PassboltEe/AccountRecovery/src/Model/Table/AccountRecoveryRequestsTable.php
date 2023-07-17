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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Rule\IsNotUserKeyFingerprintRule;
use App\Model\Rule\User\IsActiveUserRule;
use App\Model\Table\AvatarsTable;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Cleanup\UsersCleanupTrait;
use App\Model\Validation\ArmoredKey\IsParsableArmoredKeyValidationRule;
use App\Model\Validation\Fingerprint\IsMatchingKeyFingerprintValidationRule;
use App\Model\Validation\Fingerprint\IsValidFingerprintValidationRule;
use App\Utility\UserAccessControl;
use Cake\Chronos\Chronos;
use Cake\Collection\CollectionInterface;
use Cake\Core\Exception\CakeException;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Phinx\Db\Adapter\MysqlAdapter;

/**
 * AccountRecoveryRequests Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\AuthenticationTokensTable&\Cake\ORM\Association\BelongsTo $AuthenticationTokens
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest newEmptyEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest newEntity(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest[] newEntities(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest get($primaryKey, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccountRecoveryRequestsTable extends Table
{
    use TableCleanupTrait;
    use UsersCleanupTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('account_recovery_requests');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users');

        $this->belongsTo('AuthenticationTokens', [
            'foreignKey' => 'authentication_token_id',
            'joinType' => 'INNER',
            'className' => 'AuthenticationTokens',
        ]);

        $this->hasMany('AccountRecoveryResponses', [
            'className' => 'Passbolt/AccountRecovery.AccountRecoveryResponses',
            'foreignKey' => 'account_recovery_request_id',
        ]);

        $this->hasOne('AccountRecoveryPrivateKeys', [
            'className' => 'Passbolt/AccountRecovery.AccountRecoveryPrivateKeys',
            'bindingKey' => 'user_id',
            'foreignKey' => 'user_id',
        ]);

        $this->hasOne('Creator', [
            'className' => 'Users',
            'bindingKey' => 'created_by',
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
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create')
            ->notEmptyString('id', __('The identifier should not be empty.'), 'update');

        $validator
            ->scalar('status', __('The status should not be a valid string.'))
            ->inList(
                'status',
                AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_STATUSES,
                __('This status is not supported.')
            )
            ->maxLength('status', 36, __('The status length should be maximum {0} characters.', 36))
            ->requirePresence('status', 'create', __('A status is required.'))
            ->notEmptyString('status', __('The status should not be empty.'));

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->notEmptyString('user_id', __('The user identifier should not be empty.'));

        $validator
            ->ascii('armored_key', __('The armored key should be a valid ASCII string.'))
            ->requirePresence('armored_key', 'create', __('An armored key is required.'))
            ->notEmptyString('armored_key', __('The armored key should not be empty.'))
            ->maxLength('armored_key', MysqlAdapter::TEXT_MEDIUM, __('The armored key is too big.'))
            ->add('armored_key', 'invalidArmoredKey', new IsParsableArmoredKeyValidationRule());

        $validator
            ->ascii('fingerprint', __('The fingerprint should be a valid ASCII string.'))
            ->requirePresence('fingerprint', 'create', __('A fingerprint is required'))
            ->notEmptyString('fingerprint', __('The fingerprint should not be empty'))
            ->add('fingerprint', 'invalidFingerprint', new IsValidFingerprintValidationRule())
            ->add('fingerprint', 'isMatchingKeyFingerprintRule', new IsMatchingKeyFingerprintValidationRule());

        $validator
            ->uuid('created_by', __('The identifier of the user who created the request should be a valid UUID.'))
            ->requirePresence(
                'created_by',
                'create',
                __('The identifier of the user who created the request is required.')
            )
            ->notEmptyString(
                'created_by',
                __('The identifier of the user who created the request should not be empty.'),
                false
            );

        $validator
            ->uuid('modified_by', __('The identifier of the user who modified the request should be a valid UUID.'))
            ->requirePresence(
                'modified_by',
                'create',
                __('The identifier of the user who modified the request is required.')
            )
            ->notEmptyString(
                'modified_by',
                __('The identifier of the user who modified the request should not be empty.'),
                false
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
        $rules->add($rules->isUnique(['id']), ['errorField' => 'id']);
        $rules->add(new IsActiveUserRule(), [
            'errorField' => 'user_id',
            'message' => __('The user does not exist or is not active or has been deleted.'),
        ]);
        $rules->add(new IsNotUserKeyFingerprintRule(), 'isNotUserKeyFingerprintRule', [
            'errorField' => 'fingerprint',
            'message' => __('You cannot reuse the user keys.'),
        ]);

        return $rules;
    }

    /**
     * @param \Cake\Event\EventInterface $event Event
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $request Request
     * @param \ArrayObject $options Options
     * @return void
     */
    public function afterSave(EventInterface $event, AccountRecoveryRequest $request, \ArrayObject $options)
    {
        if (isset($options['uac'])) {
            /** @var \App\Utility\UserAccessControl $uac */
            $uac = $options['uac'];
            $modifiedBy = $uac->getId();
        } else {
            $modifiedBy = $request->user_id;
        }

        // Set all other pending requests to rejected
        $this->updateAll([
            'status' => AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_REJECTED,
            'modified' => FrozenTime::now(),
            'modified_by' => $modifiedBy,
        ], [
            'id !=' => $request->id,
            'user_id' => $request->user_id,
            'status' => AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_PENDING,
        ]);
    }

    /**
     * @param \App\Utility\UserAccessControl $userAccessControl user
     * @return void
     */
    public function rejectAllNonCompleted(UserAccessControl $userAccessControl): void
    {
        $this->query()
            ->update()
            ->set([
                'status' => AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_REJECTED,
                'modified_by' => $userAccessControl->getId(),
                'modified' => Chronos::now(),
            ])
            ->where([
                'status IN' => [
                    AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED,
                    AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_PENDING,
                ],
            ])
            ->execute();
    }

    /**
     * Build the query that fetches all requests
     *
     * @param array $options options
     * @throws \Cake\Core\Exception\CakeException if no role is specified
     * @return \Cake\ORM\Query
     */
    public function findIndex(array $options): Query
    {
        $query = $this->query();

        $fields = [
            'id',
            'status',
            'user_id',
            'fingerprint',
            'created_by',
            'modified_by',
            'created',
            'modified',
            // 'armored_key', // not needed everywhere
            // 'authentication_token_id', // not wanted
        ];

        // Contain options
        $contain = $options['contain'] ?? [];

        // Contain armored key
        $containArmored = $contain['armored_key'] ?? false;
        if ($containArmored) {
            $fields[] = 'armored_key';
        }

        // Contain passwords
        $containPasswords = $contain['account_recovery_private_key_passwords'] ?? false;
        $associations = [];
        if ($containPasswords) {
            $associations['AccountRecoveryPrivateKeys'] = function (Query $q) {
                return $q->select([
                    'id',
                    'user_id',
                    'created',
                    'modified',
                    'created_by',
                    'modified_by',
                    // data // not wanted - for the end user only
                ]);
            };
            $associations['AccountRecoveryPrivateKeys.AccountRecoveryPrivateKeyPasswords'] = function (Query $q) {
                return $q->select([
                    'id',
                    'private_key_id',
                    'recipient_foreign_model',
                    'recipient_fingerprint',
                    'data',
                    'created',
                    'modified',
                    'created_by',
                    'modified_by',
                ]);
            };
        }

        // Contain responses
        $containResponses = $contain['account_recovery_request_responses'] ?? false;
        if ($containResponses) {
            $associations['AccountRecoveryResponses'] = function (Query $q) {
                return $q->select([
                    'id',
                    'account_recovery_request_id',
                    'created',
                    'modified',
                    'created_by',
                    'modified_by',
                ]);
            };
        }

        // Contain creator
        $containCreator = $contain['creator'] ?? false;
        if ($containCreator) {
            $associations['Creator'] = function (Query $q) {
                return $q->select([
                    'id',
                    'username',
                    'active',
                    'deleted',
                    'role_id',
                    'created',
                    'modified',
                ]);
            };
            $associations['Creator.Profiles'] = function (Query $q) {
                return $q->select([
                    'id',
                    'user_id',
                    'first_name',
                    'last_name',
                    'created',
                    'modified',
                ]);
            };
            $associations['Creator.Profiles.Avatars'] = function (Query $q) {
                // Formatter for empty avatars.
                return $q->select(['id', 'profile_id', 'created', 'modified'])
                    ->formatResults(function (CollectionInterface $avatars) {
                        return AvatarsTable::formatResults($avatars);
                    });
            };
        }

        // Build the query
        $query->select($fields);
        if (count($associations)) {
            $query->contain($associations);
        }

        // Filter on users
        if (isset($options['filter']['has-users'])) {
            $query->where(['user_id IN' => $options['filter']['has-users']]);
        }

        return $query;
    }

    /**
     * Build the query that fetches one request
     *
     * @param array $options options
     * @throws \Cake\Core\Exception\CakeException if no id is specified
     * @return \Cake\ORM\Query
     */
    public function findView(array $options): Query
    {
        // Options must contain an id
        if (!isset($options['id'])) {
            throw new CakeException('An ID must be provided.');
        }

        // Same rule than index apply
        // with a specific id requested
        $query = $this->findIndex($options);
        $query->where([$this->aliasField('id') => $options['id']]);

        return $query;
    }

    /**
     * Build and validate an entity from user provided data
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $tokenId token id
     * @param array $data user provided data
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest entity
     */
    public function buildAndValidateEntity(UserAccessControl $uac, string $tokenId, array $data): AccountRecoveryRequest
    {
        $requestEntity = $this->newEntity([
            'authentication_token_id' => $tokenId,
            'user_id' => $uac->getId(),
            'armored_key' => $data['armored_key'] ?? '',
            'fingerprint' => $data['fingerprint'] ?? '',
            'status' => AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_PENDING,
            'created_by' => $uac->getId(),
            'modified_by' => $uac->getId(),
        ], [
            'accessibleFields' => [
                'authentication_token_id' => true,
                'user_id' => true,
                'armored_key' => true,
                'fingerprint' => true,
                'status' => true,
                'created_by' => true,
                'modified_by' => true,
            ],
        ]);

        if ($requestEntity->getErrors()) {
            throw new ValidationException(__('The request is invalid.'), $requestEntity, $this);
        }

        return $requestEntity;
    }

    /**
     * Patch a request from a response
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $requestEntity request
     * @param string $responseStatus data
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest
     */
    public function updateStatusAndValidateEntity(
        UserAccessControl $uac,
        AccountRecoveryRequest $requestEntity,
        string $responseStatus
    ): AccountRecoveryRequest {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $requestEntity */
        $requestEntity = $this->patchEntity($requestEntity, [
            'status' => $this->getRequestStatusFromResponse($responseStatus),
            'modified_by' => $uac->getId(),
        ], ['accessibleFields' => [
            'status' => true,
            'modified_by' => true,
        ]]);

        if ($requestEntity->getErrors()) {
            $msg = __('The account request is invalid.');
            throw new ValidationException($msg, $requestEntity, $this);
        }

        return $requestEntity;
    }

    /**
     * Return new request status based on response status
     *
     * @param string $status response status
     * @return string mapped request status
     */
    protected function getRequestStatusFromResponse(string $status): string
    {
        if ($status === AccountRecoveryResponse::STATUS_REJECTED) {
            return AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_REJECTED;
        }
        if ($status === AccountRecoveryResponse::STATUS_APPROVED) {
            return AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED;
        }

        throw new BadRequestException(__('Invalid response status. Not supported.'));
    }
}
