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
            ->inList(
                'policy',
                AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES,
                __(
                    'The policy should be one of the following: {0}.',
                    implode(', ', AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES)
                )
            )
            ->requirePresence('policy', 'create', __('A policy is required.'))
            ->notEmptyString('policy', __('The policy should not be empty'));

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
        return $rules;
    }
}
