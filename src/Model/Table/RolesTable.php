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

use App\Model\Entity\Role;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Roles Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 * @method \App\Model\Entity\Role get($primaryKey, $options = [])
 * @method \App\Model\Entity\Role newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Role[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Role|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Role patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Role[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Role findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $ControllerLogs
 * @method \App\Model\Entity\Role newEmptyEntity()
 * @method \App\Model\Entity\Role saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\Role>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Role>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Role>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Role>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 */
class RolesTable extends Table
{
    public const ALLOWED_ROLE_NAMES = [Role::GUEST, Role::USER, Role::ADMIN];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('roles');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ControllerLogs', [
            'foreignKey' => 'role_id',
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'role_id',
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
            ->allowEmptyString('id', 'create');

        $validator
            ->ascii('name', __('The name should be a valid ASCII string.'))
            ->requirePresence('name', 'create')
            ->notEmptyString('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->utf8('description', __('The description should be a valid BMP-UTF8 string.'))
            ->allowEmptyString('description');

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
        $rules->add($rules->isUnique(['name'], __('A role already exists for the given name.')));

        return $rules;
    }

    /**
     * Check if a role name is valid
     *
     * @param string $roleName for example 'admin' or 'user'
     * @return bool true if whitelisted
     */
    public function isValidRoleName(string $roleName): bool
    {
        return in_array($roleName, self::ALLOWED_ROLE_NAMES);
    }

    /**
     * Get a role id by providing its name
     *
     * @param string $roleName such as "admin" or "user"
     * @throws \InvalidArgumentException if the role name is not whitelisted
     * @return string|null
     */
    public function getIdByName(string $roleName): ?string
    {
        if (!$this->isValidRoleName($roleName)) {
            $msg = __('The role name should be from the list of allowed role names.');
            throw new \InvalidArgumentException($msg);
        }
        $role = $this->find('all')
            ->where(['name' => $roleName])
            ->first();

        if (empty($role)) {
            return null;
        }

        return $role->get('id');
    }
}
