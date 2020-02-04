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

use App\Model\Table\PermissionsTable;
use App\Utility\UuidFactory;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Behavior\FolderParentIdBehavior;
use PHPUnit\Framework\Assert;

/**
 * @mixin Assert
 */
trait FoldersRelationsModelTrait
{
    public static function addFolderRelation($data = [], $options = [])
    {
        $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $folderRelation = self::getDummyFolderRelationEntity($data, $options);

        $foldersRelationsTable->saveOrFail($folderRelation);

        return $folderRelation;
    }

    public static function getDummyFolderRelationEntity($data = [], $options = [])
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
    public static function getDummyFolderRelation($data = [])
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
     * @param string $foreignId
     * @param string $userId
     * @param string $folderParentId
     */
    protected function assertFolderRelation(string $foreignId, string $userId, string $folderParentId = null)
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
        $this->assertNotEmpty($folderRelation);
    }

    /**
     * Assert a user has an item in their graph.
     * @param string $foreignId
     * @param string $userId
     * @param string $folderParentId
     */
    protected function assertFolderRelationNotExist(string $foreignId, string $userId, string $folderParentId = null)
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
     * Assert a user has an item in their graph.
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

    protected function assertObjectHasFolderParentIdAttribute($object, string $expectedParentId)
    {
        $this->assertObjectHasAttribute('folder_parent_id', $object);
        $this->assertEquals($expectedParentId, $object->folder_parent_id);
    }
}
