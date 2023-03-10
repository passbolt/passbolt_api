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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\Lib\Model;

use App\Model\Table\PermissionsTable;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

trait FoldersModelTrait
{
    public function addFolder($data = [], ?array $options = [])
    {
        $foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $folder = self::getDummyFolderEntity($data, $options);

        $foldersTable->saveOrFail($folder);

        return $folder;
    }

    public function getDummyFolderEntity(?array $data = [], ?array $options = [])
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
    public function getDummyFolderData(?array $data = [])
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

    /**
     * Add a folder for a given list of users and groups. This function creates also the associated data: secrets,
     * permissions, folders_relations.
     *
     * @param array $data The folder meta data
     * @param array $users List of user to add a resource for. The first element should refer to a user id.
     * @param array $groups List of groups to add a resource for.
     * @param array $options The folder entity create options
     * @return \Passbolt\Folders\Model\Entity\Folder
     */
    public function addFolderFor(?array $data = [], ?array $users = [], ?array $groups = [], ?array $options = [])
    {
        $foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        reset($users);
        $userId = key($users);
        if (!$userId) {
            $userId = UuidFactory::uuid('user.id.system');
        }
        if (!isset($data['created_by'])) {
            $data['created_by'] = $userId;
        }
        if (!isset($data['modified_by'])) {
            $data['modified_by'] = $userId;
        }

        $folder = $this->getDummyFolderEntity($data, $options);
        $foldersTable->saveOrFail($folder);

        foreach ($users as $userId => $permissionType) {
            $this->addPermission('Folder', $folder->get('id'), null, $userId, $permissionType);
            $folderParentId = $data['folder_parent_id'] ?? null;
            $folderRelationData = [
                'foreign_model' => PermissionsTable::FOLDER_ACO,
                'foreign_id' => $folder->get('id'),
                'user_id' => $userId,
                'folder_parent_id' => $folderParentId,
            ];
            $this->addFolderRelation($folderRelationData);
        }

        foreach ($groups as $groupId => $permissionType) {
            $this->addPermission('Folder', $folder->get('id'), null, $groupId, $permissionType);
            $folderParentId = $data['folder_parent_id'] ?? null;
            $groupUsersIds = $usersTable->Groups->GroupsUsers->findByGroupId($groupId)
                ->all()
                ->extract('user_id')
                ->toArray();
            foreach ($groupUsersIds as $groupUserId) {
                $folderRelationData = [
                    'foreign_model' => PermissionsTable::FOLDER_ACO,
                    'foreign_id' => $folder->get('id'),
                    'user_id' => $groupUserId,
                    'folder_parent_id' => $folderParentId,
                ];
                $this->addFolderRelation($folderRelationData);
            }
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
        $attributes = ['id', 'name', 'created', 'modified', 'created_by', 'modified_by'];
        $this->assertObjectHasAttributes($attributes, $folder);
    }

    /**
     * Assert a user has a folder
     *
     * @param string $folderId The folder to assert
     * @return void
     */
    protected function assertFolder(string $folderId)
    {
        $foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $folderQuery = $foldersTable->find()->where(['id' => $folderId]);

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
        $foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $permissionsTable = TableRegistry::getTableLocator()->get('Permissions');

        $folder = $foldersTable->find()->where(['id' => $id])->count();
        $this->assertEmpty($folder);

        $foldersRelations = $foldersRelationsTable->find()->where(['foreign_id' => $id])->count();
        $this->assertEmpty($foldersRelations);
        $foldersRelationsParents = $foldersRelationsTable->find()->where(['folder_parent_id' => $id])->count();
        $this->assertEmpty($foldersRelationsParents);

        $permissions = $permissionsTable->find()->where(['aco_foreign_key' => $id])->count();
        $this->assertEmpty($permissions);
    }
}
