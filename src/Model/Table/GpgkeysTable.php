<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Model\Table;

use App\Error\Exception\ValidationRuleException;
use App\Model\Entity\Gpgkey;
use App\Utility\Gpg;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use JsonSchema\Exception\ValidationException;
use Cake\Core\Exception\Exception;

/**
 * Gpgkeys Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Gpgkey get($primaryKey, $options = [])
 * @method \App\Model\Entity\Gpgkey newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Gpgkey[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Gpgkey|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Gpgkey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Gpgkey[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Gpgkey findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GpgkeysTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('gpgkeys');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('armored_key')
            ->requirePresence('armored_key', 'create')
            ->notEmpty('armored_key');

        $validator
            ->integer('bits')
            ->allowEmpty('bits');

        $validator
            ->scalar('uid')
            ->requirePresence('uid', 'create')
            ->notEmpty('uid');

        $validator
            ->scalar('key_id')
            ->requirePresence('key_id', 'create')
            ->notEmpty('key_id');

        $validator
            ->scalar('fingerprint')
            ->requirePresence('fingerprint', 'create')
            ->notEmpty('fingerprint');

        $validator
            ->scalar('type')
            ->allowEmpty('type');

        $validator
            ->dateTime('expires')
            ->allowEmpty('expires');

        $validator
            ->dateTime('key_created')
            ->allowEmpty('key_created');

        $validator
            ->boolean('deleted')
            ->requirePresence('deleted', 'create')
            ->notEmpty('deleted');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * Custom validation rule to validate fingerprint
     *
     * @param string $value fingerprint
     * @param array $context not in use
     * @return bool
     */
    public function isValidFingerprint($value, array $context = null)
    {
        return (preg_match('/^[A-F0-9]{40}$/', $value) === 1);
    }

    /**
     * Check for valid email inside GPG key UID
     *
     * @param string $value gpg key uid
     * @param array $context not in use
     * @return bool
     */
    public function uidContainValidEmail($value, array $context = null)
    {
        preg_match('/<(\S+@\S+)>$/', $value, $matches);
        if (isset($matches[1])) {
            return Validation::email($matches[1]);
        }

        return false;
    }

    /**
     * Build the query that fetches data for user index
     *
     * @param Query $query a query instance
     * @param array $options options
     * @throws Exception if no role is specified
     * @return Query
     */
    public function findIndex(Query $query, array $options)
    {
        $query->where(['deleted' => false]);
        if (isset($options['filter']['modified-after'])) {
            $modified = date('Y-m-d H:i:s', $options['filter']['modified-after']);
            $query->where(['modified >' => $modified]);
        }

        return $query;
    }

    /**
     * Find view
     *
     * @param Query $query a query instance
     * @param array $options options
     * @throws Exception if no id is specified
     * @return Query
     */
    public function findView(Query $query, array $options)
    {
        // Options must contain an id
        if (!isset($options['id'])) {
            throw new Exception(__('Gpgkey table findView should have an id set in options.'));
        }
        // Same rule than index apply
        // with a specific id requested
        $query = $this->findIndex($query, $options);
        $query->where(['id' => $options['id']]);

        return $query;
    }

    /**
     * Build a Gpgkey entity from the armored key
     *
     * @param string $armoredKey ascii armored key
     * @param string $userId uuid of the user using the key
     * @throws ValidationRuleException if the key info can not be parsed
     * @return object Gpgkey entity
     */
    public function buildEntityFromArmoredKey($armoredKey, $userId)
    {
        try {
            $gpg = new Gpg();
            $info = $gpg->getKeyInfo($armoredKey);
        } catch (Exception $e) {
            throw new ValidationException(__('Could not create Gpgkey from armored key.'));
        }

        $data = [
            'user_id' => $userId,
            'fingerprint' => $info['fingerprint'],
            'bits' => $info['bits'],
            'type' => $info['type'],
            'key_id' => $info['key_id'],
            'uid' => $info['uid'],
            'armored_key' => $armoredKey,
            'deleted' => false
        ];
        if (!empty($info['expires'])) {
            $data['expires'] = new FrozenTime($info['expires']);
        }
        if (!empty($info['key_created'])) {
            $data['key_created'] = new FrozenTime($info['expires']);
        }
        $gpgkey = $this->newEntity($data, ['accessibleFields' => ['*' => true]]);

        // No need to check rules if basic validation fails
        if ($gpgkey->getErrors()) {
            return $gpgkey;
        }
        $this->checkRules($gpgkey);

        return $gpgkey;
    }
}
