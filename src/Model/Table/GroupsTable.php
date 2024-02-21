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
use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\Group;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Rule\IsNotSoleOwnerOfSharedResourcesRule;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Groups\GroupsFindersTrait;
use App\Service\Secrets\SecretsFindSecretsAccessibleViaGroupOnlyService;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
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
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsToMany $Users
 * @property \App\Model\Table\GroupsUsersTable&\Cake\ORM\Association\HasMany $GroupsUsers
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Modifier
 * @method \App\Model\Entity\Group get($primaryKey, $options = [])
 * @method \App\Model\Entity\Group newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Group[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Group|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Group patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Group[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Group findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \App\Model\Table\GroupsUsersTable&\Cake\ORM\Association\HasOne $MyGroupUser
 * @property \App\Model\Table\PermissionsTable&\Cake\ORM\Association\HasMany $Permissions
 * @method \App\Model\Entity\Group newEmptyEntity()
 * @method \App\Model\Entity\Group saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\Group>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Group>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Group>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Group>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findById(string $id)
 * @method \Cake\ORM\Query findByIdAndGroupId(string $id, string $groupId)
 */
class GroupsTable extends Table
{
    use GroupsFindersTrait;
    use TableCleanupTrait;

    public const GROUP_CREATE_SUCCESS_EVENT_NAME = 'Model.Groups.create.success';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id',
        ]);
        $this->hasMany('GroupsUsers', [
            'saveStrategy' => 'replace',
        ]);
        $this->hasOne('MyGroupUser', [
            'className' => 'GroupsUsers',
        ]);
        $this->hasMany('Permissions', [
            'foreignKey' => 'aro_foreign_key',
        ]);
        $this->belongsToMany('Users', [
            'through' => 'GroupsUsers',
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
            ->utf8Extended('name', __('The name should be a valid UTF8 string.'))
            ->maxLength('name', 255, __('The name length should be maximum {0} characters.', 255))
            ->requirePresence('name', 'create', __('A name is required.'))
            ->allowEmptyString('name', __('The name should not be empty.'), false);

        $validator
            ->boolean('deleted', __('The deleted status should be a valid boolean.'))
            ->requirePresence('deleted', 'create', __('A deleted status is required'));

        $validator
            ->uuid('created_by', __('The identifier of the user who created the group should be a valid UUID.'))
            ->requirePresence(
                'created_by',
                'create',
                __('The identifier of the user who created the group is required.')
            )
            ->allowEmptyString(
                'created_by',
                __('The identifier of the user who created the group should not be empty.'),
                false
            );

        $validator
            ->uuid('modified_by', __('The identifier of the user who modified the group should be a valid UUID.'))
            ->requirePresence(
                'modified_by',
                true,
                __('The identifier of the user who modified the group is required.')
            )
            ->allowEmptyString(
                'modified_by',
                __('The identifier of the user who modified the group should not be empty.'),
                false
            );

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
        // Add create rules.
        $rules->addCreate(
            $rules->isUnique(
                ['name', 'deleted'],
                __('The name is already used by another group.')
            ),
            'group_unique'
        );
        $rules->addCreate([$this, 'atLeastOneAdminRule'], 'at_least_one_group_manager', [
            'errorField' => 'groups_users',
            'message' => __('A group manager should be provided.'),
        ]);

        // Update rules.
        $rules->addUpdate(
            $rules->isUnique(
                ['name', 'deleted'],
                __('The name is already used by another group.')
            ),
            'group_unique'
        );
        $rules->addUpdate(new IsNotSoftDeletedRule(), 'group_is_not_soft_deleted', [
            'table' => 'Groups',
            'errorField' => 'id',
            'message' => __('The group should not be soft deleted.'),
        ]);

        // Delete rules
        $msg = __('The group should not be sole owner of shared content, transfer the ownership to other users.');
        $rules->addDelete(new IsNotSoleOwnerOfSharedResourcesRule(), 'soleOwnerOfSharedContent', [
            'errorField' => 'id',
            'message' => $msg,
        ]);

        return $rules;
    }

    /**
     * Return a group entity.
     *
     * @param array $data entity data
     * @return \App\Model\Entity\Group
     */
    public function buildEntity(array $data): Group
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'name' => true,
                'created_by' => true,
                'modified_by' => true,
                'groups_users' => true,
                'deleted' => true,
            ],
            'associated' => [
                'GroupsUsers' => [
                    'validate' => 'saveGroup',
                    'accessibleFields' => [
                        'user_id' => true,
                        'is_admin' => true,
                    ],
                ],
            ],
        ]);
    }

    /**
     * Create a new group.
     *
     * @param array $data group data
     * @param \App\Utility\UserAccessControl $control access control details
     * @return \App\Model\Entity\Group
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function create(array $data, UserAccessControl $control): Group
    {
        // Manage defaults.
        $defaults = [
            'created_by' => $control->getId(),
            'modified_by' => $control->getId(),
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
            throw new InternalErrorException('Could not save the group, try again later.');
        }

        // Dispatch event.
        $eventData = ['group' => $groupSaved, 'requester' => $control];
        $event = new Event(static::GROUP_CREATE_SUCCESS_EVENT_NAME, $this, $eventData);
        $this->getEventManager()->dispatch($event);

        return $groupSaved;
    }

    /**
     * Validate that the a group can be created only if at least one admin is provided.
     *
     * @param \App\Model\Entity\Group $entity The entity that will be created.
     * @param array|null $options options
     * @return bool
     */
    public function atLeastOneAdminRule(Group $entity, ?array $options = []): bool
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
     * Using a service here would require vast refactoring in Active Directory.
     * It is however recommended refactoring this method within a service.
     *
     * @throws \InvalidArgumentException if $group is not a valid group entity
     * @param \App\Model\Entity\Group $group entity
     * @param array|null $options additional delete options such as ['checkRules' => true]
     * @return \App\Model\Dto\EntitiesChangesDto|bool The list of entities changes, false if a validation error occurred.
     * @see PasswordExpiryOnDeleteGroupEventListener::expireResourcesOnDeletedGroup
     */
    public function softDelete(Group $group, ?array $options = null)
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

        $entitiesChanges = new EntitiesChangesDto();

        // Delete the secrets group users will lose the access to.
        $groupUsersIds = $this->GroupsUsers->findByGroupId($group->id)
            ->select('user_id')->all()->extract('user_id')->toArray();
        $secretsFindSecretsAccessibleViaGroupOnlyService = new SecretsFindSecretsAccessibleViaGroupOnlyService();
        $secretsToDelete = $secretsFindSecretsAccessibleViaGroupOnlyService->find(
            $group->id,
            $groupUsersIds,
            PermissionsTable::RESOURCE_ACO
        )->select(['id', 'resource_id', 'user_id'])->all()->toArray();

        $this->Permissions->Resources->Secrets->deleteMany($secretsToDelete);
        $entitiesChanges->pushDeletedEntities($secretsToDelete);

        // find all the resources that only belongs to the group and mark them as deleted
        // Note: all resources that cannot be deleted should have been
        // transferred to other people already (ref. delete checkRules)
        $resourceIds = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $group->id)
            ->all()
            ->extract('aco_foreign_key')
            ->toArray();
        if (!empty($resourceIds)) {
            /** @var \App\Model\Table\ResourcesTable $Resources */
            $Resources = TableRegistry::getTableLocator()->get('Resources');
            $Resources->softDeleteAll($resourceIds);
        }

        if (Configure::read('passbolt.plugins.folders.enabled')) {
            // Find all the folders that only belongs to the deleted group and delete them.
            // Note: all folders that cannot be deleted should have been transferred to other people already.
            $foldersIds = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::FOLDER_ACO, $group->id)
                ->all()
                ->extract('aco_foreign_key')->toArray();
            if (!empty($foldersIds)) {
                $foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
                $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
                $foldersTable->deleteAll(['id IN' => $foldersIds]);
                $foldersRelationsTable
                    ->deleteAll(['foreign_id IN' => $foldersIds]);
                $foldersRelationsTable
                    ->updateAll(['folder_parent_id' => null], ['folder_parent_id IN ' => $foldersIds]);
            }
        }

        // Delete all group memberships
        $this->GroupsUsers->deleteAll(['group_id' => $group->id]);

        // Delete all permissions
        // Delete all the secrets that lost permissions in the process
        $this->Permissions->deleteAll(['aro_foreign_key' => $group->id]);

        // Mark group as deleted
        $group->deleted = true;
        if (!$this->save($group, ['checkRules' => false, 'validate' => false])) {
            $msg = __('Could not delete the group {0}, please try again later.', $group->name);
            throw new InternalErrorException($msg);
        }

        return $entitiesChanges;
    }

    /**
     * Delete all groups records with no members(groups_users).
     *
     * @param bool $dryRun false
     * @return int Number of affected records
     */
    public function cleanupWithNoMembers($dryRun = false)
    {
        return $this->cleanupHardDeleted('GroupsUsers', $dryRun);
    }
}
