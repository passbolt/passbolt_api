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

namespace Passbolt\Sso\Model\Table;

use App\Model\Validation\ArmoredMessage\IsParsableMessageValidationRule;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Sso\Model\Entity\SsoSetting;

/**
 * SsoSettings Model
 *
 * @method \Passbolt\Sso\Model\Entity\SsoSetting get($primaryKey, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoSetting newEntity(array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoSetting[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoSetting|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoSetting[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoSetting findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoSetting newEmptyEntity()
 * @method \Passbolt\Sso\Model\Entity\SsoSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoSetting[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoSetting[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoSetting[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoSetting[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SsoSettingsTable extends Table
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
            ->requirePresence('provider', __('A provider is required.'))
            ->notEmptyString('provider', __('The provider should not be empty.'))
            ->maxLength('provider', 64)
            ->inList('provider', SsoSetting::ALLOWED_PROVIDERS, __('The provider is not supported.'));

        $validator
            ->requirePresence('status', __('A status is required.'))
            ->notEmptyString('status', __('The status should not be empty.'))
            ->maxLength('status', 8, __('The status length is not supported.'))
            ->inList('status', SsoSetting::ALLOWED_STATUSES, __('The status is not supported.'));

        $validator
            ->ascii('data', __('The message should be a valid ASCII string.'))
            ->requirePresence('data', 'create', __('A message is required.'))
            ->notEmptyString('data', __('The message should not be empty.'))
            ->add('data', 'isValidOpenPGPMessage', new IsParsableMessageValidationRule());

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
