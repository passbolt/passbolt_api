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
 * @since         2.0.0
 */
namespace App\Model\Table;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Gpgkey;
use App\Model\Rule\IsNotServerKeyFingerprintRule;
use App\Model\Validation\ArmoredKey\IsParsableArmoredKeyValidationRule;
use App\Model\Validation\DateTime\IsCreationDateInFuturePastValidationRule;
use App\Model\Validation\DateTime\IsDateInFutureValidationRule;
use App\Model\Validation\Fingerprint\IsValidFingerprintValidationRule;
use App\Model\Validation\GpgkeyType\IsValidGpgkeyTypeValidationRule;
use App\Model\Validation\KeyId\IsValidKeyIdValidationRule;
use App\Service\OpenPGP\PublicKeyValidationService;
use Cake\Core\Exception\CakeException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * Model to store and validate OpenPGP public keys
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\Gpgkey get($primaryKey, $options = [])
 * @method \App\Model\Entity\Gpgkey newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Gpgkey[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Gpgkey|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Gpgkey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Gpgkey[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Gpgkey findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Gpgkey newEmptyEntity()
 * @method \App\Model\Entity\Gpgkey saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\Gpgkey>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Gpgkey>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Gpgkey>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Gpgkey>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 */
class GpgkeysTable extends Table
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

        $this->setTable('gpgkeys');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
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
            ->ascii('armored_key', __('The armored key should be a valid ASCII string.'))
            ->requirePresence('armored_key', 'create', __('An armored key is required.'))
            ->notEmptyString('armored_key', __('The armored key should not be empty.'))
            ->add('armored_key', 'custom', new IsParsableArmoredKeyValidationRule());

        $validator
            ->ascii('fingerprint', __('The fingerprint should be a valid ASCII string.'))
            ->requirePresence('fingerprint', 'create', __('A fingerprint is required'))
            ->notEmptyString('fingerprint', __('The fingerprint should not be empty'))
            ->add('fingerprint', 'custom', new IsValidFingerprintValidationRule());

        $validator
            ->integer('bits', 'The length should be a valid integer.')
            ->requirePresence('bits', 'create', __('A length is required.'));

        $validator
            ->utf8('uid', __('The identifier should be a valid BMP-UTF8 string.'))
            ->allowEmptyString('uid');

        $validator
            ->ascii('key_id', __('The key identifier should be a valid ASCII string.'))
            ->requirePresence('key_id', 'create', __('A key identifier is required.'))
            ->notEmptyString('key_id', __('The key identifier should not be empty.'))
            ->add('key_id', 'custom', new IsValidKeyIdValidationRule());

        $validator
            ->ascii('type', __('The type should be a valid ASCII string.'))
            ->requirePresence('type', 'create', __('A type is required'))
            ->notEmptyString('type', __('The type should not be empty'))
            ->add('type', 'custom', new IsValidGpgkeyTypeValidationRule());

        $validator
            ->dateTime('expires', ['ymd'], __('The expiry should be a valid date.'))
            ->allowEmptyDateTime('expires')
            ->add('expires', 'custom', new IsDateInFutureValidationRule());

        $validator
            ->dateTime('key_created', ['ymd'], __('The creation date should be a valid date.'))
            ->requirePresence('key_created', 'create', __('A creation date is required.'))
            ->notEmptyDateTime('key_created', __('The creation date should not be empty.'))
            ->add('key_created', 'custom', new IsCreationDateInFuturePastValidationRule());

        $validator
            ->boolean('deleted', __('The deleted status should be a valid boolean.'))
            ->requirePresence('deleted', __('A deleted status is required'));

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->requirePresence('user_id', 'create', __('A user identifier is required'))
            ->notEmptyString('user_id', __('The user identifier should not be empty'));

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->isUnique(['fingerprint']));

        $rules->add(new IsNotServerKeyFingerprintRule(), 'isNotServerKeyFingerprintRule', [
            'errorField' => 'fingerprint',
            'message' => __('You cannot reuse the server keys.'),
        ]);

        return $rules;
    }

    /**
     * Build the query that fetches data for user index
     *
     * @param \Cake\ORM\Query $query a query instance
     * @param array $options options
     * @throws \Cake\Core\Exception\CakeException if no role is specified
     * @return \Cake\ORM\Query
     */
    public function findIndex(Query $query, array $options): Query
    {
        if (isset($options['filter']['modified-after'])) {
            $modified = new FrozenTime($options['filter']['modified-after']);
            $query->where(['modified >' => $modified->i18nFormat('yyyy-MM-dd HH:mm:ss')]);
        }

        $query->where(['deleted' => $options['filter']['is-deleted'] ?? false]);

        if (isset($options['filter']['has-users']) && is_array($options['filter']['has-users'])) {
            $query->where(['user_id IN' => $options['filter']['has-users']]);
        }

        return $query;
    }

    /**
     * Find view
     *
     * @param \Cake\ORM\Query $query a query instance
     * @param array $options options
     * @throws \Cake\Core\Exception\CakeException if no id is specified
     * @return \Cake\ORM\Query
     */
    public function findView(Query $query, array $options): Query
    {
        // Options must contain an id
        if (!isset($options['id'])) {
            throw new CakeException('Gpgkey table findView should have an id set in options.');
        }
        // Same rule than index apply
        // with a specific id requested
        $query = $this->findIndex($query, $options);
        $query->where(['id' => $options['id']]);

        return $query;
    }

    /**
     * Get a gpg key for matching fingerprint and user id
     *
     * @param string $fingerprint char40
     * @param string $userId uuid
     * @throws \InvalidArgumentException if the user id or fingerprint are not valid
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getByFingerprintAndUserId(string $fingerprint, string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException('The user identifier should be a valid UUID.');
        }
        if (!PublicKeyValidationService::isValidFingerprint($fingerprint)) {
            throw new \InvalidArgumentException('The fingerprint should be a valid hexadecimal string.');
        }

        return $this->find()
            ->where([
                'user_id' => $userId,
                'fingerprint' => strtoupper($fingerprint),
            ])
            ->first();
    }

    /**
     * Build a Gpgkey entity from the armored key
     *
     * @param string $armoredKey ascii armored key
     * @param string $userId uuid of the user using the key
     * @throws \InvalidArgumentException if the user is not valid
     * @throws \App\Error\Exception\ValidationException if the key info can not be parsed
     * @return \App\Model\Entity\Gpgkey
     */
    public function buildEntityFromArmoredKey(string $armoredKey, string $userId): Gpgkey
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException('The user identifier should be a valid UUID.');
        }
        try {
            $info = PublicKeyValidationService::getPublicKeyInfo($armoredKey);
        } catch (\Exception $e) {
            throw new CustomValidationException(__('A valid OpenPGP key must be provided.'), [
                'armored_key' => [
                    'isParsable' => __('The OpenPGP armored key could not be parsed.'),
                ],
            ]);
        }

        $data = [
            'user_id' => $userId,
            'fingerprint' => $info['fingerprint'],
            'bits' => $info['bits'],
            'type' => $info['type'],
            'key_id' => $info['key_id'],
            'uid' => $info['uid'],
            'armored_key' => $armoredKey,
            'deleted' => false,
            'key_created' => new FrozenTime($info['key_created']),
            'expires' => null,
        ];

        if (!empty($info['expires'])) {
            $data['expires'] = new FrozenTime($info['expires']);
        }

        $gpgKey = $this->newEntity($data, ['accessibleFields' => [
            'user_id' => true,
            'fingerprint' => true,
            'bits' => true,
            'type' => true,
            'key_id' => true,
            'uid' => true,
            'armored_key' => true,
            'key_created' => true,
            'deleted' => true,
            'expires' => true,
        ]]);

        if ($gpgKey->getErrors()) {
            throw new ValidationException(__('The OpenPGP armored key could not be validated.'), $gpgKey, $this);
        }

        return $gpgKey;
    }

    /**
     * Custom validation rule to validate key id
     *
     * @param string $value fingerprint
     * @param array|null $context not in use
     * @return bool
     * @deprecated Use PublicKeyValidationService::isParsableArmoredPublicKey
     */
    public function isParsableArmoredPublicKey(string $value, ?array $context = null): bool
    {
        return PublicKeyValidationService::isParsableArmoredPublicKey($value);
    }

    /**
     * Return true if string is a valid fingerprint
     *
     * @deprecated use PublicKeyValidationService::isValidFingerprint();
     * @param string $value fingerprint
     * @return bool
     */
    public static function isValidFingerprint(string $value): bool
    {
        return PublicKeyValidationService::isValidFingerprint($value);
    }
}
