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

namespace Passbolt\Folders\Test\TestCase\Controller\Groups;

use App\Model\Entity\Permission;
use App\Test\Fixture\Base\FavoritesFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UuidFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

class GroupsDeleteControllerTest extends FoldersIntegrationTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use GroupsModelTrait;
    use PermissionsModelTrait;

    public $fixtures = [
    FavoritesFixture::class,
        GpgkeysFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        ProfilesFixture::class,
        RolesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    public function testFoldersGroupsDeleteSuccess_PersonalFolder()
    {
        [$folderA, $g1, $userAId] = $this->insertFixture_PersonalFolder();
        $this->authenticateAs('admin');

        $this->deleteJson("/groups/$g1->id.json?api-version=v2");
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($g1->id);
        $this->assertFolderNotExist($folderA->id);
    }

    private function insertFixture_PersonalFolder()
    {
        // G1 is OWNER of folder A
        // Ada is member of group G1
        // ---
        // A (G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $g1Data = [
            'groups_users' => [
                ['user_id' => $userAId, 'is_admin' => true],
            ],
        ];
        $g1 = $this->addGroup($g1Data);
        $folderA = $this->addFolderFor(['name' => 'A'], [], [$g1->id => Permission::OWNER]);

        return [$folderA, $g1, $userAId];
    }

    public function testFoldersGroupsDeleteError_SoleOwnerFolder_FolderSharedWithUser()
    {
        [$folderA, $g1, $userAId, $userBId] = $this->insertFixture_SoleOwnerFolder_FolderSharedWithUser();
        $this->authenticateAs('admin');

        $this->deleteJson("/groups/$g1->id.json?api-version=v2");

        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userAId);
        $this->assertFolder($folderA->id);
        $this->assertStringContainsString('transfer the ownership', $this->_responseJsonHeader->message);

        $errors = $this->_responseJsonBody->errors;
        $this->assertEquals(1, count($errors->folders->sole_owner));

        $folder = $errors->folders->sole_owner[0];
        $this->assertFolderAttributes($folder);
        $this->assertEquals($folder->id, $folderA->id);
    }

    private function insertFixture_SoleOwnerFolder_FolderSharedWithUser()
    {
        // G1 is OWNER of folder A
        // Betty has READ on folder A
        // Ada is member of G1
        // ---
        // A (G1:O, Betty:R)
        $userAId = UuidFactory::uuid('user.id.ada');
        $g1Data = [
            'groups_users' => [
                ['user_id' => $userAId, 'is_admin' => true],
            ],
        ];
        $g1 = $this->addGroup($g1Data);
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::READ], [$g1->id => Permission::OWNER]);

        return [$folderA, $g1, $userAId, $userBId];
    }
}
