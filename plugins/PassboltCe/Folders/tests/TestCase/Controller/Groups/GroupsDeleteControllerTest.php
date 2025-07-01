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
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

class GroupsDeleteControllerTest extends FoldersIntegrationTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use GroupsModelTrait;

    public function testFoldersGroupsDeleteSuccess_PersonalFolder()
    {
        // G1 is OWNER of folder A
        // Ada is member of group G1
        // ---
        // A (G1:O)
        $userA = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$g1])
            ->withFoldersRelationsFor([$g1])
            ->persist();

        $this->logInAsAdmin();

        $this->deleteJson("/groups/{$g1->get('id')}.json?api-version=v2");
        $this->assertSuccess();
        $this->assertGroupIsSoftDeleted($g1->get('id'));
        $this->assertFolderNotExist($folderA->get('id'));
    }

    public function testFoldersGroupsDeleteError_SoleOwnerFolder_FolderSharedWithUser()
    {
        // G1 is OWNER of folder A
        // Betty has READ on folder A
        // Ada is member of G1
        // ---
        // A (G1:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$g1])
            ->withPermissionsFor([$userB], Permission::READ)
            ->withFoldersRelationsFor([$g1, $userB])
            ->persist();

        $this->logInAsAdmin();

        $this->deleteJson("/groups/{$g1->get('id')}.json?api-version=v2");

        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userA->id);
        $this->assertFolder($folderA->get('id'));
        $this->assertStringContainsString('transfer the ownership', $this->_responseJsonHeader->message);

        $errors = $this->_responseJsonBody->errors;
        $this->assertEquals(1, count($errors->folders->sole_owner));

        $folder = $errors->folders->sole_owner[0];
        $this->assertFolderAttributes($folder);
        $this->assertEquals($folder->id, $folderA->get('id'));
    }
}
