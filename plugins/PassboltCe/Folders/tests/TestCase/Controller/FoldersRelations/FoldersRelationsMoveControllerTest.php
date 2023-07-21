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

namespace Passbolt\Folders\Test\TestCase\Controller\FoldersRelations;

use App\Model\Entity\Permission;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Controller\FoldersRelations\FoldersRelationsMoveController Test Case
 *
 * @uses \Passbolt\Folders\Controller\FoldersRelations\FoldersRelationsMoveController
 */
class FoldersRelationsMoveControllerTest extends FoldersIntegrationTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use PermissionsModelTrait;

    public $fixtures = [
        GpgkeysFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        ResourcesFixture::class,
        RolesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    public function testFoldersRelationsMoveSuccess_MoveFolder()
    {
        [$folderA, $folderB, $userAId] = $this->insertFixture_MoveFolder();
        $this->authenticateAs('ada');
        $data['folder_parent_id'] = $folderA->id;
        $this->putJson("/move/folder/$folderB->id.json?api-version=2", $data);
        $this->assertSuccess();

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);

        // Folder B
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
    }

    private function insertFixture_MoveFolder()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // ---
        // A (Ada:O)
        // B (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId];
    }

    public function testFoldersRelationsMoveSuccess_MoveResource()
    {
        [$folderA, $r1, $userAId] = $this->insertFixture_MoveResource();
        $this->authenticateAs('ada');
        $data['folder_parent_id'] = $folderA->id;
        $this->putJson("/move/resource/$r1->id.json?api-version=2", $data);
        $this->assertSuccess();

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);

        // R1
        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
    }

    private function insertFixture_MoveResource()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of Resource R1
        // ---
        // A (Ada:O)
        // R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER]);

        return [$folderA, $r1, $userAId];
    }

    public function testFoldersRelationsMoveError_NotValidForeignModel()
    {
        $this->authenticateAs('ada');
        $itemId = UuidFactory::uuid();
        $this->putJson("/move/invalid/$itemId.json?api-version=2");
        $this->assertError(400, 'The object type should be one of the following: Folder, Resource.');
    }

    public function testFoldersRelationsMoveError_NotValidForeignId()
    {
        $this->authenticateAs('ada');
        $itemId = 'invalid-id';
        $this->putJson("/move/folder/$itemId.json?api-version=2");
        $this->assertError(400, 'The object identifier should be a valid UUID.');
    }

    public function testFoldersRelationsMoveError_ValidationErrors_FolderParentIdRequired()
    {
        [$folderA, $folderB, $userAId] = $this->insertFixture_MoveFolder();
        $this->authenticateAs('ada');
        $this->putJson("/move/folder/$folderA->id.json?api-version=2");
        $this->assertError(400, 'Could not validate move data.');
        $arr = $this->getResponseBodyAsArray();
        $error = Hash::get($arr, 'folder_parent_id');
        $this->assertEquals('A folder parent identifier is required.', $error['_required']);
    }

    public function testFoldersRelationsMoveError_ValidationErrors_Uuid()
    {
        [$folderA] = $this->insertFixture_MoveFolder();
        $this->authenticateAs('ada');
        $this->putJson("/move/folder/$folderA->id.json?api-version=2", ['folder_parent_id' => 'invalid-id']);
        $this->assertError(400, 'Could not validate move data.');
        $arr = $this->getResponseBodyAsArray();
        $error = Hash::get($arr, 'folder_parent_id');
        $this->assertEquals('The folder parent identifier should be a valid UUID.', $error['uuid']);
    }

    public function testFoldersRelationsMoveError_CsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $itemId = UuidFactory::uuid();
        $this->put("/move/folder/{$itemId}.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testFoldersRelationsMoveError_NotAuthenticated()
    {
        $itemId = UuidFactory::uuid();
        $this->putJson("/move/folder/{$itemId}.json?api-version=2", []);
        $this->assertAuthenticationError();
    }

    public function testFoldersRelationsMoveError_FolderDoesNotExist()
    {
        $this->authenticateAs('ada');
        $itemId = UuidFactory::uuid();
        $data['folder_parent_id'] = UuidFactory::uuid();
        $this->putJson("/move/folder/{$itemId}.json?api-version=2", $data);
        $this->assertError(404, 'The object to move does not exist.');
    }

    public function testFoldersRelationsUpdateResourcesError_NoAccessToFolder()
    {
        [$folderA, $folderB, $userAId] = $this->insertFixture_MoveFolder();
        $this->authenticateAs('dame');
        $data = ['folder_parent_id' => FoldersRelation::ROOT];
        $this->putJson("/move/folder/$folderA->id.json", $data);
        $this->assertError(404, 'The object to move does not exist.');
    }
}
