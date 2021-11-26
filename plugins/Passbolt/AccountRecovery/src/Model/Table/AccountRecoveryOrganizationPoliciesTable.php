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
 * @since         3.4.0
 */

namespace Passbolt\AccountRecovery\Model\Table;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey;

/**
 * AccountRecoveryOrganizationPolicies Model
 *
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy get($primaryKey, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy newEntity(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy[] newEntities(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy newEmptyEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 *
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPublicKeysTable&\Cake\ORM\Association\HasOne $Avatars
 */
class AccountRecoveryOrganizationPoliciesTable extends Table
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

        $this->setTable('account_recovery_organization_policies');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->hasOne('AccountRecoveryOrganizationPublicKeys', [
            'foreignKey' => 'account_recovery_organization_public_key_id',
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
            ->requirePresence('policy', true, __('A policy is required.'))
            ->notEmptyString('policy', __('The name should not be empty.'))
            ->inList(
                'policy',
                AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES,
                __(
                    'The policy should be one of the following: {0}.',
                    implode(', ', AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES)
                )
            );

        // Associated data
//        $validator
//            ->requirePresence('account_recovery_organization_public_key', 'create',
//                __('An organization public key is required.'));

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
        $rules->addCreate(
            [$this, 'isValidPublicKeyProvided'],
            'account_recovery_organization_public_key_provided',
            [
                'errorField' => 'account_recovery_organization_public_key',
                'message' => __('The organization policy should contain the organization public key.'),
            ]
        );

        return $rules;
    }

    /**
     * @return AccountRecoveryOrganizationPolicy
     * @throws RecordNotFoundException
     */
    public function getCurrentPolicy(): string
    {
        /** @var AccountRecoveryOrganizationPolicy $policy */
        $policy = $this->find()
            ->where(function ($exp, $q) {
                return $exp->isNull('AccountRecoveryOrganizationPolicies.deleted');
            })
            ->order(['AccountRecoveryOrganizationPolicies.created' => 'DESC'])
            ->first();

        return $policy->policy ?? AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED;
    }

    /**
     * Validate that the public key is provided when 'enabling' the feature
     *
     * @param AccountRecoveryOrganizationPolicy $entity The entity that will be created.
     * @param array|null $options options
     * @return bool
     */
    public function isAccountRecoveryOrganizationPublicKeyProvided(AccountRecoveryOrganizationPolicy $entity, ?array $options = [])
    {
        $currentPolicy = $this->getCurrentPolicy();

        $this->isOrganizationPublicKeyNeeded($currentPolicy, $entity->policy);

        // Public key is not
        if (!isset($entity->account_recovery_organization_public_key)) {
            return false;
        }

    }

    /**
     * Is a revocation key needed to perform the change?
     *
     * @param string $currentPolicy
     * @param string $targetPolicy
     * @param bool $keyChange
     * @return bool
     */
    public function isKeyRevocationNeededForChange(string $currentPolicy, string $targetPolicy, bool $keyChange)
    {
        // If there is no public key at the moment, there is no need for revocation
        if ($currentPolicy === AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED) {
            return false;
        }

        // If there is no key change, also no need
        if (!$keyChange) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isKeyNeededForChange($currentPolicy, $targetPolicy)
    {
        // No need to provide new key when setting policy to disabled
        if ($targetPolicy === AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED) {
            return false;
        }

        // Policy is the same, this means this is a change of key
        if ($targetPolicy === $currentPolicy) {
            return true;
        }

        // Current policy is disabled, new policy needs a key
        if ($currentPolicy === AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED) {
            return true;
        }

        return false;
    }
}
