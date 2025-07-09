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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Model\Table;

use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Validation\ArmoredMessage\IsParsableMessageValidationRule;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Metadata\Model\Rule\IsValidEncryptedMetadataSessionKeyRule;

/**
 * MetadataSessionKeys Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey newEmptyEntity()
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey newEntity(array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey[]|iterable<mixed, \Cake\Datasource\EntityInterface> saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey[]|iterable<mixed, \Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey[]|iterable<mixed, \Cake\Datasource\EntityInterface> deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataSessionKey[]|iterable<mixed, \Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MetadataSessionKeysTable extends Table
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

        $this->setTable('metadata_session_keys');
        $this->setDisplayField('id');
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
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->notEmptyString('user_id', __('The user identifier should not be empty.'), 'create');

        $validator
            ->ascii('data', __('The data should be a valid ASCII string.'))
            ->requirePresence('data', 'create', __('A data is required.'))
            ->notEmptyString('data', __('The data should not be empty.'))
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
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);
        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);

        $rules->add(new IsValidEncryptedMetadataSessionKeyRule(), 'isValidEncryptedMetadataSessionKey', [
            'errorField' => 'data',
            'message' => __('The data is not valid. Please make sure it is encrypted for the correct key.'),
        ]);

        return $rules;
    }
}
