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

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Cake\Network\Exception\InternalErrorException;

/**
 * AuthenticationTokens Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\AuthenticationToken get($primaryKey, $options = [])
 * @method \App\Model\Entity\AuthenticationToken newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AuthenticationToken patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AuthenticationToken findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AuthenticationTokensTable extends Table
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

        $this->setTable('authentication_tokens');
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
            ->scalar('token')
            ->requirePresence('token', 'create')
            ->notEmpty('token');

        $validator
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        return $validator;
    }

    /**
     * Register validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationRegister(Validator $validator)
    {
        return self::validationDefault($validator);
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
     * Build the authentication token
     *
     * @param $userId
     * @return \App\Model\Entity\AuthenticationToken
     */
    public function generate($userId)
    {
        $token = $this->newEntity([
            'user_id' => $userId,
            'token' => UuidFactory::uuid(),
            'active' => true
        ]);
        if (!$this->save($token, ['checkRules' => false, 'atomic' => false])) {
            throw new InternalErrorException(__('The authentication token could not be saved.'));
        }

        return $token;
    }

    /**
     * Check if a token exist and is valid for a given user.
     *
     * A valid token :
     *  - belongs to the given user &&
     *  - is active &&
     *  - is not expired ;
     *
     * @param string $tokenId uuid of the token to check
     * @param string $userId uuid of the user
     * @return bool true if it is valid
     */
    public function isValid($tokenId, $userId)
    {
        // Are ids valid uuid?
        if (!Validation::uuid($tokenId) || !Validation::uuid($userId)) {
            return false;
        }

        // Does token exist?
        $token = $this->find('all')
            ->where(['token' => $tokenId, 'user_id' => $userId, 'active' => true ])
            ->first();
        if (empty($token)) {
            return false;
        }

        // Is it expired?
        $valid = $token->created->wasWithinLast(Configure::read('passbolt.auth.tokenExpiry'));

        if (!$valid) {
            // update the token to inactive
            $token->active = false;
            $this->save($token);

            return false;
        }

        return true;
    }

    /**
     * Set a token as inactive
     *
     * @param $tokenId
     */
    public function setInactive($tokenId)
    {
        $token = $this->find('all')
            ->where(['token' => $tokenId, 'active' => true ])
            ->first();

        $token->active = false;
        $this->save($token);
    }
}
