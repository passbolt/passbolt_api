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

namespace Passbolt\Folders\Test\TestCase\Controller\FoldersRelations;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

/**
 * Passbolt\Folders\Controller\FoldersRelations\FoldersRelationsMoveController Test Case
 *
 * @uses \Passbolt\Folders\Controller\FoldersRelations\FoldersRelationsMoveController
 */
class FoldersRelationsMoveControllerTest extends FoldersIntegrationTestCase
{
    use FoldersModelTrait;

    public function testFoldersRelationsMoveSuccess_MoveFolder()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // ---
        // A (Ada:O)
        // B (Ada:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        [$folderA, $folderB] = FolderFactory::make(2)->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA])->persist();

        $this->logInAs($userA);
        $data['folder_parent_id'] = $folderA->id;
        $this->putJson("/move/folder/{$folderB->id}.json?api-version=2", $data);
        $this->assertSuccess();

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);

        // Folder B
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
    }

    public function testFoldersRelationsMoveSuccess_MoveResource()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of Resource R1
        // ---
        // A (Ada:O)
        // R1 (Ada:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA])->persist();
        /** @var \App\Model\Entity\Resource $r1 */
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA])->persist();
        FoldersRelationFactory::make()->root()->foreignModelResource($r1)->user($userA)->persist();

        $this->logInAs($userA);
        $data['folder_parent_id'] = $folderA->id;
        $this->putJson("/move/resource/$r1->id.json?api-version=2", $data);
        $this->assertSuccess();

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);

        // R1
        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->id);
    }

    public function testFoldersRelationsMoveError_NotValidForeignModel()
    {
        $this->logInAsUser();
        $itemId = UuidFactory::uuid();
        $this->putJson("/move/invalid/$itemId.json?api-version=2");
        $this->assertError(400, 'The object type should be one of the following: Folder, Resource.');
    }

    public function testFoldersRelationsMoveError_NotValidForeignId()
    {
        $this->logInAsUser();
        $itemId = 'invalid-id';
        $this->putJson("/move/folder/$itemId.json?api-version=2");
        $this->assertError(400, 'The object identifier should be a valid UUID.');
    }

    public function testFoldersRelationsMoveError_ValidationErrors_FolderParentIdRequired()
    {
        // Ada is OWNER of folder A
        // ---
        // A (Ada:O)
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA])->persist();

        $this->loginAs($userA);
        $this->putJson("/move/folder/{$folderA->id}.json?api-version=2");
        $this->assertError(400, 'Could not validate move data.');
        $arr = $this->getResponseBodyAsArray();
        $error = Hash::get($arr, 'folder_parent_id');
        $this->assertEquals('A folder parent identifier is required.', $error['_required']);
    }

    public function testFoldersRelationsMoveError_ValidationErrors_Uuid()
    {
        // Ada is OWNER of folder A
        // ---
        // A (Ada:O)
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA])->persist();

        $this->loginAs($userA);
        $this->putJson("/move/folder/$folderA->id.json?api-version=2", ['folder_parent_id' => 'invalid-id']);
        $this->assertError(400, 'Could not validate move data.');
        $arr = $this->getResponseBodyAsArray();
        $error = Hash::get($arr, 'folder_parent_id');
        $this->assertEquals('The folder parent identifier should be a valid UUID.', $error['uuid']);
    }

    public function testFoldersRelationsMoveError_CsrfToken()
    {
        $this->disableCsrfToken();
        $this->logInAsUser();
        $folderId = FolderFactory::make()->persist()->get('id');
        $this->put("/move/folder/{$folderId}.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testFoldersRelationsMoveError_NotAuthenticated()
    {
        $folderId = FolderFactory::make()->persist()->get('id');
        $this->putJson("/move/folder/{$folderId}.json?api-version=2", []);
        $this->assertAuthenticationError();
    }

    public function testFoldersRelationsMoveError_FolderDoesNotExist()
    {
        $this->logInAsUser();
        $itemId = UuidFactory::uuid();
        $data['folder_parent_id'] = UuidFactory::uuid();
        $this->putJson("/move/folder/{$itemId}.json?api-version=2", $data);
        $this->assertError(404, 'The object to move does not exist.');
    }

    public function testFoldersRelationsUpdateResourcesError_NoAccessToFolder()
    {
        // Ada is OWNER of folder A
        // ---
        // A (Ada:O)
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA])->persist();

        $this->logInAsUser();
        $data = ['folder_parent_id' => FoldersRelation::ROOT];
        $this->putJson("/move/folder/$folderA->id.json", $data);
        $this->assertError(404, 'The object to move does not exist.');
    }
}
