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
use App\Utility\UserAccessControl;
use Cake\Chronos\Chronos;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;

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
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPublicKeysTable&\Cake\ORM\Association\BelongsTo $AccountRecoveryOrganizationPublicKeys
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
        $this->belongsTo('AccountRecoveryOrganizationPublicKeys', [
            'className' => 'Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys',
            'foreignKey' => 'public_key_id',
        ]);
        $this->belongsTo('Creator', [
            'className' => 'Users',
            'foreignKey' => 'created_by',
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
            ->notEmptyString('policy', __('The policy name should not be empty.'))
            ->inList(
                'policy',
                AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES,
                __(
                    'The policy should be one of the following: {0}.',
                    implode(', ', AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES)
                )
            );

        $validator
            ->uuid('public_key_id', __('The public key identifier should be a valid UUID.'))
            ->allowEmptyString('public_key_id', __('The public key identifier should not be empty.'));

        $validator
            ->uuid(
                'created_by',
                __('The identifier of the user who created the organization policy should be a valid UUID.')
            )
            ->requirePresence(
                'created_by',
                'create',
                __('The identifier of the user who created the organization policy is required.')
            )
            ->notEmptyString(
                'created_by',
                __('The identifier of the user who created the organization policy should not be empty.'),
                false
            );

        $validator
            ->uuid(
                'modified_by',
                __('The identifier of the user who modified the organization policy should be a valid UUID.')
            )
            ->requirePresence(
                'modified_by',
                'create',
                __('The identifier of the user who modified the organization policy is required.')
            )
            ->notEmptyString(
                'modified_by',
                __('The identifier of the user who modified the organization policy should not be empty.'),
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

        return $rules;
    }

    /**
     * Get the current policy name
     *
     * @return string
     */
    public function getCurrentPolicyName(): string
    {
        try {
            $policy = $this->getCurrentPolicyOrFail($this->find());
        } catch (RecordNotFoundException $exception) {
            return AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED;
        }

        return $policy->policy;
    }

    /**
     * Get currently active policy if any
     *
     * @param \Cake\ORM\Query $query Query passed with potential contains
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy current policy
     * @throws \Cake\Http\Exception\NotFoundException if no active policy found
     */
    public function getCurrentPolicyOrFail(Query $query): AccountRecoveryOrganizationPolicy
    {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy */
        $policy = $query
            ->select()
            ->contain('AccountRecoveryOrganizationPublicKeys', function (Query $q) {
                return $q->select([
                    'AccountRecoveryOrganizationPublicKeys.id',
                    'AccountRecoveryOrganizationPublicKeys.fingerprint',
                    'AccountRecoveryOrganizationPublicKeys.armored_key',
                    'AccountRecoveryOrganizationPublicKeys.created',
                    'AccountRecoveryOrganizationPublicKeys.modified',
                    'AccountRecoveryOrganizationPublicKeys.created_by',
                    'AccountRecoveryOrganizationPublicKeys.modified_by',
                ]);
            })
            ->where(function ($exp) {
                // Must not be deleted
                return $exp->isNull('AccountRecoveryOrganizationPolicies.deleted');
            })
            ->where(function ($exp) {
                // Must have a non deleted public key
                return $exp->isNull('AccountRecoveryOrganizationPublicKeys.deleted');
            })
            ->order(['AccountRecoveryOrganizationPolicies.created' => 'DESC'])
            ->firstOrFail();

        return $policy;
    }

    /**
     * Mark the old policy as deleted if any and save the new one
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $newPolicy policy that replaces it
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     * @throws \Exception Will re-throw any exception raised in $callback after rolling back the transaction.
     */
    public function replace(
        UserAccessControl $uac,
        AccountRecoveryOrganizationPolicy $newPolicy
    ): AccountRecoveryOrganizationPolicy {
        $this->getConnection()->transactional(function () use (&$newPolicy, $uac) {
            $saveOptions = ['atomic' => false];
            $this->softDeleteCurrentPolicy($uac, $saveOptions);
            $newPolicy = $this->createOrFail($uac, $newPolicy, $saveOptions);
        });

        return $newPolicy;
    }

    /**
     * Soft delete current policy if any
     *
     * @param \App\Utility\UserAccessControl $uac user access controlt
     * @param array|null $saveOptions options such as validate, checkRules, etc.
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy|null the soft deleted org policy
     */
    protected function softDeleteCurrentPolicy(UserAccessControl $uac, ?array $saveOptions = null)
    {
        try {
            $oldPolicy = $this->getCurrentPolicyOrFail($this->find());
        } catch (RecordNotFoundException $exception) {
            // No current policy to delete, do nothing
            return null;
        }

        $oldPolicy = $this->patchForSoftDelete($uac, $oldPolicy);

        return $this->saveOrFail($oldPolicy, $saveOptions);
    }

    /**
     * Create an org policy entry or die trying
     * Allow saving associated public key if any
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $newPolicy entity
     * @param array|null $saveOptions options such as validate, checkRules, etc.
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    public function createOrFail(
        UserAccessControl $uac,
        AccountRecoveryOrganizationPolicy $newPolicy,
        ?array $saveOptions = null
    ): AccountRecoveryOrganizationPolicy {
        if (isset($newPolicy->account_recovery_organization_public_key)) {
            $saveOptions['associated'] = [
                'AccountRecoveryOrganizationPublicKeys' => [
                    'validate' => $saveOptions['validate'] ?? true,
                    'checkRules' => $saveOptions['checkRules'] ?? true,
                ],
            ];
        }

        return $this->saveOrFail($newPolicy, $saveOptions);
    }

    /**
     * Patch an org policy entity in order to mark it as soft deleted
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy entity to soft delete
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy entity ready to be saved
     */
    protected function patchForSoftDelete(
        UserAccessControl $uac,
        AccountRecoveryOrganizationPolicy $policy
    ): AccountRecoveryOrganizationPolicy {
        return $this->patchEntity($policy, [
            'policy' => $policy->policy,
            'modified_by' => $uac->getId(),
            'deleted' => Chronos::now(),
        ], [
            'accessibleFields' => [
                'modified_by' => true,
                'deleted' => true,
            ],
        ]);
    }

    /**
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $policy user provided data
     * @param string|null $publicKeyId user provided data
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy entity ready to be saved
     */
    public function buildAndValidateEntity(
        UserAccessControl $uac,
        string $policy,
        ?string $publicKeyId = null
    ): AccountRecoveryOrganizationPolicy {
        $data = [
            'policy' => $policy,
            'created_by' => $uac->getId(),
            'modified_by' => $uac->getId(),
        ];
        $accessibleFields = [
            'policy' => true,
            'created' => true,
            'modified' => true,
            'created_by' => true,
            'modified_by' => true,
        ];

        if (isset($publicKeyId)) {
            $data['public_key_id'] = $publicKeyId;
            $accessibleFields['public_key_id'] = true;
        }

        $newPolicy = $this->newEntity($data, ['accessibleFields' => $accessibleFields]);
        if ($newPolicy->getErrors()) {
            $em = __('Could not validate policy data.');
            throw new ValidationException($em, $newPolicy, $this);
        }

        return $newPolicy;
    }

    /**
     * Return a new org policy entity that is set to disabled
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy entity ready to be saved
     */
    public function newEntityForDisable(UserAccessControl $uac): AccountRecoveryOrganizationPolicy
    {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy */
        $policy = $this->newEntity([
                'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED,
                'created_by' => $uac->getId(),
                'modified_by' => $uac->getId(),
            ], [
                'accessibleFields' => [
                    'policy' => true,
                    'created_by' => true,
                    'modified_by' => true,
                ],
            ]);

        return $policy;
    }

    /**
     * Get a disabled AccountRecoveryOrganizationPolicy entity
     * with an empty key and no creation / modified date
     * Used as a fallback when no policy is present
     *
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    public function newEntityForDefaultFallback()
    {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy */
        $policy = $this->newEntity([
                'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED,
                'public_key_id' => null,
            ], [
                'accessibleFields' => [
                    'policy' => true,
                    'public_key_id' => true,
                ],
            ]);

        return $policy;
    }
}
