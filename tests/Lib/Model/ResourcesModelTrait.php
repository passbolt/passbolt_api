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
namespace App\Test\Lib\Model;

use App\Model\Entity\Resource;
use App\Model\Table\PermissionsTable;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

trait ResourcesModelTrait
{
    /**
     * Add a dummy resource.
     *
     * @param array $data The resource data
     * @param array $options The entity options
     * @return Resource
     */
    public function addResource($data = [], $options = [])
    {
        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $resource = self::getDummyResourceEntity($data, $options);

        $resourcesTable->save($resource, ['checkRules' => false]);

        return $resource;
    }

    /**
     * Add folders for the given users
     * @param array $data Data
     * @param array $users List of user to add a resource for. The first element should refer to a user id.
     * @param array $groups List of groups to add a resource for.
     * @return \Cake\Datasource\EntityInterface
     */
    public function addResourceFor(array $data = [], array $users = [], array $groups = [])
    {
        reset($users);
        $userId = key($users);
        if (!isset($data['created_by'])) {
            $data['created_by'] = $userId;
        }
        if (!isset($data['modified_by'])) {
            $data['modified_by'] = $userId;
        }

        $resource = $this->addResource($data);
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        foreach ($users as $userId => $permissionType) {
            $this->addPermission(PermissionsTable::RESOURCE_ACO, $resource->id, PermissionsTable::USER_ARO, $userId, $permissionType);
            $this->addResourceForUserAssociatedData($resource, $userId, $data);
        }

        foreach ($groups as $groupId => $permissionType) {
            $this->addPermission(PermissionsTable::RESOURCE_ACO, $resource->id, PermissionsTable::GROUP_ARO, $groupId, $permissionType);
            $groupUsersIds = $usersTable->Groups->GroupsUsers->findByGroupId($groupId)->extract('user_id')->toArray();
            foreach ($groupUsersIds as $groupUserId) {
                $this->addResourceForUserAssociatedData($resource, $groupUserId);
            }
        }

        return $resource;
    }

    /**
     * Add resource associated data for a user:
     * - Secrets.
     * - Folders if folders enabled.
     *
     * @param Resource $resource
     * @param string $userId
     * @param array $data
     */
    private function addResourceForUserAssociatedData(Resource $resource, string $userId, array $data = [])
    {
        $secretData = [
            'resource_id' => $resource->id,
            'user_id' => $userId,
        ];
        $this->addSecret($secretData);

        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $folderRelationData = [
                'foreign_model' => PermissionsTable::RESOURCE_ACO,
                'foreign_id' => $resource->id,
                'user_id' => $userId,
                'folder_parent_id' => $data['folder_parent_id'] ?? null,
            ];
            $this->addFolderRelation($folderRelationData);
        }
    }

    /**
     * Get a new resource entity
     *
     * @param array $data The resource data.
     * @param array $options The new entity options.
     * @return Resouce
     */
    public function getDummyResourceEntity($data = [], $options = [])
    {
        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $defaultOptions = [
            'validate' => false,
            'accessibleFields' => [
                '*' => true,
            ],
        ];

        $data = self::getDummyResourceData($data);
        $options = array_merge($defaultOptions, $options);

        return $resourcesTable->newEntity($data, $options);
    }

    /**
     * Get a dummy resource with test data.
     * The comment returned passes a default validation.
     *
     * @param array $data Custom data that will be merged with the default content.
     * @return array Comment data
     */
    public static function getDummyResourceData($data = [])
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $entityContent = [
            'name' => 'New resource name',
            'username' => 'username@domain.com',
            'uri' => 'https://www.domain.com',
            'description' => 'New resource description',
            'created_by' => $userId,
            'modified_by' => $userId,
        ];
        $entityContent = array_merge($entityContent, $data);

        return $entityContent;
    }

    /**
     * Asserts that an object has all the attributes a resource should have.
     *
     * @param object $resource
     */
    protected function assertResourceAttributes($resource)
    {
        $attributes = ['id', 'name', 'username', 'uri', 'description', 'deleted', 'created', 'modified', 'created_by', 'modified_by'];
        $this->assertObjectHasAttributes($attributes, $resource);
    }

    /**
     * Assert that a user has not access to a or multiple resources
     * @param string $userId
     * @param array $resourcesIds
     */
    protected function assertUserHasNotAccessResources(string $userId, array $resourcesIds = [])
    {
        foreach ($resourcesIds as $resourceId) {
            // No access to the resource.
            $hasAccess = $this->Resources->Permissions->hasAccess(PermissionsTable::RESOURCE_ACO, $resourceId, $userId);
            $this->assertFalse($hasAccess);
            // No secret for the resource.
            $secret = $this->Resources->Secrets->find()
                ->where(['resource_id' => $resourceId, 'user_id' => $userId])->first();
            $this->assertNull($secret);
            // Not favorite for the resource.
            $favorite = $this->Resources->Favorites->find()
                ->where(['foreign_key' => $resourceId, 'user_id' => $userId])->first();
            $this->assertNull($favorite);
        }
    }

    /**
     * Assert that a user has access to a or multiple resources
     * @param string $userId
     * @param array $resourcesIds
     */
    protected function assertUserHasAccessResources(string $userId, array $resourcesIds = [])
    {
        foreach ($resourcesIds as $resourceId) {
            // Access granted to the resource.
            $hasAccess = $this->Resources->Permissions->hasAccess(PermissionsTable::RESOURCE_ACO, $resourceId, $userId);
            $this->assertTrue($hasAccess);
            // Secret existing.
            $secret = $this->Resources->Secrets->find()
                ->where(['resource_id' => $resourceId, 'user_id' => $userId])->first();
            $this->assertNotNull($secret);
        }
    }

    /**
     * Asserts than a resource is soft deleted.
     *
     * @param string $id
     */
    protected function assertResourceIsSoftDeleted($id)
    {
        $resource = $this->Resources->get($id);
        $this->assertTrue($resource->deleted);
    }

    /**
     * Asserts than a resource is not soft deleted.
     *
     * @param string $id
     */
    protected function assertResourceIsNotSoftDeleted($id)
    {
        $resource = $this->Resources->get($id);
        $this->assertFalse($resource->deleted);
    }

    /**
     * Assert than a resource does not exist
     *
     * @param mixed $selector Either the resource id or a find options array
     */
    protected function assertResourceNotExist($selector)
    {
        if (is_string($selector)) {
            $resource = $this->Resources->get($selector);
        } else {
            $resource = $this->Resources->find()->where($selector)->first();
        }
        $this->assertEmpty($resource);
    }
}
