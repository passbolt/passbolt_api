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

namespace Passbolt\Folders\Test\TestCase\Controller\Users;

use App\Model\Entity\Permission;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

class UsersDeleteControllerTest extends FoldersIntegrationTestCase
{
    use FoldersModelTrait;

    public function testFoldersUsersDeleteSuccess_PersonalFolder()
    {
        // Ada is OWNER of folder A
        // ---
        // A (Ada:O)
        RoleFactory::make()->guest()->persist();
        $userA = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();

        $this->logInAsAdmin();

        $this->deleteJson("/users/{$userA->get('id')}.json?api-version=v2");
        $this->assertSuccess();
        $this->assertUserIsSoftDeleted($userA->get('id'));
        $this->assertFolderNotExist($folderA->get('id'));
    }

    public function testFoldersUsersDeleteError_SoleOwnerFolder_FolderSharedWithUser()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // Ada is OWNER of folder B
        // ---
        // A (Ada:O, Betty:R)
        // B (Ada:O)
        RoleFactory::make()->guest()->persist();
        [$userA, $userB] = UserFactory::make(2)->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();
        FolderFactory::make()->withPermissionsFor([$userA])->persist();

        $this->logInAsAdmin();

        $this->deleteJson("/users/{$userA->get('id')}.json?api-version=v2");

        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($userA->get('id'));
        $this->assertFolder($folderA->get('id'));
        $this->assertStringContainsString('transfer the ownership', $this->_responseJsonHeader->message);

        $errors = $this->_responseJsonBody->errors;
        $this->assertEquals(1, count($errors->folders->sole_owner));

        $folder = $errors->folders->sole_owner[0];
        $this->assertFolderAttributes($folder);
        $this->assertEquals($folder->id, $folderA->get('id'));
    }
}
