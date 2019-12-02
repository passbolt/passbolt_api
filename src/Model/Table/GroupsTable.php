<?php
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
use App\Model\Entity\Group;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Rule\IsNotSoleOwnerOfSharedResourcesRule;
use App\Model\Traits\Groups\GroupsFindersTrait;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * Groups Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsToMany $Users
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $GroupsUsers
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasOne $Modifier
 *
 * @method \App\Model\Entity\Group get($primaryKey, $options = [])
 * @method \App\Model\Entity\Group newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Group[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Group|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Group patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Group[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Group findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GroupsTable extends Table
{
    use GroupsFindersTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id'
        ]);
        $this->hasMany('GroupsUsers', [
            'saveStrategy' => 'replace'
        ]);
        $this->hasOne('MyGroupUser', [
            'className' => 'GroupsUsers'
        ]);
        $this->hasMany('Permissions', [
            'foreignKey' => 'aro_foreign_key'
        ]);
        $this->belongsToMany('Users', [
            'through' => 'GroupsUsers'
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->utf8Extended('name', __('The name is not a valid utf8 string.'))
            ->lengthBetween('name', [0, 255], __('The name length should be maximum {0} characters.', 255))
            ->requirePresence('name', 'create', __('A name is required.'))
            ->allowEmptyString('name', __('The name cannot be empty.'), false);

        $validator
            ->boolean('deleted')
            ->requirePresence('deleted', 'create');

        $validator
            ->uuid('created_by')
            ->requirePresence('created_by', 'create')
            ->allowEmptyString('created_by', null, false);

        $validator
            ->uuid('modified_by')
            ->requirePresence('modified_by', true, __('Modified by field is required.'))
            ->allowEmptyString('modified_by', null, false);

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
        // Add create rules.
        $rules->addCreate(
            $rules->isUnique(
                ['name', 'deleted'],
                __('The name provided is already used by another group.')
            ),
            'group_unique'
        );
        $rules->addCreate([$this, 'atLeastOneAdminRule'], 'at_least_one_admin', [
            'errorField' => 'groups_users',
            'message' => __('A group manager must be provided.')
        ]);

        // Update rules.
        $rules->addUpdate(
            $rules->isUnique(
                ['name', 'deleted'],
                __('The name provided is already used by another group.')
            ),
            'group_unique'
        );
        $rules->addUpdate([$this, 'atLeastOneAdminRule'], 'at_least_one_admin', [
            'errorField' => 'groups_users',
            'message' => __('A group manager must be provided.')
        ]);
        $rules->addUpdate(new IsNotSoftDeletedRule(), 'group_is_not_soft_deleted', [
            'table' => 'Groups',
            'errorField' => 'id',
            'message' => __('The group cannot be soft deleted.')
        ]);

        // Delete rules
        $rules->addDelete(new IsNotSoleOwnerOfSharedResourcesRule(), 'soleOwnerOfSharedResource', [
            'errorField' => 'id',
            'message' => __('You need to transfer the ownership for the shared passwords owned by this user before deleting this user.')
        ]);

        return $rules;
    }

    /**
     * Return a group entity.
     * @param array $data entity data
     *
     * @return \App\Model\Entity\Group
     */
    public function buildEntity(array $data)
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'name' => true,
                'created_by' => true,
                'modified_by' => true,
                'groups_users' => true,
                'deleted' => true
            ],
            'associated' => [
                'GroupsUsers' => [
                    'validate' => 'saveGroup',
                    'accessibleFields' => [
                        'user_id' => true,
                        'is_admin' => true
                    ]
                ],
            ]
        ]);
    }

    /**
     * Create a new group.
     *
     * @param array $data group data
     * @param UserAccessControl $control access control details
     *
     * @return \App\Model\Entity\Group|bool
     * @throws ValidationException
     * @throws InternalErrorException
     */
    public function create(array $data, UserAccessControl $control)
    {
        // Manage defaults.
        $defaults = [
            'created_by' => $control->userId(),
            'modified_by' => $control->userId(),
            'deleted' => false,
        ];
        $data = array_merge($defaults, $data);

        // Check validation rules.
        $group = $this->buildEntity($data);
        if (!empty($group->getErrors())) {
            throw new ValidationException(__('Could not validate group data.'), $group, $this);
        }

        $groupSaved = $this->save($group);

        // Check for validation errors. (associated models too).
        if (!empty($group->getErrors())) {
            throw new ValidationException(__('Could not validate group data.'), $group, $this);
        }

        // Check for errors while saving.
        if (!$groupSaved) {
            throw new InternalErrorException(__('The group could not be saved.'));
        }

        // Dispatch event.
        $eventData = ['group' => $groupSaved, 'requester' => $control];
        $event = new Event('Model.Groups.create.success', $this, $eventData);
        $this->getEventManager()->dispatch($event);

        return $groupSaved;
    }

    /**
     * Validate that the a group can be created only if at least one admin is provided.
     *
     * @param \App\Model\Entity\Group $entity The entity that will be created.
     * @param array $options options
     * @return bool
     */
    public function atLeastOneAdminRule(Group $entity, array $options = [])
    {
        $adminUsers = [];
        if (isset($entity->groups_users)) {
            $adminUsers = Hash::extract($entity->groups_users, '{n}[is_admin=true]');
        }

        return !empty($adminUsers);
    }

    /**
     * Soft delete a group and their associated items
     * Mark group as deleted = true
     * Mark all the group resources only associated with this group as deleted = true
     * Delete all UserGroups association entries
     * Delete all Permissions associated with this group
     *
     * @throws \InvalidArgumentException if $group is not a valid group entity
     * @param \App\Model\Entity\Group $group entity
     * @param array $options additional delete options such as ['checkRules' => true]
     * @return bool status
     */
    public function softDelete(Group $group, array $options = null)
    {
        // Check the delete rules like a normal operation
        if (!isset($options['checkRules'])) {
            $options['checkRules'] = true;
        }
        if ($options['checkRules']) {
            if (!$this->checkRules($group, RulesChecker::DELETE)) {
                return false;
            }
        }

        // find all the resources that only belongs to the group and mark them as deleted
        // Note: all resources that cannot be deleted should have been
        // transferred to other people already (ref. delete checkRules)
        $Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $resourceIds = $Permissions->findResourcesOnlyGroupCanAccess($group->id)->extract('aco_foreign_key')->toArray();
        if (!empty($resourceIds)) {
            $Resources = TableRegistry::getTableLocator()->get('Resources');
            $Resources->softDeleteAll($resourceIds);
        }

        // Delete all group memberships
        $this->GroupsUsers->deleteAll(['group_id' => $group->id]);

        // Delete all permissions
        // Delete all the secrets that lost permissions in the process
        $Permissions->deleteAll(['aro_foreign_key' => $group->id]);
        $Secrets = TableRegistry::getTableLocator()->get('Secrets');
        $Secrets->cleanupHardDeletedPermissions();

        // Mark group as deleted
        $group->deleted = true;
        if (!$this->save($group, ['checkRules' => false, 'validate' => false])) {
            $msg = __('Could not delete the group {0}, please try again later.', $group->name);
            throw new InternalErrorException($msg);
        }

        return true;
    }
}
