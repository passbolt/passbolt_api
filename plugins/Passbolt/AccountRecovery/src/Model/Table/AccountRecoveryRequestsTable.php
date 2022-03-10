<?php
declare(strict_types=1);

namespace Passbolt\AccountRecovery\Model\Table;

use App\Model\Rule\User\IsActiveUserRule;
use App\Model\Validation\ArmoredKey\IsParsableArmoredKeyValidationRule;
use App\Model\Validation\Fingerprint\IsValidFingerprintValidationRule;
use App\Utility\UserAccessControl;
use Cake\Chronos\Chronos;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;

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

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className' => 'Passbolt/AccountRecovery.Users',
        ]);
        $this->belongsTo('AuthenticationTokens', [
            'foreignKey' => 'authentication_token_id',
            'joinType' => 'INNER',
            'className' => 'Passbolt/AccountRecovery.AuthenticationTokens',
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
            ->allowEmptyString('id', null, 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('armored_key')
            ->allowEmptyString('armored_key');

        $validator
            ->scalar('fingerprint')
            ->maxLength('fingerprint', 40)
            ->allowEmptyString('fingerprint');

        $validator
            ->scalar('status')
            ->inList('status', AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_STATUSES)
            ->maxLength('status', 36)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->ascii('armored_key', __('The armored key should be a valid ASCII string.'))
            ->requirePresence('armored_key', 'create', __('An armored key is required.'))
            ->notEmptyString('armored_key', __('The armored key should not be empty.'))
            ->add('armored_key', 'invalidArmoredKey', new IsParsableArmoredKeyValidationRule());

        $validator
            ->ascii('fingerprint', __('The fingerprint should be a valid ASCII string.'))
            ->requirePresence('fingerprint', 'create', __('A fingerprint is required'))
            ->notEmptyString('fingerprint', __('The fingerprint should not be empty'))
            ->add('fingerprint', 'invalidFingerprint', new IsValidFingerprintValidationRule());

        $validator
            ->uuid('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

        $validator
            ->uuid('modified_by')
            ->requirePresence('modified_by', 'create')
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
        $rules->add(new IsActiveUserRule(), [
            'errorField' => 'user_id',
            'message' => __('The user does not exist or is not active or has been deleted.'),
        ]);
        $rules->add($rules->existsIn('created_by', 'Users'));
        $rules->add($rules->existsIn('modified_by', 'Users'));

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
        $this->deleteAll([
            'id !=' => $request->id,
            'user_id' => $request->user_id,
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
}
