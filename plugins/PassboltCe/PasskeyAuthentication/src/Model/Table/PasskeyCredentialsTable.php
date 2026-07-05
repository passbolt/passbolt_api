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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PasskeyCredentials Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential newEmptyEntity()
 * @method \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential newEntity(array $data, array $options = [])
 * @method \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PasskeyCredentialsTable extends Table
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

        $this->setTable('passkey_credentials');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'Users',
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->ascii('credential_id')
            ->maxLength('credential_id', 512)
            ->requirePresence('credential_id', 'create')
            ->notEmptyString('credential_id');

        $validator
            ->scalar('data')
            ->requirePresence('data', 'create')
            ->notEmptyString('data');

        // Server half of the passphrase kit (base64 JWK). Set right after the attestation is
        // verified; nullable so a credential can exist transiently before its kit is stored.
        $validator
            ->scalar('server_kit_key')
            ->allowEmptyString('server_kit_key');

        $validator
            ->nonNegativeInteger('sign_count')
            ->requirePresence('sign_count', 'create');

        $validator
            ->scalar('transports')
            ->maxLength('transports', 255)
            ->allowEmptyString('transports');

        $validator
            ->uuid('aaguid')
            ->allowEmptyString('aaguid');

        $validator
            ->utf8Extended('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        $validator
            ->dateTime('last_used')
            ->allowEmptyDateTime('last_used');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->isUnique(['credential_id']), [
            'errorField' => 'credential_id',
            'message' => __('This credential is already registered.'),
        ]);

        return $rules;
    }
}
