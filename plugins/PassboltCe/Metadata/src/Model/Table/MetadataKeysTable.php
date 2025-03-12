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

use App\Model\Rule\IsNotServerKeyFingerprintRule;
use App\Model\Rule\IsNotUserKeyFingerprintRule;
use App\Model\Validation\ArmoredKey\IsParsableArmoredKeyValidationRule;
use App\Model\Validation\ArmoredKey\IsPublicKeyRevokedRule;
use App\Model\Validation\ArmoredKey\IsPublicKeyValidStrictRule;
use App\Model\Validation\DateTime\IsDateInPastValidationRule;
use App\Model\Validation\Fingerprint\IsMatchingKeyFingerprintValidationRule;
use App\Model\Validation\Fingerprint\IsValidFingerprintValidationRule;
use App\Model\Validation\IsNullOnCreateRule;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Metadata\Model\Rule\MaxNoOfActiveMetadataKeysRule;

/**
 * MetadataKeys Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Creator
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Modifier
 * @property \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable&\Cake\ORM\Association\HasMany $MetadataPrivateKeys
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey newEmptyEntity()
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey newEntity(array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey get($primaryKey, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey[]|iterable<mixed, \Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey[]|iterable<mixed, \Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey[]|iterable<mixed, \Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey[]|iterable<mixed, \Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MetadataKeysTable extends Table
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

        $this->setTable('metadata_keys');
        $this->setDisplayField('fingerprint');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('MetadataPrivateKeys', [
            'foreignKey' => 'metadata_key_id',
            'className' => 'Passbolt/Metadata.MetadataPrivateKeys',
        ]);

        $this->hasOne('Creator', [
            'className' => 'Users',
            'bindingKey' => 'created_by',
            'foreignKey' => 'id',
        ]);
        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id',
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
            ->requirePresence('fingerprint', 'create', __('A fingerprint is required.'))
            ->maxLength('fingerprint', 51, __('A fingerprint should not be greater than 51 characters.'))
            ->notEmptyString('fingerprint', __('A fingerprint should not be empty.'))
            ->alphaNumeric('fingerprint', __('The fingerprint should be a valid alphanumeric string.'))
            ->add('fingerprint', 'custom', new IsValidFingerprintValidationRule())
            ->add('fingerprint', 'isMatchingKeyFingerprint', new IsMatchingKeyFingerprintValidationRule());

        $validator
            ->ascii('armored_key', __('The armored key should be a valid ASCII string.'))
            ->requirePresence('armored_key', 'create', __('An armored key is required.'))
            ->notEmptyString('armored_key', __('The armored key should not be empty.'))
            ->add('armored_key', 'isPublicKeyValidStrict', new IsPublicKeyValidStrictRule())
            ->add('armored_key', 'isParsableArmoredPublicKey', new IsParsableArmoredKeyValidationRule());

        $validator
            ->dateTime('expired')
            ->allowEmptyDateTime('expired')
            ->add('expired', 'isNullOnCreate', new IsNullOnCreateRule());

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted')
            ->add('deleted', 'isNullOnCreate', new IsNullOnCreateRule());

        $validator
            ->uuid('created_by', __('The identifier of the user who created the metadata key should be a valid UUID.'))
            ->requirePresence('created_by', 'create', __('The creator is required.'));

        $validator
            ->uuid('modified_by', __('The identifier of the user who modified the metadata key should be a valid UUID.')) // phpcs:ignore;
            ->allowEmptyString('modified_by');

        return $validator;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationUpdate(Validator $validator): Validator
    {
        $validator
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->requirePresence('id', 'update', __('An identifier is required.'));

        $validator
            ->requirePresence('fingerprint', 'update', __('A fingerprint is required.'))
            ->maxLength('fingerprint', 51, __('A fingerprint should not be greater than 51 characters.'))
            ->notEmptyString('fingerprint', __('A fingerprint should not be empty.'))
            ->alphaNumeric('fingerprint', __('The fingerprint should be a valid alphanumeric string.'))
            ->add('fingerprint', 'isValidFingerprint', new IsValidFingerprintValidationRule())
            ->add('fingerprint', 'isMatchingKeyFingerprint', new IsMatchingKeyFingerprintValidationRule());

        $validator
            ->ascii('armored_key', __('The armored key should be a valid ASCII string.'))
            ->requirePresence('armored_key', 'update', __('An armored key is required.'))
            ->notEmptyString('armored_key', __('The armored key should not be empty.'))
            ->add('armored_key', 'isParsableArmoredPublicKey', new IsParsableArmoredKeyValidationRule())
            ->add('armored_key', 'isPublicKeyRevoked', new IsPublicKeyRevokedRule());

        $validator
            ->dateTime('expired')
            ->requirePresence('expired', 'update', __('A expired date is required.'))
            ->notEmptyDateTime('expired', __('The expired date should not be empty.'))
            ->add('expired', 'isDateInPast', new IsDateInPastValidationRule());

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        $validator
            ->uuid('modified_by', __('The identifier of the user who modified the metadata key should be a valid UUID.')) // phpcs:ignore;
            ->requirePresence('modified_by', 'update', __('The modifier is required.'));

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
        $rules->add(
            $rules->isUnique(
                ['fingerprint'],
                __('The fingerprint is already in use.'),
            ),
            ['errorField' => 'fingerprint']
        );
        $rules->addCreate(new MaxNoOfActiveMetadataKeysRule(), 'maxNoOfActiveKeys', [
            'errorField' => 'fingerprint',
            'message' => __('Already two metadata keys are active.'),
        ]);
        $rules->add(new IsNotServerKeyFingerprintRule(), 'isNotServerKeyFingerprintRule', [
            'errorField' => 'fingerprint',
            'message' => __('You cannot reuse the server keys.'),
        ]);
        $rules->add(new IsNotUserKeyFingerprintRule(), 'isNotUserKeyFingerprintRule', [
            'errorField' => 'fingerprint',
            'message' => __('You cannot reuse the user key.'),
        ]);

        return $rules;
    }

    /**
     * @param \Cake\ORM\Query $query Query.
     * @param array $options Finder options.
     * @return \Cake\ORM\Query
     */
    public function findActive(Query $query, array $options): Query
    {
        return $query->where([
            $query->newExpr()->isNull('deleted'),
            $query->newExpr()->isNull('expired'),
        ]);
    }

    /**
     * @return array|\Cake\Datasource\EntityInterface
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no first record.
     */
    public function getLatestActiveKey()
    {
        return $this
            ->find()
            ->select(['id', 'fingerprint', 'armored_key'])
            ->where(['deleted IS NULL', 'expired IS NULL'])
            ->order(['created' => 'DESC'])
            ->firstOrFail();
    }
}
