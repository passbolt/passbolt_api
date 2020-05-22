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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Controller\Users;

use App\Model\Entity\Permission;
use App\Test\Fixture\Base\AvatarsFixture;
use App\Test\Fixture\Base\FavoritesFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UuidFactory;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Fixture\PermissionsFixture;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

class UsersDeleteControllerTest extends FoldersIntegrationTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use PermissionsModelTrait;

    private $Permissions;

    public $fixtures = [
        AvatarsFixture::class,
        FavoritesFixture::class,
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GpgkeysFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        RolesFixture::class,
        SecretsFixture::class,
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
    }

    public function testUsersDeleteSuccess_PersonalFolder()
    {
        list($folderA, $userAId) = $this->insertFixture_PersonalFolder();
        $this->authenticateAs('admin');

        $this->deleteJson("/users/$userAId.json?api-version=v2");
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userAId);
        $this->assertFolderNotExist($folderA->id);
    }

    private function insertFixture_PersonalFolder()
    {
        // Ada is OWNER of folder A
        // ---
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);

        return [$folderA, $userAId];
    }

    public function testUsersDeleteError_SoleOwnerFolder_FolderSharedWithUser()
    {
        list($folderA, $userAId, $userBId) = $this->insertFixture_SoleOwnerFolder_FolderSharedWithUser();
        $this->authenticateAs('admin');

        $this->deleteJson("/users/$userAId.json?api-version=v2");

        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userAId);
        $this->assertFolder($folderA->id);
        $this->assertContains('You need to transfer the ownership for the shared content', $this->_responseJsonHeader->message);

        $errors = $this->_responseJsonBody->errors;
        $this->assertEquals(1, count($errors->folders->sole_owner));

        $folder = $errors->folders->sole_owner[0];
        $this->assertFolderAttributes($folder);
        $this->assertEquals($folder->id, $folderA->id);
    }

    private function insertFixture_SoleOwnerFolder_FolderSharedWithUser()
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
}
