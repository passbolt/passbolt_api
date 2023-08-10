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
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Validation\ArmoredMessage\IsParsableMessageValidationRule;
use App\Utility\UserAccessControl;
use Cake\Core\Exception\CakeException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Phinx\Db\Adapter\MysqlAdapter;

/**
 * AccountRecoveryResponses Model
 *
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse newEmptyEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse newEntity(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse[] newEntities(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse get($primaryKey, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccountRecoveryResponsesTable extends Table
{
    use TableCleanupTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('account_recovery_responses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('Responder', [
            'className' => AccountRecoveryOrganizationPublicKey::class,
            'foreignKey' => 'foreign_key',
        ]);

        $this->belongsTo('Passbolt/AccountRecovery.AccountRecoveryRequests');

        $this->belongsTo('Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys', [
           'foreignKey' => 'responder_foreign_key',
        ]);

        $this->belongsTo('Creator', [
            'className' => 'Users',
            'foreignKey' => 'created_by',
        ]);
        $this->belongsTo('Modifier', [
            'className' => 'Users',
            'foreignKey' => 'modified_by',
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
            ->inList('status', AccountRecoveryResponse::STATUSES, __('This status is not supported.'))
            ->maxLength('status', 36, __('The status length should be maximum {0} characters.', 36))
            ->requirePresence('status', 'create', __('A status is required.'))
            ->notEmptyString('status', __('The status should not be empty.'));

        $validator
            ->notEmptyString('responder_foreign_key', __('The responder_foreign_key should not be empty.'))
            ->uuid('responder_foreign_key', __('The responder_foreign_key should be a valid UUID.'));

        $validator
            ->scalar('responder_foreign_model', __('The responder_foreign_model should be a valid string.'))
            ->requirePresence('responder_foreign_model', 'create')
            ->notEmptyString('responder_foreign_model', __('The responder_foreign_model should not be empty.'))
            ->inList('responder_foreign_model', AccountRecoveryResponse::ALLOWED_RESPONDER_FOREIGN_MODELS, __(
                'The responder_foreign_model must be one of the following: {0}.',
                implode(', ', AccountRecoveryResponse::ALLOWED_RESPONDER_FOREIGN_MODELS)
            ));

        $validator
            ->notEmptyString('data', __('The data should not be empty.'))
            ->maxLength('data', MysqlAdapter::TEXT_MEDIUM, __('The data is too big.'))
            ->add('data', 'isValidOpenPGPMessage', new IsParsableMessageValidationRule());

        $validator
            ->uuid('created_by', __('The identifier of the user who created the response should be a valid UUID.'))
            ->requirePresence(
                'created_by',
                'create',
                __('The identifier of the user who created the response is required.')
            )
            ->notEmptyString(
                'created_by',
                __('The identifier of the user who created the response should not be empty.'),
                false
            );

        $validator
            ->uuid('modified_by', __('The identifier of the user who modified the response should be a valid UUID.'))
            ->requirePresence(
                'modified_by',
                'create',
                __('The identifier of the user who modified the response is required.')
            )
            ->notEmptyString(
                'modified_by',
                __('The identifier of the user who modified the response should not be empty.'),
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
        $rules->add($rules->existsIn('created_by', 'Creator'));
        $rules->add($rules->existsIn('modified_by', 'Modifier'));

        // TODO contextual switch based on responder_foreign_model
        $rules->add($rules->existsIn('responder_foreign_key', 'AccountRecoveryOrganizationPublicKeys'));

        return $rules;
    }

    /**
     * Build the query that fetches all requests
     *
     * @param \Cake\ORM\Query $query a query instance
     * @param array $options options
     * @throws \Cake\Core\Exception\CakeException if no role is specified
     * @return \Cake\ORM\Query
     */
    public function findIndex(Query $query, array $options): Query
    {
        $fields = [
            'id',
            'responder_foreign_key',
            'responder_foreign_model',
            'status',
            'created',
            'modified',
            'created_by',
            'modified_by',
            'account_recovery_request_id',
            // 'data' // not available by default
        ];

        $contains = $options['contains'] ?? [];
        $containsArmored = $contains['data'] ?? false;
        if ($containsArmored) {
            $fields[] = 'data';
        }

        $query->select($fields);

        return $query;
    }

    /**
     * Build the query that fetches one request
     *
     * @param \Cake\ORM\Query $query a query instance
     * @param array $options options
     * @throws \Cake\Core\Exception\CakeException if no id is specified
     * @return \Cake\ORM\Query
     */
    public function findView(Query $query, array $options): Query
    {
        // Options must contain an id
        if (!isset($options['id'])) {
            throw new CakeException('An ID must be provided.');
        }

        // Same rule than index apply
        // with a specific id requested
        $query = $this->findIndex($query, $options);
        $query->where(['id' => $options['id']]);

        return $query;
    }

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param array $data user provided data
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse
     */
    public function buildAndValidateEntity(UserAccessControl $uac, array $data): AccountRecoveryResponse
    {
        $data = array_merge($data, [
            'created_by' => $uac->getId(),
            'modified_by' => $uac->getId(),
        ]);

        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse $responseEntity */
        $responseEntity = $this->newEntity($data, [
            'accessibleFields' => [
                'account_recovery_request_id' => true,
                'responder_foreign_key' => true,
                'responder_foreign_model' => true,
                'data' => true,
                'status' => true,
                'created_by' => true,
                'modified_by' => true,
            ],
        ]);

        if ($responseEntity->getErrors()) {
            $msg = __('The account recovery request response is invalid.');
            throw new ValidationException($msg, $responseEntity, $this);
        }

        return $responseEntity;
    }

    /**
     * Delete all records where associated responses are deleted
     *
     * @param bool|null $dryRun false
     * @return int of affected records
     */
    public function cleanupHardDeletedAccountRecoveryRequests(?bool $dryRun = false): int
    {
        return $this->cleanupHardDeleted('AccountRecoveryRequests', $dryRun);
    }
}
