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
use Cake\Core\Configure;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Controller\Folders\FoldersUpdatePermissionsController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersShareController
 */
class FoldersShareControllerTest extends FoldersIntegrationTestCase
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

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
//        $config = TableRegistry::getTableLocator()->exists('FoldersRelations') ? [] : ['className' => FoldersRelationsTable::class];
//        $this->FoldersRelations = TableRegistry::getTableLocator()->get('FoldersRelations', $config);
//        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
//        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    /* COMMON & VALIDATION */

    public function testFoldersShareFolder_CommonError1_DoesNotExist()
    {
        $this->authenticateAs('ada');
        $folderId = UuidFactory::uuid();
        $this->postJson("/share/folder/{$folderId}.json?api-version=2");
        $this->assertError(404, 'The folder does not exist.');
    }

    public function testFoldersShareFolder_CommonError2_NoPermission()
    {
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::READ]);

        $this->authenticateAs('ada');
        $this->postJson("/share/folder/{$folder->id}.json?api-version=2");
        $this->assertForbiddenError('You are not allowed to update the permissions of this folder.');
    }

    public function testFoldersShareFolder_CommonError_NotAuthenticated()
    {
        $folderId = UuidFactory::uuid('folder.id.folder');
        $this->putJson("/folders/{$folderId}.json?api-version=2");
        $this->assertAuthenticationError();
    }

    public function testFoldersShareFolder_CommonError_NotValidIdParameter()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->putJson("/share/folder/$resourceId.json?api-version=2");
        $this->assertError(400, 'The folder id is not valid.');
    }

    public function testFoldersShareFolder_CommonError_IsProtectedByCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $folderId = UuidFactory::uuid('folder.id.folder');
        $this->put("/folders/{$folderId}.json?api-version=2");
        $this->assertResponseCode(403);
    }

    /* PERSONAL */

    /* SHARED */

    public function testFoldersShareFolder_SharedError1_InsufficientPermission()
    {
        $folder = $this->insertSharedError1Fixture();

        $this->authenticateAs('ada');
        $this->postJson("/share/folder/{$folder->id}.json?api-version=2");
        $this->assertForbiddenError('You are not allowed to update the permissions of this folder.');
    }

    public function insertSharedError1Fixture()
    {
        // Ada has UPDATE access on folder A
        // Betty has access to folder A as a OWNER
        // A (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::UPDATE, $userBId => Permission::OWNER]);

        return $folder;
    }
}
