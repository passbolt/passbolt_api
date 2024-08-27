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

use App\Model\Validation\ArmoredMessage\IsParsableMessageValidationRule;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Metadata\Model\Rule\IsValidEncryptedMetadataPrivateKey;
use Passbolt\Metadata\Model\Rule\UserIsActiveAndNotDeletedIfPresent;

/**
 * MetadataPrivateKeys Model
 *
 * @property \Passbolt\Metadata\Model\Table\MetadataKeysTable&\Cake\ORM\Association\BelongsTo $MetadataKeys
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey newEmptyEntity()
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey newEntity(array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey get($primaryKey, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[]|iterable<mixed, \Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[]|iterable<mixed, \Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[]|iterable<mixed, \Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[]|iterable<mixed, \Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MetadataPrivateKeysTable extends Table
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

        $this->setTable('metadata_private_keys');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('MetadataKeys', [
            'foreignKey' => 'metadata_key_id',
            'joinType' => 'INNER',
            'className' => 'Passbolt/Metadata.MetadataKeys',
        ]);
        $this->belongsTo('Users', ['foreignKey' => 'user_id']);
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
            ->uuid('metadata_key_id', __('The metadata key identifier should be a valid UUID.'))
            ->notEmptyString('metadata_key_id', __('The metadata key identifier should not be empty.'));

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->allowEmptyString('user_id');

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
        $rules->add($rules->existsIn('metadata_key_id', 'MetadataKeys'), ['errorField' => 'metadata_key_id']);

        $rules->add($rules->isUnique(['user_id']), null, ['message' => __('The user id is already in use.')]);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);
        $rules->addCreate(new UserIsActiveAndNotDeletedIfPresent(), 'isUserActiveIfPresent', [
            'errorField' => 'user_id',
            'message' => __('The user does not exist or is not active or is deleted.'),
        ]);

        $rules->add(
            $rules->isUnique(['metadata_key_id', 'user_id']),
            null,
            ['message' => __('The metadata key id & user id combination is already in use.')]
        );

        $rules->add(new IsValidEncryptedMetadataPrivateKey(), 'isValidEncryptedMetadataPrivateKey', [
            'errorField' => 'data',
            'message' => __('The data is not valid. Please make sure it is encrypted for the correct key.'),
        ]);

        return $rules;
    }
}
