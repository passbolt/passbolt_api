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
use Cake\Core\Configure;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;

/**
 * Passbolt\Folders\Controller\Folders\FoldersUpdatePermissionsController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersShareController
 */
class FoldersShareControllerTest extends FoldersIntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
    }

    /* COMMON & VALIDATION */

    public function testFoldersShareFolder_CommonError1_DoesNotExist()
    {
        $this->logInAsUser();
        $folderId = UuidFactory::uuid();
        $this->postJson("/share/folder/{$folderId}.json?api-version=2");
        $this->assertError(404, 'The folder does not exist.');
    }

    public function testFoldersShareFolder_CommonError2_NoPermission()
    {
        $userB = UserFactory::make()->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$userB], Permission::READ)->persist();

        $this->logInAsUser();
        $this->postJson("/share/folder/{$folder->get('id')}.json?api-version=2");
        $this->assertForbiddenError('You are not allowed to update the permissions of this folder.');
    }

    public function testFoldersShareFolder_CommonError_NotAuthenticated()
    {
        $folderId = FolderFactory::make()->persist()->get('id');
        $this->putJson("/folders/{$folderId}.json?api-version=2");
        $this->assertAuthenticationError();
    }

    public function testFoldersShareFolder_CommonError_NotValidIdParameter()
    {
        $this->logInAsUser();
        $resourceId = 'invalid-id';
        $this->putJson("/share/folder/$resourceId.json?api-version=2");
        $this->assertError(400, 'The folder id is not valid.');
    }

    public function testFoldersShareFolder_CommonError_IsProtectedByCsrfToken()
    {
        $this->disableCsrfToken();
        $this->logInAsUser();
        $folderId = FolderFactory::make()->persist()->get('id');
        $this->put("/folders/{$folderId}.json?api-version=2");
        $this->assertResponseCode(403);
    }

    /* PERSONAL */

    /* SHARED */

    public function testFoldersShareFolder_SharedError1_InsufficientPermission()
    {
        // Ada has READ access on folder A
        // Betty has access to folder A as a OWNER
        // A (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        $folder = FolderFactory::make()
            ->withPermissionsFor([$userB])
            ->withPermissionsFor([$userA], Permission::READ)
            ->persist();

        $this->logInAs($userA);
        $this->postJson("/share/folder/{$folder->get('id')}.json?api-version=2");
        $this->assertForbiddenError('You are not allowed to update the permissions of this folder.');
    }
}
