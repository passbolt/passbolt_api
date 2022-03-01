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
 * @since         3.5.0
 */

namespace Passbolt\AccountRecovery\Model\Table;

use Cake\ORM\Query;
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

        return $validator;
    }

    /**
     * Get the current policy
     *
     * @return string
     */
    public function getCurrentPolicy(): string
    {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy */
        $policy = $this->find()
            ->innerJoinWith('AccountRecoveryOrganizationPublicKeys', function (Query $q) {
                return $q->where(function ($exp) {
                    return $exp->isNull('AccountRecoveryOrganizationPublicKeys.deleted');
                });
            })
            ->order(['AccountRecoveryOrganizationPolicies.created' => 'DESC'])
            ->first();

        return $policy->policy ?? AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED;
    }
}
