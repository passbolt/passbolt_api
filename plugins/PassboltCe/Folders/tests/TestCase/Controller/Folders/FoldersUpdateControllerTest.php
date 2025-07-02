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
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;

/**
 * Passbolt\Folders\Controller\Folders\FoldersUpdateController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersUpdateController
 */
class FoldersUpdateControllerTest extends FoldersIntegrationTestCase
{
    public function testFoldersUpdateSuccess_UpdateName()
    {
        // Ada has access to folder A as a OWNER
        // ----
        // A (Ada:O)
        $userA = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();

        $data = ['name' => 'A updated'];
        $this->logInAs($userA);
        $this->postJson("/folders/{$folderA->get('id')}.json?api-version=2", $data);
        $this->assertSuccess();

        // Assert controller response
        $folderUpdated = $this->_responseJsonBody;
        $this->assertEquals($data['name'], $folderUpdated->name);
    }

    public function testFoldersUpdateError_NotValidId()
    {
        $this->logInAsUser();
        $folderId = 'invalid-id';
        $this->putJson("/move/folder/$folderId.json?api-version=2");
        $this->assertError(400, 'The object identifier should be a valid UUID.');
    }

    public function testFoldersUpdateError_ValidationErrors()
    {
        // Ada has access to folder A as a OWNER
        // ----
        // A (Ada:O)
        $userA = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();

        $this->logInAs($userA);
        $data = ['name' => ''];
        $this->putJson("/folders/{$folderA->get('id')}.json?api-version=2", $data);
        $this->assertError(400, 'Could not validate folder data.');
    }

    public function testFoldersUpdateError_CsrfToken()
    {
        $this->disableCsrfToken();
        $this->logInAsUser();
        $folderId = FolderFactory::make()->persist()->get('id');
        $this->put("/folders/{$folderId}.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testFoldersResourcesUpdateError_InsufficientPermission()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // ---
        // A (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();

        $data = [
            'name' => 'A updated',
        ];
        $this->logInAs($userB);
        $this->putJson("/folders/{$folderA->get('id')}.json", $data);
        $this->assertError(403, 'You are not allowed to update this folder.');
    }

    public function testFoldersUpdateError_NotAuthenticated()
    {
        $folderId = FolderFactory::make()->persist()->get('id');
        $this->putJson("/folders/{$folderId}.json?api-version=2", []);
        $this->assertAuthenticationError();
    }

    public function testFoldersUpdateResourcesError_FolderDoesNotExist()
    {
        $this->logInAsUser();
        $folderId = UuidFactory::uuid();
        $this->putJson("/folders/{$folderId}.json");
        $this->assertError(404, 'The folder does not exist.');
    }

    public function testFoldersUpdateResourcesError_NoAccessToFolder()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // ---
        // A (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();

        $this->logInAsUser();
        $data = ['name' => 'A updated'];
        $this->putJson("/folders/{$folderA->get('id')}.json", $data);
        $this->assertError(404, 'The folder does not exist.');
    }
}
