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

/**
 * @mixin Assert
 */
trait FoldersRelationsModelTrait
{
    public static function addFolderRelation(?array $data = [], ?array $options = [])
    {
        $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $folderRelation = $foldersRelationsTable->findByForeignIdAndUserId($data['foreign_id'], $data['user_id'])->first();
        if (!empty($folderRelation)) {
            return $folderRelation;
        }

        $folderRelation = self::getDummyFolderRelationEntity($data, $options);
        $foldersRelationsTable->saveOrFail($folderRelation, ['checkRules' => false]);
    }

    public static function getDummyFolderRelationEntity(?array $data = [], ?array $options = [])
    {
        $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $defaultOptions = [
            'checkRules' => true,
            'accessibleFields' => ['*' => true],
        ];

        $data = self::getDummyFolderRelation($data);
        $options = array_merge($defaultOptions, $options);

        return $foldersRelationsTable->newEntity($data, $options);
    }

    /**
     * Get a dummy folder relation with test data.
     * The relation returned shoudl passe a default validation.
     *
     * @param array $data Custom data that will be merged with the default content.
     * @return array
     * @throws \Exception
     */
    public static function getDummyFolderRelation(?array $data = [])
    {
        $entityContent = [
            'foreign_model' => PermissionsTable::FOLDER_ACO,
            'foreign_id' => UuidFactory::uuid('folder.id.folder'),
            'user_id' => UuidFactory::uuid('user.id.test'),
            'created' => (new \DateTime())->format('Y-m-d H:i:s'),
            'modified' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];
        $entityContent = array_merge($entityContent, $data);

        return $entityContent;
    }

    /**
     * Assert a user has an item in their graph.
     *
     * @param string $foreignId
     * @param string $foreignModel
     * @param string $userId
     * @param string $folderParentId
     */
    protected function assertFolderRelation(string $foreignId, string $foreignModel, string $userId, ?string $folderParentId = null)
    {
        $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $folderRelationQuery = $foldersRelationsTable->find()->where([
            'foreign_id' => $foreignId,
            'foreign_model' => $foreignModel,
            'user_id' => $userId,
        ]);
        if (is_null($folderParentId)) {
            $folderRelationQuery->whereNull('folder_parent_id');
        } else {
            $folderRelationQuery->where(['folder_parent_id' => $folderParentId]);
        }
        $folderRelation = $folderRelationQuery->first();
        $this->assertNotEmpty($folderRelation);
    }

    /**
     * Assert a user has an item in their graph.
     *
     * @param string $foreignId
     * @param string $userId
     * @param string $folderParentId
     */
    protected function assertFolderRelationNotExist(string $foreignId, string $userId, ?string $folderParentId = null)
    {
        $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $folderRelationQuery = $foldersRelationsTable->find()->where([
            'foreign_id' => $foreignId,
            'user_id' => $userId,
        ]);
        if (is_null($folderParentId)) {
            $folderRelationQuery->whereNull('folder_parent_id');
        } else {
            $folderRelationQuery->where(['folder_parent_id' => $folderParentId]);
        }
        $folderRelation = $folderRelationQuery->first();
        $this->assertEmpty($folderRelation);
    }

    /**
     * Assert the item is visible in a number of trees.
     *
     * @param string $foreignId
     * @param int $count
     */
    protected function assertItemIsInTrees(string $foreignId, int $count)
    {
        $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $found = $foldersRelationsTable->find()->where(['foreign_id' => $foreignId])->count();
        $this->assertEquals($count, $found);
    }

    /**
     * Assert a user has an item in their graph.
     *
     * @param string $foreignId
     * @param string $userId
     */
    protected function assertNoRelationsExistFor(string $foreignId, string $userId)
    {
        $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $folderRelationQuery = $foldersRelationsTable->find()->where([
            'foreign_id' => $foreignId,
            'user_id' => $userId,
        ]);

        $this->assertEquals(0, $folderRelationQuery->count());
    }

    protected function assertObjectHasFolderParentIdAttribute($object, ?string $expectedParentId = null)
    {
        $this->assertObjectHasAttribute('folder_parent_id', $object);
        $this->assertEquals($expectedParentId, $object->folder_parent_id);
    }
}
