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

namespace Passbolt\Folders\Test\TestCase\Controller\Folders;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Controller\Folders\FoldersCreateController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersCreateController
 */
class FoldersCreateControllerTest extends FoldersIntegrationTestCase
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
        RolesFixture::class,
        UsersFixture::class,
    ];

    public $FoldersRelations;
    public $Permissions;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    public function testFoldersCreateFolder_PersoSuccess1_CreateFolder()
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

    public function testFoldersCreateFolder_PersoSuccess2_CreateInFolder()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $parentFolder = $this->insertPersoSuccess2Fixture();

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

    private function insertPersoSuccess2Fixture()
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);

        return $folderA;
    }

    public function testFoldersCreateFolder_CommonError1_ValidationError()
    {
        $data = ['name' => ''];
        $this->authenticateAs('ada');
        $this->postJson('/folders.json?api-version=2', $data);
        $this->assertError(400, 'Could not validate folder data.');
        $arr = $this->getResponseBodyAsArray();
        $error = Hash::get($arr, 'name');
        $this->assertNotNull($error, 'The test should return an error of the given field.');
        $this->assertEquals('The name should not be empty.', $error['_empty']);
    }

    public function testFoldersCreateFolder_CommonError2_ParentFolderDoesNotExist()
    {
        $data = [
            'name' => 'B',
            'folder_parent_id' => UuidFactory::uuid('folder.id.not-exist'),
        ];
        $this->authenticateAs('ada');
        $this->postJson('/folders.json?api-version=2', $data);
        $this->assertError(400, 'Could not validate folder data');
        $arr = $this->getResponseBodyAsArray();
        $error = Hash::get($arr, 'folder_parent_id');
        $this->assertNotNull($error, 'The test should return an error of the given field.');
        $this->assertEquals('The folder parent must exist.', $error['folder_exists']);
    }

    public function testFoldersCreateFolder_SharedError1_ParentFolderInsufficientPermission()
    {
        $parentFolder = $this->insertSharedError1Fixture();

        $data = [
            'name' => 'B',
            'folder_parent_id' => $parentFolder->id,
        ];
        $this->authenticateAs('ada');
        $this->postJson('/folders.json?api-version=2', $data);
        $arr = $this->getResponseBodyAsArray();
        $error = Hash::get($arr, 'folder_parent_id');
        $this->assertNotNull($error, 'The test should return an error of the given field.');
        $this->assertEquals('You are not allowed to create content into the parent folder.', $error['has_folder_access']);
    }

    private function insertSharedError1Fixture()
    {
        // Ada has access to folder A in READ
        // Betty has access to folder A as OWNER
        // A (Ada:R, Betty:0)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return $folder;
    }

    public function testFoldersCreateFolderError_IsProtectedByCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $this->post('/folders.json?api-version=2');
        $this->assertResponseCode(403);
    }

    public function testFoldersCreateFolderError_NotAuthenticated()
    {
        $this->postJson('/folders.json?api-version=2');
        $this->assertAuthenticationError();
    }
}
