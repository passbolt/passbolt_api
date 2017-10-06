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

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Favorites Model
 *
 * @method \App\Model\Entity\Favorite get($primaryKey, $options = [])
 * @method \App\Model\Entity\Favorite newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Favorite[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Favorite|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Favorite patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Favorite[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Favorite findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FavoritesTable extends Table
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

        $this->setTable('favorites');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmpty('user_id');

        $validator
            ->inList('foreign_model', array('Resource'))
            ->requirePresence('foreign_model', 'create')
            ->notEmpty('foreign_model');

        $validator
            ->uuid('foreign_id')
            ->requirePresence('foreign_id', 'create')
            ->notEmpty('foreign_id');

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
        $rules->addCreate([$this, 'validateUserExists'], 'userExists', [
            'errorField' => 'user_id',
            'message' => 'The user does not exist.'
        ]);

        $rules->addCreate([$this, 'validateResourceExists'], 'resourceExists', [
            'errorField' => 'resource_id',
            'message' => 'The resource does not exist.'
        ]);

        $rules->addCreate([$this, 'validateFavoriteUnique'], 'favoriteExists', [
            'errorField' => 'id',
            'message' => 'The resource has already been marked as favorite.'
        ]);

        return $rules;
    }

    /**
     * Validate that the user exists and has not been deleted.
     *
     * @param \App\Model\Entity\Favorite $entity The entity that will be saved.
     * @param array $options
     * @return bool
     */
    public function validateUserExists($entity, $options) {
        $Users = TableRegistry::get('Users');
        $user = $Users->find('all')
            ->where([
                'Users.id' => $entity->user_id,
                'Users.deleted' => 0
            ])
            ->first();

        if (empty($user)) {
            return false;
        }

        return true;
    }

    /**
     * Validate that the resource exists and has not been deleted.
     *
     * @param \App\Model\Entity\Favorite $entity The entity that will be saved.
     * @param array $options
     * @return bool
     */
    public function validateResourceExists($entity, $options) {
        $Resources = TableRegistry::get('Resources');
        $resource = $Resources->find('all')
            ->where([
                'Resources.id' => $entity->foreign_id,
                'Resources.deleted' => 0
            ])
            ->first();

        if (empty($resource)) {
            return false;
        }

        return true;
    }

    /**
     * Validate that the resource is not yet marked as favorite for the user.
     *
     * @param \App\Model\Entity\Favorite $entity The entity that will be saved.
     * @param array $options
     * @return bool
     */
    public function validateFavoriteUnique($entity, $options) {
        $favorite = $this->find('all')
            ->where([
                'Favorites.user_id' => $entity->user_id,
                'Favorites.foreign_id' => $entity->foreign_id
            ])
            ->first();

        if (!empty($favorite)) {
            return false;
        }

        return true;
    }
}
