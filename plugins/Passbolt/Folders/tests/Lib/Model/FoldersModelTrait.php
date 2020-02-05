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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Test\Lib\Model;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

trait FoldersModelTrait
{
    public function addFolder($data = [], $options = [])
    {
        $foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $folder = self::getDummyFolderEntity($data, $options);

        $foldersTable->saveOrFail($folder);

        return $folder;
    }

    public function getDummyFolderEntity($data = [], $options = [])
    {
        $foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $defaultOptions = [
            'checkRules' => true,
            'accessibleFields' => [
                '*' => true,
            ],
        ];

        $data = self::getDummyFolderData($data);
        $options = array_merge($defaultOptions, $options);

        return $foldersTable->newEntity($data, $options);
    }

    /**
     * Get a dummy folder with test data.
     * The relation returned shoudl passe a default validation.
     *
     * @param array $data Custom data that will be merged with the default content.
     * @return array
     * @throws \Exception If the create date is not correct.
     */
    public function getDummyFolderData($data = [])
    {
        $entityContent = [
            'name' => UuidFactory::uuid('folder.id.name'),
            'created' => (new \DateTime())->format('Y-m-d H:i:s'),
            'modified' => (new \DateTime())->format('Y-m-d H:i:s'),
            'created_by' => UuidFactory::uuid('user.id.username'),
            'modified_by' => UuidFactory::uuid('user.id.username'),
        ];
        $entityContent = array_merge($entityContent, $data);

        return $entityContent;
    }

    public function addFolderFor($data = [], $usersIds, $options = [])
    {
        reset($usersIds);
        $userId = key($usersIds);
        if (!isset($data['created_by'])) {
            $data['created_by'] = $userId;
        }
        if (!isset($data['modified_by'])) {
            $data['modified_by'] = $userId;
        }

        $foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $folder = $this->getDummyFolderEntity($data, $options);

        $foldersTable->saveOrFail($folder);

        foreach ($usersIds as $userId => $permissionType) {
            $this->addPermission('Folder', $folder->id, 'User', $userId, $permissionType);
            $folderRelationData = [
                'foreign_model' => PermissionsTable::FOLDER_ACO,
                'foreign_id' => $folder->id,
                'user_id' => $userId,
                'folder_parent_id' => $data['folder_parent_id'] ?? null
            ];
            $this->addFolderRelation($folderRelationData);
        }

        return $folder;
    }

    /**
     * Asserts that an object has all the attributes a resource should have.
     *
     * @param object $folder
     */
    protected function assertFolderAttributes($folder)
    {
        $attributes = ['id', 'name', 'created', 'modified', 'created_by', 'modified_by', 'folder_parent_id'];
        $this->assertObjectHasAttributes($attributes, $folder);
    }

    /**
     * Assert a user has a folder
     * @param string $folderId The folder to assert
     * @param string|null $userId
     * @return void
     */
    protected function assertFolder(string $folderId, string $userId = null)
    {
        $folderQuery = $this->Folders->find()->where(['id' => $folderId]);

        if (!is_null($userId)) {
            $folderQuery->where(['user_id' => $userId]);
        }

        $folder = $folderQuery->first();
        $this->assertNotEmpty($folder);
    }

    /**
     * Assert that a folder does not exist
     *
     * @param string $id The target folder
     */
    protected function assertFolderNotExist(string $id)
    {
        $folder = $this->Folders->find()->where(['id' => $id])->count();
        $this->assertEmpty($folder);

        $foldersRelations = $this->FoldersRelations->find()->where(['foreign_id' => $id])->count();
        $this->assertEmpty($foldersRelations);
        $foldersRelationsParents = $this->FoldersRelations->find()->where(['folder_parent_id' => $id])->count();
        $this->assertEmpty($foldersRelationsParents);

        $permissions = $this->Permissions->find()->where(['aco_foreign_key' => $id])->count();
        $this->assertEmpty($permissions);
    }

    /**
     * Add folders for the given users
     * @param array $data Data
     * @param array $usersIds UserIDS
     * @return \Cake\Datasource\EntityInterface
     */
    public function addResourceForUsers($data = [], $usersIds)
    {
        reset($usersIds);
        $userId = key($usersIds);
        if (!isset($data['created_by'])) {
            $data['created_by'] = $userId;
        }
        if (!isset($data['modified_by'])) {
            $data['modified_by'] = $userId;
        }

        $resource = $this->addResource($data);

        foreach($usersIds as $userId => $permissionType) {
            $folderRelationData = [
                'foreign_model' => PermissionsTable::RESOURCE_ACO,
                'foreign_id' => $resource->id,
                'user_id' => $userId,
                'folder_parent_id' => $data['folder_parent_id'] ?? null,
            ];
            $this->addFolderRelation($folderRelationData);
        }

        return $resource;
    }
}
