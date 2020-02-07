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

namespace Passbolt\Folders\Test\TestCase\Controller;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Base\AvatarsFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Controller\Folders\FoldersCreateController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersCreateController
 */
class FoldersCreateControllerTest extends AppIntegrationTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use IntegrationTestTrait;
    use PermissionsModelTrait;

    public $fixtures = [
        AvatarsFixture::class,
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        RolesFixture::class,
        UsersFixture::class
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    public function testSuccessCase1_WithoutParentFolder()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $data = ['name' => 'A'];
        $this->authenticateAs('ada');
        $this->postJson('/folders.json?api-version=2', $data);
        $this->assertSuccess();

        // Assert controller response
        $folder = $this->_responseJsonBody;
        $this->assertFolderAttributes($folder);
        $this->assertEquals($data['name'], $folder->name);
        $this->assertEquals($userId, $folder->created_by);
        $this->assertEquals($userId, $folder->modified_by);
    }

    public function testSuccessCase2_WithFolderParentId()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $parentFolder = null;
        $this->insertFixtureCase2($parentFolder);

        $data = [
            'name' => 'B',
            'folder_parent_id' => $parentFolder->id,
        ];
        $this->authenticateAs('ada');
        $this->postJson('/folders.json?api-version=2', $data);
        $this->assertSuccess();

        // Assert controller response
        $folder = $this->_responseJsonBody;
        $this->assertFolderAttributes($folder);
        $this->assertEquals($data['name'], $folder->name);
        $this->assertEquals($data['folder_parent_id'], $folder->folder_parent_id);
        $this->assertEquals($userId, $folder->created_by);
        $this->assertEquals($userId, $folder->modified_by);
    }

    private function insertFixtureCase2(&$folder)
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolder(['id' => UuidFactory::uuid(), 'name' => 'A', 'created_by' => $userAId, 'modified_by' => $userAId]);
        $this->addPermission('Folder', $folder->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folder->id, 'user_id' => $userAId]);
    }

    public function testErrorCase3_ValidationError()
    {
        $data = ['name' => ''];
        $this->authenticateAs('ada');
        $this->postJson('/folders.json?api-version=2', $data);
        $this->assertError(400, 'Could not validate the folder data.');
        $arr = json_decode(json_encode($this->_responseJsonBody), true);
        $error = Hash::get($arr, 'name');
        $this->assertNotNull($error, "The test should return an error of the given field.");
        $this->assertEquals('The name cannot be empty.', $error['_empty']);
    }

    public function testErrorCase4_ParentFolderDoesNotExist()
    {
        $data = [
            'name' => 'B',
            'folder_parent_id' => UuidFactory::uuid('folder.id.not-exist'),
        ];
        $this->authenticateAs('ada');
        $this->postJson('/folders.json?api-version=2', $data);
        $this->assertError(400, 'Could not validate the folder data');
        $arr = json_decode(json_encode($this->_responseJsonBody), true);
        $error = Hash::get($arr, 'folder_parent_id');
        $this->assertNotNull($error, "The test should return an error of the given field.");
        $this->assertEquals('The folder parent must exist.', $error['folder_exists']);
    }

    public function testErrorCase5_ParentFolderInsufficientPermission()
    {
        $parentFolder = null;
        $this->insertFixtureCase5($parentFolder);

        $data = [
            'name' => 'B',
            'folder_parent_id' => $parentFolder->id,
        ];
        $this->authenticateAs('ada');
        $this->postJson('/folders.json?api-version=2', $data);
        $this->assertForbiddenError('You are not allowed to create content into the parent folder.');
    }

    private function insertFixtureCase5(&$folder)
    {
        // Ada has access to folder A in READ
        // Betty has access to folder A as OWNER
        // A (Ada:R, Betty:0)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolder(['id' => UuidFactory::uuid(), 'name' => 'A', 'created_by' => $userAId, 'modified_by' => $userAId]);
        $this->addPermission('Folder', $folder->id, 'User', $userAId, Permission::READ);
        $this->addPermission('Folder', $folder->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folder->id, 'user_id' => $userAId]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folder->id, 'user_id' => $userBId]);
    }

    public function testActionIsProtectedByCsrfTokenAndReturnErrorIfNotProvided()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $this->post('/folders.json?api-version=2');
        $this->assertResponseCode(403);
    }

    public function testResourcesAddErrorNotAuthenticated()
    {
        $this->postJson('/folders.json?api-version=2');
        $this->assertAuthenticationError();
    }
}
