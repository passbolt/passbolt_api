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
     * @param array|null $data The resource data
     * @param array|null $options The entity options
     * @return Resource
     */
    public function addResource(?array $data = [], ?array $options = []): Resource
    {
        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $resource = self::getDummyResourceEntity($data, $options);

        $resourcesTable->save($resource, ['checkRules' => false]);

        return $resource;
    }

    /**
     * Add folders for the given users
     *
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
     * @param array|null $data The resource data.
     * @param array|null $options The new entity options.
     * @return Resource
     */
    public function getDummyResourceEntity(?array $data = [], ?array $options = []): Resource
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
            'resource_type_id' => UuidFactory::uuid('resource-types.id.password-string'),
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
     *
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
     *
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

    protected function getDummyGpgMessage(): string
    {
        return '-----BEGIN PGP MESSAGE-----

hQIMA1P90Qk1JHA+ARAAu3oaLzv/BfeukST6tYAkAID+xbt5dhsv4lxL3oSbo8Nm
qmJQSVe6wmh8nZJjeHN4L7iCq8FEZpdCwrDbX1qIuqBFFO3vx6BJFOURG0JbI/E/
nXtvck00RvxTB1Y30OUbGp21jjEILyuELhWpf11+AQelybY4XKyM8UxGjSncDqaS
X7/yXspCByywci1VfzK7D6+zfcyLy29wQm9Ci5j6I4QqhvlKQPTxl6tWrJh+EyLP
SLZjO8ofc00fbc7mUIH5taDg6Br2VLG/x29HhKCPYdOVzSz3BpUCcUcPgn98mCV0
Qh7ZPE1NNmCWXID5hryuSF71IiAYhxae9u77pOAbVe0PwFgMY6kke/hJQkO6IYJ/
/Q3aL/xHTlY2XtPbpV1in6soc0wJBuoROrwN0AdtvEJOnomclNEH5BPwLjZ1shCr
vuk0zJjj9WcqQiVNEuErs4d7rLc+dB7md+97S8Gtcf8lrlZMH9ooI2UnvxC8HRqX
KzcgW17YF44VtD2TLMymvpnjPV9gruYnmpkQG/1ihnDOWe6xWlFH6jZf5eE4IEVn
osx/D6inZHHMXWbZu9hMiQloKKZ0s8yxTFw9C1wFwaIxRtvJ84qc17rJs7mfcC2n
sG7jLzQBV/GVWtR4hVebstP+q05Sib+sKwLOTZhzWNPKruBsdaBCUTxcmI6qwDHS
QQFgGx0K1xQj2rKiP2j0cDHyGsWIlOITN+4r6Ohx23qRhVo0txPWVOYLpC8JnlfQ
W3AI8+rWjK8MGH2T88hCYI/6
=uahb
-----END PGP MESSAGE-----';
    }

    protected function getDummyResourcesPostData($data = []): array
    {
        $defaultData = [
            'name' => 'new resource name',
            'username' => 'username@domain.com',
            'uri' => 'https://www.domain.com',
            'description' => 'new resource description',
            'secrets' => [
                [
                    'data' => $this->getDummyGpgMessage(),
                ],
            ],
        ];
        $data = array_merge($defaultData, $data);

        return $data;
    }
}
