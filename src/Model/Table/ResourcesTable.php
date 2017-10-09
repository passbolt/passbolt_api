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

use App\Model\Entity\Permission;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * Resources Model
 *
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasOne $Creator
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasOne $Modifier
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasMany $Secrets
 *
 * @method \App\Model\Entity\Resource get($primaryKey, $options = [])
 * @method \App\Model\Entity\Resource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Resource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Resource|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Resource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Resource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Resource findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ResourcesTable extends Table
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

        $this->setTable('resources');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('Permissions', [
            'foreignKey' => 'aco_foreign_key'
        ]);
        $this->hasMany('Secrets', [
            'foreignKey' => 'resource_id'
        ]);
        $this->hasOne('Creator', [
            'className' => 'Users',
            'bindingKey' => 'created_by',
            'foreignKey' => 'id'
        ]);
        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id'
        ]);
        $this->hasOne('Favorites', [
            'foreignKey' => 'foreign_id'
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
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('username')
            ->allowEmpty('username');

        $validator
            ->scalar('uri')
            ->allowEmpty('uri');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->boolean('deleted')
            ->requirePresence('deleted', 'create')
            ->notEmpty('deleted');

        $validator
            ->uuid('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');

        $validator
            ->uuid('modified_by')
            ->requirePresence('modified_by', 'create')
            ->notEmpty('modified_by');

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
        $rules->add($rules->isUnique(['username']));

        return $rules;
    }

    /**
     * Build the query that fetches data for resource index
     *
     * @param string $userId The user to get the resources for
     * @param array $options options
     * @throws \Exception if the userId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findIndex($userId, array $options = [])
    {
        if (!Validation::uuid($userId)) {
            throw new \Exception(__('The parameter userId should be a valid uuid.'));
        }

        $query = $this->find('all');

        // If contains Secrets.
        if (isset($options['contain']['secret'])) {
            $query->contain('Secrets', function($q) use ($userId) {
                return $q->where(['Secrets.user_id' => $userId]);
            });
        }

        // If contains creator.
        if (isset($options['contain']['creator'])) {
            $query->contain('Creator');
        }

        // If contains modifier.
        if (isset($options['contain']['modifier'])) {
            $query->contain('Modifier');
        }

        // If filtered by favorite.
        if (isset($options['filter']['is-favorite'])) {
            // Filter on the favorite resources.
            if ($options['filter']['is-favorite']) {
                $query->innerJoinWith('Favorites', function($q) use ($userId) {
                    return $q->where(['Favorites.user_id' => $userId]);
                });
            }
            // Filter out the favorite resources.
            else {
                $query->notMatching('Favorites', function($q) use ($userId) {
                    return $q->where(['Favorites.user_id' => $userId]);
                });
            }
        }

        // If contains favorite.
        if (isset($options['contain']['favorite'])) {
            $query->contain('Favorites', function($q) use ($userId) {
                return $q->where(['Favorites.user_id' => $userId]);
            });
        }

        // Filter on resources the user is allowed to access.
        $query->innerJoinWith('Permissions', function($q) use ($userId) {
            return $q
                ->where([
                    'Permissions.aro_foreign_key' => $userId,
                    'Permissions.type >=' => Permission::READ,
                ]);
            // @TODO Check group permissions
        });

        // Filter out deleted resources
        $query->where(['Resources.deleted' => false]);

        return $query;
    }


    /**
     * Build the query that fetches data for resource view
     *
     * @param string $userId The user to get the resources for
     * @param string $resourceId The resource to retrieve
     * @param array $options options
     * @throws \Exception if the resourceId parameter is not a valid uuid.
     * @return Query
     */
    public function findView($userId, $resourceId, array $options = [])
    {
        if (!Validation::uuid($userId)) {
            throw new \Exception(__('The parameter userId should be a valid uuid.'));
        }
        if (!Validation::uuid($resourceId)) {
            throw new \Exception(__('The parameter resourceId should be a valid uuid.'));
        }

        $query = $this->findIndex($userId, $options);
        $query->where(['Resources.id' => $resourceId]);

        return $query;
    }
}
