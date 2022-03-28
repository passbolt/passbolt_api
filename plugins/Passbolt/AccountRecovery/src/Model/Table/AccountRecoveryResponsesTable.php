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

use App\Model\Table\UsersTable;
use App\Model\Validation\ArmoredMessage\IsParsableMessageValidationRule;
use Cake\Core\Exception\Exception;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;

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

        $this->hasOne('Creator', [
            'className' => UsersTable::class,
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
            ->uuid('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->requirePresence('status', 'create')
            ->notEmptyString('status')
            ->scalar('status')
            ->inList('status', AccountRecoveryResponse::STATUSES)
            ->maxLength('status', 36);

        $validator
            ->notEmptyString('responder_foreign_key')
            ->uuid('responder_foreign_key');

        $validator
            ->scalar('responder_foreign_model')
            ->requirePresence('responder_foreign_model', 'create')
            ->notEmptyString('responder_foreign_model')
            ->inList('responder_foreign_model', AccountRecoveryResponse::ALLOWED_RESPONDER_FOREIGN_MODELS, __(
                'The responder_foreign_model must be one of the following: {0}.',
                implode(', ', AccountRecoveryResponse::ALLOWED_RESPONDER_FOREIGN_MODELS)
            ));

        $validator
            ->notEmptyString('data')
            ->add('data', 'isValidOpenPGPMessage', new IsParsableMessageValidationRule());

        $validator
            ->uuid('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

        $validator
            ->uuid('modified_by')
            ->requirePresence('modified_by')
            ->notEmptyString('modified_by');

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

        // TODO contextual switch based on responder_foreign_model
        //$rules->add($rules->existsIn('foreign_key', 'AccountRecoveryOrganizationPublicKeys'));

        return $rules;
    }

    /**
     * Build the query that fetches all requests
     *
     * @param \Cake\ORM\Query $query a query instance
     * @param array $options options
     * @throws \Cake\Core\Exception\Exception if no role is specified
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
     * @throws \Cake\Core\Exception\Exception if no id is specified
     * @return \Cake\ORM\Query
     */
    public function findView(Query $query, array $options): Query
    {
        // Options must contain an id
        if (!isset($options['id'])) {
            throw new Exception('An ID must be provided.');
        }

        // Same rule than index apply
        // with a specific id requested
        $query = $this->findIndex($query, $options);
        $query->where(['id' => $options['id']]);

        return $query;
    }
}
