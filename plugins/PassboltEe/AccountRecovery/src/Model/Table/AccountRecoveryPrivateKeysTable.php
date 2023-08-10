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
use App\Model\Validation\ArmoredMessage\IsParsableMessageValidationRule;
use App\Utility\UserAccessControl;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey;
use Passbolt\AccountRecovery\Model\Table\Traits\TableTruncateTrait;
use Phinx\Db\Adapter\MysqlAdapter;

/**
 * AccountRecoveryPrivateKeys Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable&\Cake\ORM\Association\HasMany $AccountRecoveryPrivateKeyPasswords
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey newEmptyEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey newEntity(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey[] newEntities(array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey get($primaryKey, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccountRecoveryPrivateKeysTable extends Table
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

        $this->setTable('account_recovery_private_keys');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users');

        $this->hasMany('Passbolt/AccountRecovery.AccountRecoveryPrivateKeyPasswords', [
            'foreignKey' => 'private_key_id',
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
            ->scalar('data', __('The data should be a valid string.'))
            ->requirePresence('data', 'create', __('The data is required.'))
            ->notEmptyString('data', __('The data should not be empty.'))
            ->maxLength('data', MysqlAdapter::TEXT_MEDIUM, __('The data is too big.'))
            ->add('data', 'isValidOpenPGPMessage', new IsParsableMessageValidationRule());

        $validator
            ->uuid(
                'created_by',
                __('The identifier of the user who created the private key  should be a valid UUID.')
            )
            ->requirePresence(
                'created_by',
                'create',
                __('The identifier of the user who created the private key  is required.')
            )
            ->notEmptyString(
                'created_by',
                __('The identifier of the user who created the private key  should not be empty.'),
                false
            );

        $validator
            ->uuid(
                'modified_by',
                __('The identifier of the user who modified the private key  should be a valid UUID.')
            )
            ->requirePresence(
                'modified_by',
                'create',
                __('The identifier of the user who modified the private key is required.')
            )
            ->notEmptyString(
                'modified_by',
                __('The identifier of the user who modified the private key  should not be empty.'),
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
        $rules->isUnique(['id']);
        $rules->existsIn('user_id', 'Users');
        $rules->existsIn('created_by', 'Users');
        $rules->existsIn('modified_by', 'Users');

        return $rules;
    }

    /**
     * @param \App\Utility\UserAccessControl $uac UAC
     * @param array $privateKey Private key
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey
     */
    public function buildAndValidateEntity(UserAccessControl $uac, array $privateKey): AccountRecoveryPrivateKey
    {
        $userId = $uac->getId();

        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey $privateKeyEntity */
        $privateKeyEntity = $this->newEntity([
            'user_id' => $userId,
            'data' => $privateKey['data'] ?? [],
            'created_by' => $userId,
            'modified_by' => $userId,
        ], [
            'accessibleFields' => [
                'user_id' => true,
                'data' => true,
                'account_recovery_private_key_passwords' => true,
                'created_by' => true,
                'modified_by' => true,
            ],
        ]);

        if ($privateKeyEntity->hasErrors()) {
            $msg = __('The account recovery private key is not valid.');
            throw new ValidationException($msg, $privateKeyEntity, $this);
        }

        return $privateKeyEntity;
    }
}
