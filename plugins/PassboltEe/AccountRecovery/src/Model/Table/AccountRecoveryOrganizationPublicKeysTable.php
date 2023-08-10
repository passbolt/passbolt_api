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
use App\Model\Rule\IsNotServerKeyFingerprintRule;
use App\Model\Rule\IsNotUserKeyFingerprintRule;
use App\Model\Validation\ArmoredKey\IsParsableArmoredKeyValidationRule;
use App\Model\Validation\Fingerprint\IsMatchingKeyFingerprintValidationRule;
use App\Model\Validation\Fingerprint\IsValidFingerprintValidationRule;
use App\Utility\UserAccessControl;
use ArrayObject;
use Cake\Chronos\Chronos;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey;
use Passbolt\AccountRecovery\Model\Rule\IsNotAccountRecoveryOrganizationKeyFingerprintRule;
use Phinx\Db\Adapter\MysqlAdapter;

/**
 * AccountRecoveryOrganizationPolicies Model
 *
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey get($primaryKey, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey newEntity(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey[] newEntities(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey newEmptyEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccountRecoveryOrganizationPublicKeysTable extends Table
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

        $this->setTable('account_recovery_organization_public_keys');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->dateTime('deleted', ['ymd'], __('The "deleted" field should be a valid date.'))
            ->allowEmptyDateTime('deleted');

        $validator
            ->uuid(
                'created_by',
                __('The identifier of the user who created the organization public key should be a valid UUID.')
            )
            ->requirePresence(
                'created_by',
                'create',
                __('The identifier of the user who created the organization public key is required.')
            )
            ->notEmptyString(
                'created_by',
                __('The identifier of the user who created the organization public key should not be empty.'),
                false
            );

        $validator
            ->uuid(
                'modified_by',
                __('The identifier of the user who modified the organization public key should be a valid UUID.')
            )
            ->requirePresence(
                'modified_by',
                'create',
                __('The identifier of the user who modified the organization public key is required.')
            )
            ->notEmptyString(
                'modified_by',
                __('The identifier of the user who modified the organization public key should not be empty.'),
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

        $rules->add(new IsNotServerKeyFingerprintRule(), 'isNotServerKeyFingerprintRule', [
            'errorField' => 'fingerprint',
            'message' => __('You cannot reuse the server keys.'),
        ]);

        $rules->add(new IsNotUserKeyFingerprintRule(), 'isNotUserKeyFingerprintRule', [
            'errorField' => 'fingerprint',
            'message' => __('You cannot reuse the user keys.'),
        ]);

        $rules->addCreate(
            new IsNotAccountRecoveryOrganizationKeyFingerprintRule(),
            'isNotAccountRecoveryOrganizationPublicKeyFingerprintRule',
            [
            'errorField' => 'fingerprint',
            'message' => __('You cannot reuse account recovery organization public keys.'),
            ]
        );

        return $rules;
    }

    /**
     * Build and validate a public key entity from user provided data
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param array $data public key data
     * @throws \App\Error\Exception\ValidationException if entity validation fails
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey
     */
    public function buildAndValidateEntity(UserAccessControl $uac, array $data): AccountRecoveryOrganizationPublicKey
    {
        $data['created_by'] = $uac->getId();
        $data['modified_by'] = $uac->getId();

        $publicKey = $this->newEntity($data, [
            'accessibleFields' => [
                'fingerprint' => true,
                'armored_key' => true,
                'created_by' => true,
                'modified_by' => true,
            ],
        ]);

        if ($publicKey->getErrors()) {
            throw new ValidationException(__('Could not validate public key data.'), $publicKey, $this);
        }

        return $publicKey;
    }

    /**
     * Format fingerprint data to remove spaces and set it to uppercase
     *
     * @param \Cake\Event\EventInterface $event event
     * @param \ArrayObject $data user provided data
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options)
    {
        if (isset($data['fingerprint']) && is_string($data['fingerprint'])) {
            $data['fingerprint'] = strtoupper(str_replace(' ', '', $data['fingerprint']));
        }
    }

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey $oldEntity old public key to be patched
     * @param string $revokedArmored OpenPGP armored key block to replace old non-revoked public key blocked
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey
     */
    public function patchAndValidateEntityForRevocation(
        UserAccessControl $uac,
        AccountRecoveryOrganizationPublicKey $oldEntity,
        string $revokedArmored
    ): AccountRecoveryOrganizationPublicKey {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey $patchedEntity */
        $patchedEntity = $this->patchEntity($oldEntity, [
            'modified_by' => $uac->getId(),
            'armored_key' => $revokedArmored,
            'deleted' => Chronos::now(),
        ], [
            'fields' => ['deleted', 'modified_by', 'armored_key'],
        ]);

        return $patchedEntity;
    }
}
