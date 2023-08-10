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

use App\Model\Rule\User\IsActiveUserRule;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SsoOrganizationPolicies Model
 *
 * @method \Passbolt\Sso\Model\Entity\SsoKey get($primaryKey, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoKey newEntity(array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoKey[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoKey|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoKey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoKey[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoKey findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoKey newEmptyEntity()
 * @method \Passbolt\Sso\Model\Entity\SsoKey saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoKey[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoKey[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoKey[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Sso\Model\Entity\SsoKey[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SsoKeysTable extends Table
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
        $this->belongsTo('Users');
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
            ->ascii('data', __('The data should be a valid ASCII string.'))
            ->requirePresence('data', 'create', __('Data is required.'))
            ->notEmptyString('data', __('The data should not be empty.'))
            ->maxLength('data', 256, __('The property length should be maximum {0} characters.', 256));

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
        $rules->add(new IsActiveUserRule(), [
            'errorField' => 'user_id',
            'message' => __('The user does not exist or is not active or has been deleted.'),
        ]);

        return $rules;
    }
}
