<?php
declare(strict_types=1);

namespace Passbolt\AccountRecovery\Model\Table;

use App\Model\Rule\User\IsActiveUserRule;
use App\Model\Traits\OpenPGP\PublicKeyValidatorTrait;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;

/**
 * AccountRecoveryRequests Model
 *
 * @property \Passbolt\AccountRecovery\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \Passbolt\AccountRecovery\Model\Table\AuthenticationTokensTable&\Cake\ORM\Association\BelongsTo $AuthenticationTokens
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
    use PublicKeyValidatorTrait;

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
            ->add('armored_key', ['invalidArmoredKey' => [
                'rule' => [$this, 'isParsableArmoredPublicKeyRule'],
                'message' => __('The armored key should be a valid ASCII-armored OpenPGP key.'),
            ]]);

        $validator
            ->ascii('fingerprint', __('The fingerprint should be a valid ASCII string.'))
            ->requirePresence('fingerprint', 'create', __('A fingerprint is required'))
            ->notEmptyString('fingerprint', __('The fingerprint should not be empty'))
            ->add('fingerprint', ['invalidFingerprint' => [
                'rule' => [$this, 'isValidFingerprintRule'],
                'message' => __('The fingerprint should be a string of 40 hexadecimal characters.'),
            ]]);

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
}
