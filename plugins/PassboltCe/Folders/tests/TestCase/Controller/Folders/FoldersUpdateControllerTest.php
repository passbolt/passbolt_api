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
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Controller\Folders\FoldersUpdateController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersUpdateController
 */
class FoldersUpdateControllerTest extends FoldersIntegrationTestCase
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

    public function testFoldersUpdateSuccess_UpdateName()
    {
        [$folderA, $userAId] = $this->insertFixture_UpdateName();

        $data = ['name' => 'A updated'];
        $this->authenticateAs('ada');
        $this->postJson("/folders/{$folderA->id}.json?api-version=2", $data);
        $this->assertSuccess();

        // Assert controller response
        $folderUpdated = $this->_responseJsonBody;
        $this->assertEquals($data['name'], $folderUpdated->name);
    }

    private function insertFixture_UpdateName()
    {
        // Ada has access to folder A as a OWNER
        // ----
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);

        return [$folderA, $userAId];
    }

    public function testFoldersUpdateError_NotValidId()
    {
        $this->authenticateAs('ada');
        $folderId = 'invalid-id';
        $this->putJson("/move/folder/$folderId.json?api-version=2");
        $this->assertError(400, 'The object identifier should be a valid UUID.');
    }

    public function testFoldersUpdateError_ValidationErrors()
    {
        [$folderA, $userAId] = $this->insertFixture_UpdateName();
        $this->authenticateAs('ada');
        $data = ['name' => ''];
        $this->putJson("/folders/$folderA->id.json?api-version=2", $data);
        $this->assertError(400, 'Could not validate folder data.');
    }

    public function testFoldersUpdateError_CsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $folderId = UuidFactory::uuid();
        $this->put("/folders/{$folderId}.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testFoldersResourcesUpdateError_InsufficientPermission()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_InsufficientPermission();
        $data = [
            'name' => ['A updated'],
        ];
        $this->authenticateAs('betty');
        $this->putJson("/folders/$folderA->id.json", $data);
        $this->assertError(403, 'You are not allowed to update this folder.');
    }

    private function insertFixture_InsufficientPermission()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // ---
        // A (Ada:O, Betty:R)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);

        return [$folderA, $userAId, $userBId];
    }

    public function testFoldersUpdateError_NotAuthenticated()
    {
        $folderId = UuidFactory::uuid();
        $this->putJson("/folders/{$folderId}.json?api-version=2", []);
        $this->assertAuthenticationError();
    }

    public function testFoldersUpdateResourcesError_FolderDoesNotExist()
    {
        $this->authenticateAs('ada');
        $folderId = UuidFactory::uuid();
        $this->putJson("/folders/$folderId.json");
        $this->assertError(404, 'The folder does not exist.');
    }

    public function testFoldersUpdateResourcesError_NoAccessToFolder()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_InsufficientPermission();
        $this->authenticateAs('dame');
        $data = ['name' => 'A updated'];
        $this->putJson("/folders/$folderA->id.json", $data);
        $this->assertError(404, 'The folder does not exist.');
    }
}
