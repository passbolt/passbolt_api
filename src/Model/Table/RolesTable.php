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
use App\Model\Rule\IsUniqueCaseInsensitive;
use App\Model\Rule\Role\HasNoActiveUserAssociatedRule;
use App\Model\Rule\Role\IsReservedRoleRule;
use App\Model\Rule\Role\MaximumNumberOfRolesAllowedRule;
use ArrayObject;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Roles Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 * @method \App\Model\Entity\Role get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Role newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Role[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Role|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Role patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Role[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Role findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
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
    public const DEFAULT_ROLE_NAMES = [Role::GUEST, Role::USER, Role::ADMIN];

    public const RESERVED_ROLE_NAMES = [Role::GUEST, Role::USER, Role::ADMIN, Role::ROOT];

    public const MAXIMUM_NO_OF_ROLES_ALLOWED = 5;

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

        $this->hasMany('ControllerLogs', [ // @phpstan-ignore-line
            'foreignKey' => 'role_id',
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'role_id',
        ]);
    }

    /**
     * Trim whitespace from the role name before validation.
     *
     * @param \Cake\Event\EventInterface $event Event instance.
     * @param \ArrayObject $data Data to be marshaled.
     * @param \ArrayObject $options Options.
     * @return void
     */
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void
    {
        if (isset($data['name']) && is_string($data['name'])) {
            $data['name'] = trim($data['name']);
        }
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
            ->utf8('name', __('The name should be a valid BMP-UTF8 string.'))
            ->requirePresence('name', 'create')
            ->notEmptyString('name')
            ->maxLength('name', 50, __('The name should not be greater than 50 characters.'))
            ->add('name', 'reservedRole', [
                'rule' => [$this, 'isReservedRole'],
                'message' => __('The name should not be reserved role.'),
            ])
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
        $rules->add(new HasNoActiveUserAssociatedRule(), 'hasNoActiveUserAssociatedRule', [
            'errorField' => 'id',
            'message' => __('The role cannot be deleted as it is associated with another user.'),
        ]);
        $rules->add(new IsReservedRoleRule(), 'isReservedRole', [
            'errorField' => 'name',
            'message' => __('A reserved role cannot be deleted.'),
        ]);
        $rules->add(new IsUniqueCaseInsensitive(), 'unique', [
            'table' => 'Roles',
            'errorField' => 'name',
            'checkDirty' => true,
            'message' => __('A role already exists for the given name.'),
        ]);

        $rules->addCreate(new MaximumNumberOfRolesAllowedRule(), 'maximumNumberOfRolesAllowed', [
            'errorField' => 'name',
            'message' => __('Only maximum of {0} active roles are allowed.', self::MAXIMUM_NO_OF_ROLES_ALLOWED),
        ]);
        $rules->addCreate($rules->existsIn('created_by', 'Users'), 'creator_exists', ['allowNullableNulls' => true]);
        $rules->addCreate($rules->existsIn('modified_by', 'Users'), 'modifier_exists', ['allowNullableNulls' => true]);

        $rules->addUpdate($rules->existsIn('modified_by', 'Users'), 'modifier_exists', ['allowNullableNulls' => true]);
        $rules->addUpdate($rules->existsIn('deleted_by', 'Users'), 'remover_exists', ['allowNullableNulls' => true]);

        return $rules;
    }

    /**
     * Checks if given value is from reserved role.
     *
     * @param mixed $value Value to check.
     * @param array $context Data.
     * @return bool
     */
    public function isReservedRole(mixed $value, array $context): bool
    {
        return !in_array($value, self::RESERVED_ROLE_NAMES);
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
        $role = $this->find('all')
            ->where(['name' => $roleName])
            ->first();

        if (empty($role)) {
            return null;
        }

        return $role->get('id');
    }

    /**
     * @param \Cake\ORM\Query $query The Query.
     * @param array $options Array of options.
     * @return \Cake\ORM\Query
     */
    public function findIndex(Query $query, array $options): Query
    {
        /**
         * Filter
         */
        if (!empty($options['filter']['is-deleted'])) {
            $query = $query->where(function (QueryExpression $exp) {
                return $exp->isNotNull($this->aliasField('deleted'));
            });
        } else {
            $query = $query->find('notDeleted');
        }

        /**
         * Contain
         */
        if (!empty($options['contain']['user_count'])) {
            // Count the members of the groups in a subquery.
            $subQuery = $this->getAssociation('Users')->find();
            $subQuery
                ->select(['count' => $subQuery->func()->count('*')])
                ->where([
                    'Users.role_id' => new IdentifierExpression('Roles.id'),
                    // Only return active users count
                    'Users.deleted' => false,
                ]);

            // Add the user_count field to the Groups query.
            $query = $query->select(['user_count' => $subQuery])->enableAutoFields();
        }

        return $query;
    }

    /**
     * @param \Cake\ORM\Query $query query
     * @return \Cake\ORM\Query
     */
    public function findNotDeleted(Query $query): Query
    {
        return $query->where(function (QueryExpression $exp) {
            return $exp->isNull($this->aliasField('deleted'));
        });
    }
}
