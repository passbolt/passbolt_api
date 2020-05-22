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

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Resources\ResourcesFindersTrait;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * Resources Model
 *
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasOne $Creator
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasOne $Modifier
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasMany $Secrets
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasOne $Permissions
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
    use ResourcesFindersTrait;

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

        $this->hasOne('Creator', [
            'className' => 'Users',
            'bindingKey' => 'created_by',
            'foreignKey' => 'id',
        ]);
        $this->hasOne('Favorites', [
            'foreignKey' => 'foreign_key',
        ]);
        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id',
        ]);
        $this->hasOne('Permission', [
            'className' => 'Permissions',
            'foreignKey' => 'aco_foreign_key',
        ]);
        $this->hasMany('Permissions', [
            'foreignKey' => 'aco_foreign_key',
            'saveStrategy' => 'replace',
            // Important so that we can track the delete event and log it.
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('Secrets', [
            'foreignKey' => 'resource_id',
            'saveStrategy' => 'replace',
        ]);

        if (Configure::read('passbolt.plugins.tags.enabled')) {
            $this->belongsToMany('Tags', [
                'through' => 'Passbolt/Tags.ResourcesTags',
            ]);
        }

        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $this->hasMany('FoldersRelations', [
                'className' => 'FoldersRelations',
                'foreignKey' => 'foreign_id',
                'conditions' => [
                    'FoldersRelations.foreign_model' => 'Resource',
                ],
                'dependent' => true,
            ]);
        }
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
            ->maxLength('name', 64, __('The name length should be maximum {0} characters.', 64))
            ->requirePresence('name', 'create', __('A name is required.'))
            ->allowEmptyString('name', __('The name cannot be empty.'), false);

        $validator
            ->utf8Extended('username', __('The username is not a valid utf8 string.'))
            ->maxLength('username', 64, __('The username length should be maximum {0} characters.', 64))
            ->allowEmptyString('username');

        $validator
            ->utf8('uri', __('The uri is not a valid utf8 string (emoticons excluded).'))
            ->maxLength('uri', 1024, __('The uri length should be maximum {0} characters.', 1024))
            ->allowEmptyString('uri');

        $validator
            ->utf8Extended('description', __('The description is not a valid utf8 string.'))
            ->maxLength('description', 10000, __('The description length should be maximum {0} characters.', 10000))
            ->allowEmptyString('description');

        $validator
            ->boolean('deleted')
            ->allowEmptyString('deleted', null, false);

        $validator
            ->uuid('created_by')
            ->requirePresence('created_by', 'create')
            ->allowEmptyString('created_by', null, false);

        $validator
            ->uuid('modified_by')
            ->requirePresence('modified_by', 'create')
            ->allowEmptyString('modified_by', null, false);

        // Associated fields
        $validator
            ->requirePresence('permissions', 'create', __('The permissions are required.'))
            ->allowEmptyString('permissions', __('The permissions cannot be empty.'), false)
            ->hasAtMost('permissions', 1, __('Only the permission of the owner must be provided.'), 'create');

        $validator
            ->requirePresence('secrets', 'create', __('A secret is required.'))
            ->allowEmptyString('secrets', __('The secret cannot be empty.'), false)
            ->hasAtMost('secrets', 1, __('Only the secret of the owner must be provided.'), 'create');

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
        // Create rules.
        $rules->addCreate([$this, 'isOwnerPermissionProvidedRule'], 'owner_permission_provided', [
            'errorField' => 'permissions',
            'message' => __('At least one owner permission must be provided.'),
        ]);
        $rules->addCreate([$this, 'isOwnerSecretProvidedRule'], 'owner_secret_provided', [
            'errorField' => 'secrets',
            'message' => __('The secret of the owner is required.'),
        ]);

        // Update rules.
        $rules->addUpdate([$this, 'isSecretsProvidedRule'], 'secrets_provided', [
            'errorField' => 'secrets',
            'message' => __('The secrets of all the users having access to the resource are required.'),
        ]);
        $rules->addUpdate(new IsNotSoftDeletedRule(), 'resource_is_not_soft_deleted', [
            'table' => 'Resources',
            'errorField' => 'id',
            'message' => __('The resource cannot be soft deleted.'),
        ]);
        $rules->addUpdate([$this, 'isOwnerPermissionProvidedRule'], 'at_least_one_owner', [
            'errorField' => 'permissions',
            'message' => __('At least one owner permission must be provided.'),
        ]);

        return $rules;
    }

    /**
     * Validate that the entity has at least one owner
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created or updated.
     * @param array $options options
     * @return bool
     */
    public function isOwnerPermissionProvidedRule($entity, array $options = [])
    {
        if (isset($entity->permissions)) {
            $found = Hash::extract($entity->permissions, '{n}[type=' . Permission::OWNER . ']');
            if (empty($found)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate that the a resource can be created only if the secret of the owner is provided.
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created.
     * @param array $options options
     * @return bool
     */
    public function isOwnerSecretProvidedRule(\App\Model\Entity\Resource $entity, array $options = [])
    {
        return ($entity->secrets[0]->user_id === $entity->created_by);
    }

    /**
     * Validate that the secrets of all the allowed users are provided if the secret changed.
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created.
     * @param array $options options
     * @return bool
     */
    public function isSecretsProvidedRule(\App\Model\Entity\Resource $entity, array $options = [])
    {
        // Secrets are not required to update a resource, but if provided check that the list of secrets correspond
        // only to the users who have access to the resource.
        if (!isset($entity->secrets)) {
            return true;
        }

        // Retrieve the users who are allowed to access the resource.
        $Users = TableRegistry::getTableLocator()->get('Users');
        $usersFindOptions['filter']['has-access'] = [$entity->id];
        $allowedUsersIds = $Users->findIndex(Role::USER, $usersFindOptions)
            ->extract('id')
            ->toArray();

        // Extract the users for whom the secrets will be updated.
        $secretsUsersIds = Hash::extract($entity->secrets, '{n}.user_id');

        // If the list of secrets does not correspond to the list of users who have access to the resource,
        // do not validate.
        if (
            count($secretsUsersIds) != count($allowedUsersIds)
            || !empty(array_diff($allowedUsersIds, $secretsUsersIds))
        ) {
            return false;
        }

        return true;
    }

    /**
     * Soft delete a resource.
     *
     * @param string $userId The user who perform the delete.
     * @param \App\Model\Entity\Resource $resource The resource to delete.
     * @throws \InvalidArgumentException if the user id is not a uuid
     * @return bool true if success
     */
    public function softDelete(string $userId, \App\Model\Entity\Resource $resource)
    {
        // The softDelete will perform an update to the entity to soft delete it.
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }
        if ($resource->deleted) {
            $resource->setError('deleted', [
                'is_not_soft_deleted' => __('The resource cannot be soft deleted.'),
            ]);

            return false;
        }

        if (!$this->Permissions->hasAccess(PermissionsTable::RESOURCE_ACO, $resource->id, $userId, Permission::UPDATE)) {
            $resource->setError('id', [
                'has_access' => __('The user cannot delete this resource.'),
            ]);

            return false;
        }

        // Patch the entity.
        $data = [
            'deleted' => true,
            'modified_by' => $userId,
            'secrets' => [],
        ];
        $patchOptions = [
            'accessibleFields' => [
                'deleted' => true,
                'secrets' => true,
                'modified' => true,
                'modified_by' => true,
            ],
        ];
        $this->patchEntity($resource, $data, $patchOptions);
        if ($resource->getErrors()) {
            return false;
        }

        // Soft delete the resource.
        $this->save($resource, ['checkRules' => false]);
        if ($resource->getErrors()) {
            return false;
        }

        // Remove all the associated permissions.
        $this->getAssociation('Permissions')
            ->deleteAll(['Permissions.aco_foreign_key' => $resource->id]);

        // Remove all the associated favorites.
        $this->getAssociation('Favorites')
            ->deleteAll(['Favorites.foreign_key' => $resource->id]);

        // Delete all tags
        if (Configure::read('passbolt.plugins.tags.enabled')) {
            $ResourcesTags = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
            $ResourcesTags->deleteAll(['resource_id' => $resource->id]);
            $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
            $Tags->deleteAllUnusedTags();
        }

        // Notify other components about the resource soft delete.
        $event = new Event('Model.Resource.afterSoftDelete', $resource);
        $this->getEventManager()->dispatch($event);

        return true;
    }

    /**
     * Remove the resource associated data for the users who lost access to the resource.
     *
     * @param string $resourceId The resource identifier the users lost the access to
     * @param array $usersId The list of users who lost access to the resource
     * @return void
     */
    public function deleteLostAccessAssociatedData($resourceId, array $usersId = [])
    {
        if (empty($usersId)) {
            return;
        }

        $Favorites = TableRegistry::getTableLocator()->get('Favorites');
        $Favorites->deleteAll([
            'foreign_key' => $resourceId,
            'user_id IN' => $usersId,
        ]);

        if (Configure::read('passbolt.plugins.tags.enabled')) {
            $ResourcesTags = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
            $ResourcesTags->deleteAll([
                'resource_id' => $resourceId,
                'user_id IN' => $usersId,
            ]);
            $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
            $Tags->deleteAllUnusedTags();
        }
    }

    /**
     * Soft delete a list of resources by Ids
     *
     * @param string $resourceIds uuid of Resources
     * @param bool $cascade true
     * @return void
     */
    public function softDeleteAll($resourceIds, $cascade = true)
    {
        $Resources = TableRegistry::getTableLocator()->get('Resources');
        $Resources->updateAll(['deleted' => true], ['id IN' => $resourceIds]);

        if ($cascade) {
            $Favorites = TableRegistry::getTableLocator()->get('Favorites');
            $Favorites->deleteAll(['foreign_key IN' => $resourceIds]);

            $Secrets = TableRegistry::getTableLocator()->get('Secrets');
            $Secrets->deleteAll(['resource_id IN' => $resourceIds]);

            $Permissions = TableRegistry::getTableLocator()->get('Permissions');
            $Permissions->deleteAll(['aco_foreign_key IN' => $resourceIds]);

            if (Configure::read('passbolt.plugins.tags.enabled')) {
                $ResourcesTags = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
                $ResourcesTags->deleteAll(['resource_id IN' => $resourceIds]);
                $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
                $Tags->deleteAllUnusedTags();
            }
        }
    }
}
