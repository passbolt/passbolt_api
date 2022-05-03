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
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Cleanup\UsersCleanupTrait;
use App\Utility\UserAccessControl;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Model\Table\Traits\TableTruncateTrait;

/**
 * AccountRecoveryUserSettings Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting newEmptyEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting newEntity(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting[] newEntities(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting get($primaryKey, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccountRecoveryUserSettingsTable extends Table
{
    use TableCleanupTrait;
    use TableTruncateTrait;
    use UsersCleanupTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('account_recovery_user_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users');
        $this->hasOne('AccountRecoveryPrivateKeys', [
            'className' => 'Passbolt/AccountRecovery.AccountRecoveryPrivateKeys',
            'bindingKey' => 'user_id',
        ]);
        $this->belongsTo('Creator', [
            'className' => 'Users',
            'foreignKey' => 'created_by',
        ]);
        $this->belongsTo('Modifier', [
            'className' => 'Users',
            'foreignKey' => 'modified_by',
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
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->notEmptyString('user_id', __('The user identifier should not be empty.'));

        $validator
            ->notEmptyString('status', __('The status should not be empty'))
            ->requirePresence('status', true, __('A status is required.'))
            ->inList(
                'status',
                AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_STATUSES,
                __(
                    'The status should be one of the following: {0}.',
                    implode(', ', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_STATUSES)
                )
            );

        $validator
            ->uuid(
                'created_by',
                __('The identifier of the user who created the user settings should be a valid UUID.')
            )
            ->requirePresence(
                'created_by',
                'create',
                __('The identifier of the user who created the user settings is required.')
            )
            ->notEmptyString(
                'created_by',
                __('The identifier of the user who created the user settings should not be empty.'),
                false
            );

        $validator
            ->uuid(
                'modified_by',
                __('The identifier of the user who modified the user settings should be a valid UUID.')
            )
            ->requirePresence(
                'modified_by',
                'create',
                __('The identifier of the user who modified the user settings is required.')
            )
            ->notEmptyString(
                'modified_by',
                __('The identifier of the user who modified the user settings should not be empty.'),
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
        $rules->add($rules->existsIn('user_id', 'Users'));
        $rules->add($rules->isUnique(['user_id'], __('This user already has an account recovery setting')));
        $rules->add($rules->existsIn('created_by', 'Users'));
        $rules->add($rules->existsIn('modified_by', 'Users'));

        return $rules;
    }

    /**
     * Build and validate a user setting entity
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $status user provided
     * @throws \App\Error\Exception\ValidationException if the entity data does not validate
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting
     */
    public function buildAndValidateEntity(UserAccessControl $uac, string $status): AccountRecoveryUserSetting
    {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting|null $setting */
        $setting = $this->find()
            ->where(['AccountRecoveryUserSettings.user_id' => $uac->getId()])
            ->first();

        if ($setting) {
            $setting = $this->patchEntity($setting, [
                'status' => $status,
                'modified_by' => $uac->getId(),
            ], [
                'accessibleFields' => [
                    'status' => true,
                    'modified_by' => true,
                ],
            ]);
        } else {
            $setting = $this->newEntity([
                'status' => $status,
                'user_id' => $uac->getId(),
                'created_by' => $uac->getId(),
                'modified_by' => $uac->getId(),
            ], [
                'accessibleFields' => [
                    'status' => true,
                    'user_id' => true,
                    'created_by' => true,
                    'modified_by' => true,
                ],
            ]);
        }

        if ($setting->hasErrors()) {
            if ($setting->getError('status')['inList'] ?? false) {
                $msg = $setting->getError('status')['inList'];
            } else {
                $msg = __('The account recovery user setting is not valid.');
            }
            throw new ValidationException($msg, $setting, $this);
        }

        return $setting;
    }
}
