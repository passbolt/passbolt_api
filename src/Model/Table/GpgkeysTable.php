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

use App\Error\Exception\ValidationException;
use App\Model\Entity\Gpgkey;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Chronos\ChronosInterface;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
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
 * @method \App\Model\Entity\Gpgkey[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Gpgkey[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Gpgkey[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Gpgkey[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
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
            ->add('armored_key', ['custom' => [
                'rule' => [$this, 'isParsableArmoredPublicKey'],
                'message' => __('The armored key should be a valid ASCII-armored OpenPGP key.'),
            ]]);

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
            ->add('key_id', ['custom' => [
                'rule' => [$this, 'isValidKeyIdRule'],
                'message' => __('The key identifier should be a string of 8 hexadecimal characters.'),
            ]]);

        $validator
            ->ascii('fingerprint', __('The fingerprint should be a valid ASCII string.'))
            ->requirePresence('fingerprint', 'create', __('A fingerprint is required'))
            ->notEmptyString('fingerprint', __('The fingerprint should not be empty'))
            ->add('fingerprint', ['custom' => [
                'rule' => [$this, 'isValidFingerprintRule'],
                'message' => __('The fingerprint should be a string of 40 hexadecimal characters.'),
            ]]);

        $validator
            ->ascii('type', __('The type should be a valid ASCII string.'))
            ->requirePresence('type', 'create', __('A type is required'))
            ->notEmptyString('type', __('The type should not be empty'))
            ->add('type', ['custom' => [
                'rule' => [$this, 'isValidKeyTypeRule'],
                'message' => __('The type should be one of the following: RSA, DSA, ECC, ELGAMAL, ECDSA, DH.'),
            ]]);

        $validator
            ->dateTime('expires', ['ymd'], __('The expiry should be a valid date.'))
            ->allowEmptyDateTime('expires')
            ->add('expires', ['custom' => [
                'rule' => [$this, 'isInFutureRule'],
                'message' => __('The key should not already be expired.'),
            ]]);

        $validator
            ->dateTime('key_created', ['ymd'], __('The creation date should be a valid date.'))
            ->requirePresence('key_created', 'create', __('A creation date is required.'))
            ->notEmptyDateTime('key_created', __('The creation date should not be empty.'))
            ->add('key_created', ['custom' => [
                'rule' => [$this, 'isInFuturePastRule'],
                'message' => __('The creation date should be set in the past.'),
            ]]);

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

        return $rules;
    }

    /**
     * Custom validation rule to validate fingerprint
     *
     * @param string $value fingerprint
     * @param array|null $context not in use
     * @return bool
     */
    public function isValidFingerprintRule(string $value, ?array $context = null): bool
    {
        return self::isValidFingerprint($value);
    }

    /**
     * Return true if string is a valid fingerprint
     *
     * @param string $value fingerprint
     * @return bool
     */
    public static function isValidFingerprint(string $value): bool
    {
        return preg_match('/^[A-F0-9]{40}$/', $value) === 1;
    }

    /**
     * Custom validation rule to validate key id
     *
     * @param string $value fingerprint
     * @param array|null $context not in use
     * @return bool
     */
    public function isValidKeyIdRule(string $value, ?array $context = null): bool
    {
        return preg_match('/^[A-F0-9]{8,16}$/', $value) === 1;
    }

    /**
     * Custom validation rule to validate key id
     *
     * @param string $value fingerprint
     * @param array|null $context not in use
     * @return bool
     */
    public function isParsableArmoredPublicKey(string $value, ?array $context = null)
    {
        $gpg = OpenPGPBackendFactory::get();

        return $gpg->isParsableArmoredPublicKey($value);
    }

    /**
     * Check if a key date is set in the past... tomorrow!
     *
     * In a ideal world we should check if a key date is set in the past from 'now'
     * where now is the time of reference of the server. But in practice we
     * allow a next day margin because users had the issue of having keys generated
     * by systems that were ahead of server time. Refs. PASSBOLT-1505.
     *
     * @param \Cake\Chronos\ChronosInterface $value Cake Datetime
     * @return bool
     */
    public function isInFuturePastRule(ChronosInterface $value): bool
    {
        $nowWithMargin = Time::now()->modify('+12 hours');

        return $value->lt($nowWithMargin);
    }

    /**
     * Check if a key date is set in the future
     * Used to check key expiry date
     *
     * @param \Cake\Chronos\ChronosInterface $value Cake Datetime
     * @return bool
     */
    public function isInFutureRule(ChronosInterface $value)
    {
        return $value->gt(FrozenTime::now());
    }

    /**
     * Custom validation rule to validate key type
     *
     * @param string $value fingerprint
     * @param array|null $context not in use
     * @return bool
     */
    public function isValidKeyTypeRule(string $value, ?array $context = null): bool
    {
        foreach (\OpenPGP_PublicKeyPacket::$algorithms as $i => $algorithm) {
            if ($value === $algorithm) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for valid email inside OpenPGP key UID
     *
     * @param string $value gpg key uid
     * @param array|null $context not in use
     * @return bool
     */
    public function uidContainValidEmailRule(string $value, ?array $context = null): bool
    {
        $match = $this->pregMatchEmail($value);

        return Validation::email($match, Configure::read('passbolt.email.validate.mx'));
    }

    /**
     * Extract email from a string
     *
     * @param string|null $value string to preg match
     * @return string|null the email matched or null if no email was found.
     */
    public function pregMatchEmail(?string $value): ?string
    {
        if (!isset($value)) {
            return null;
        }
        preg_match('/<(\S+@\S+)>$/', $value, $matches);

        return $matches[1] ?? null;
    }

    /**
     * Build the query that fetches data for user index
     *
     * @param \Cake\ORM\Query $query a query instance
     * @param array $options options
     * @throws \Cake\Core\Exception\Exception if no role is specified
     * @return \Cake\ORM\Query
     */
    public function findIndex(Query $query, array $options): Query
    {
        if (isset($options['filter']['modified-after'])) {
            $modified = new FrozenTime($options['filter']['modified-after']);
            $query->where(['modified >' => $modified->i18nFormat('yyyy-MM-dd HH:mm:ss')]);
        }

        $query->where(['deleted' => $options['filter']['is-deleted'] ?? false]);

        return $query;
    }

    /**
     * Find view
     *
     * @param \Cake\ORM\Query $query a query instance
     * @param array $options options
     * @throws \Cake\Core\Exception\Exception if no id is specified
     * @return \Cake\ORM\Query
     */
    public function findView(Query $query, array $options): Query
    {
        // Options must contain an id
        if (!isset($options['id'])) {
            throw new Exception('Gpgkey table findView should have an id set in options.');
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
        if (!$this->isValidFingerprintRule($fingerprint)) {
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
            $gpg = OpenPGPBackendFactory::get();
            $info = $gpg->getPublicKeyInfo($armoredKey);
        } catch (Exception $e) {
            throw new ValidationException(__('Could not parse the key info.'));
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
        $gpgkey = $this->newEntity($data, ['accessibleFields' => ['*' => true]]);

        return $gpgkey;
    }
}
