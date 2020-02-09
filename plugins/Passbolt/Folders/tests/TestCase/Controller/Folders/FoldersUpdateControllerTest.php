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
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Controller\Folders\FoldersUpdateController Test Case
 *
 * @uses \Passbolt\Folders\Controller\Folders\FoldersUpdateController
 */
class FoldersUpdateControllerTest extends AppIntegrationTestCase
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
        UsersFixture::class,
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

    public function testSuccessCase1_UpdateName()
    {
        $folder = $this->insertFixtureCase1();

        $data = ['name' => 'A updated'];
        $this->authenticateAs('ada');
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);
        $this->assertSuccess();

        // Assert controller response
        $folder = $this->_responseJsonBody;
        $this->assertEquals($data['name'], $folder->name);
    }

    private function insertFixtureCase1()
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);

        return $folder;
    }

    public function testErrorCase2_InsufficientPermissionToUpdateName()
    {
        $folder = $this->insertFixtureCase2();

        $data = ['name' => 'A updated'];
        $this->authenticateAs('ada');
        $this->postJson("/folders/{$folder->id}.json?api-version=2", $data);
        $this->assertForbiddenError('You are not allowed to update this folder.');
    }

    private function insertFixtureCase2()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // A (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return $folder;
    }

    public function testNotValidIdParameter()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->putJson("/folders/$resourceId.json?version=2");
        $this->assertError(400, 'The folder id is not valid.');
    }

    public function testActionIsProtectedByCsrfTokenAndReturnErrorIfNotProvided()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $folderId = UuidFactory::uuid('folder.id.folder');
        $this->put("/folders/{$folderId}.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testResourcesAddErrorNotAuthenticated()
    {
        $folderId = UuidFactory::uuid('folder.id.folder');
        $this->putJson("/folders/{$folderId}.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
