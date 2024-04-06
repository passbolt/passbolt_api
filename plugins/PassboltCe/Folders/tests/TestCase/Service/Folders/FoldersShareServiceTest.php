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

namespace Passbolt\Folders\Test\TestCase\Service\Folders;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Folders\FoldersShareService;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\Folders\FoldersShareService Test Case
 *
 * @covers \Passbolt\Folders\Service\Folders\FoldersShareService
 */
class FoldersShareServiceTest extends FoldersTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use PermissionsModelTrait;

    public $fixtures = [
    GpgkeysFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        ResourcesFixture::class,
        RolesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    /**
     * @var FoldersShareService
     */
    private $service;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadNotificationSettings();
        (new EmailSubscriptionDispatcher())->collectSubscribedEmailRedactors();

        $this->service = new FoldersShareService();
    }

    public function tearDown(): void
    {
        $this->unloadNotificationSettings();
        parent::tearDown();
    }

    public function testShareFolderError_FolderNotFound()
    {
        $notExistFolderId = UuidFactory::uuid();
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $this->expectException(NotFoundException::class);
        $this->service->share($uac, $notExistFolderId);
    }

    public function testShareFolderError_NoAccess()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folder = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::READ]);

        $this->expectException(ForbiddenException::class);
        $this->service->share($uac, $folder->id);
    }

    public function testShareFolderError_InsufficientPermission()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_ShareFolderError_InsufficientPermission();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->expectException(ForbiddenException::class);
        $this->service->share($uac, $folderA->id);
    }

    public function insertFixture_ShareFolderError_InsufficientPermission()
    {
        // Ada has access to folder A as a READ
        // Betty has access to folder A as a OWNER
        // A (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::UPDATE, $userBId => Permission::OWNER]);

        return [$folderA, $userAId, $userBId];
    }

    public function testShareFolderError_AddUser_InvalidPermission()
    {
        [$folderA, $userAId] = $this->insertFixture_ShareFolderError_AddUser_InvalidPermission();
        $userNotExistId = UuidFactory::uuid();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userNotExistId, 'type' => Permission::READ];

        try {
            $this->service->share($uac, $folderA->id, $data);
            $this->assertFalse(true, 'The test should throw an exception');
        } catch (ValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'permissions.0.aro_foreign_key._existsIn');
        }
    }

    public function insertFixture_ShareFolderError_AddUser_InvalidPermission()
    {
        // Ada is OWNER of folder A
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);

        return [$folderA, $userAId];
    }

    private function assertUpdatePermissionsValidationException(ValidationException $e, string $errorFieldName)
    {
        $this->assertEquals('Could not validate folder data.', $e->getMessage());
        $error = Hash::get($e->getErrors(), $errorFieldName);
        $this->assertNotNull($error, "Expected error not found : {$errorFieldName}. Errors: " . json_encode($e->getErrors()));
    }

    public function testShareFolderSuccess_AddUser()
    {
        [$folderA, $userAId] = $this->insertFixture_ShareFolderSuccess_AddUser();
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::READ];
        $this->service->share($uac, $folderA->id, $data);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userBId, Permission::READ);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId);
    }

    public function insertFixture_ShareFolderSuccess_AddUser()
    {
        // Ada is OWNER of folder A
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);

        return [$folderA, $userAId];
    }

    public function testShareFolderSuccess_NotifyUserAfterShare()
    {
        [$folderA, $userAId] = $this->insertFixture_ShareFolderSuccess_AddUser();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::READ];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userCId, 'type' => Permission::READ];
        $this->service->share($uac, $folderA->id, $data);

        $this->assertEmailQueueCount(2);
        $this->assertEmailSubject('betty@passbolt.com', 'Ada shared the folder A');
        $this->assertEmailInBatchContains('Ada shared a folder with you', 'carol@passbolt.com');
        $this->assertEmailSubject('carol@passbolt.com', 'Ada shared the folder A');
        $this->assertEmailInBatchContains('Ada shared a folder with you', 'betty@passbolt.com');
    }

    public function testShareFolderSuccess_AddGroup()
    {
        [$folderA, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_ShareFolderSuccess_AddGroup();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['aro' => 'Group', 'aro_foreign_key' => $g1->id, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderA->id, $data);

        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderA->id, $g1->id, Permission::OWNER);
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
    }

    public function insertFixture_ShareFolderSuccess_AddGroup()
    {
        // Ada is OWNER of folder A
        // Betty is member of group G1
        // Carol is member of group G1
        // ---
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userBId, 'is_admin' => true],
            ['user_id' => $userCId, 'is_admin' => true],
        ]]);

        return [$folderA, $g1, $userAId, $userBId, $userCId];
    }

    public function testShareFolderError_RemoveUser_AtLeastOneOwner()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_ShareFolderError_RemoveUser_AtLeastOneOwner();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userAId}"), 'delete' => true];

        try {
            $this->service->share($uac, $folderA->id, $data);
            $this->assertFalse(true, 'The test should throw an exception');
        } catch (ValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'permissions.at_least_one_owner');
        }
    }

    public function insertFixture_ShareFolderError_RemoveUser_AtLeastOneOwner()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // A (Ada:O, Betty:R)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);

        return [$folderA, $userAId, $userBId];
    }

    public function testShareFolderSuccess_RemoveUser()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_ShareFolderSuccess_RemoveUser();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userAId}"), 'type' => Permission::OWNER];
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userBId}"), 'delete' => true];
        $folder = $this->service->share($uac, $folderA->id, $data);

        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelationNotExist($folder->id, $userBId);
        $this->assertPermission($folder->id, $userAId, Permission::OWNER);
        $this->assertPermissionNotExist($folder->id, $userBId);
        $this->assertItemIsInTrees($folder->id, 1);
    }

    public function insertFixture_ShareFolderSuccess_RemoveUser()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // A (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $userAId, $userBId];
    }

    public function testShareFolderSuccess_RemoveGroup()
    {
        [$folderA, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_ShareFolderSuccess_RemoveGroup();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$g1->id}"), 'delete' => true];
        $folder = $this->service->share($uac, $folderA->id, $data);

        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermissionNotExist($folderA->id, $g1->id);
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
    }

    public function insertFixture_ShareFolderSuccess_RemoveGroup()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Betty is member of group G1
        // Carol is member of group G1
        // ---
        // A (Ada:O, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userBId, 'is_admin' => true],
            ['user_id' => $userCId, 'is_admin' => true],
        ]]);
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$folderA, $g1, $userAId, $userBId, $userCId];
    }

    public function testShareFolderSuccess_UpdateUser()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_ShareFolderSuccess_UpdateUser();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userBId}"), 'type' => Permission::OWNER];
        $this->service->share($uac, $folderA->id, $data);

        // Folder A.
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
    }

    public function insertFixture_ShareFolderSuccess_UpdateUser()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // ----
        // A (Ada:O, Betty:R)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);

        return [$folderA, $userAId, $userBId];
    }

    public function testShareFolderSuccess_UpdateGroup()
    {
        [$folderA, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_ShareFolderSuccess_UpdateGroup();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$g1->id}"), 'type' => Permission::READ];
        $folder = $this->service->share($uac, $folderA->id, $data);

        // Folder A.
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderA->id, $g1->id, Permission::READ);
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
    }

    public function insertFixture_ShareFolderSuccess_UpdateGroup()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Betty is member of group G1
        // Carol is member of group G1
        // ---
        // A (Ada:O, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userBId, 'is_admin' => true],
            ['user_id' => $userCId, 'is_admin' => true],
        ]]);
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$folderA, $g1, $userAId, $userBId, $userCId];
    }

    public function testShareFolderSuccess_MoveSelfOrganizedContentToRoot()
    {
        [$folderA, $folderB, $folderC, $r1, $r2, $userAId, $userBId] = $this->insertFixture_ShareFolderSuccess_MoveSelfOrganizedContentToRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $folder = $this->service->share($uac, $folderA->id, $data);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertPermission($folderB->id, $userAId, Permission::READ);
        $this->assertPermission($folderB->id, $userBId, Permission::OWNER);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertPermission($folderC->id, $userAId, Permission::OWNER);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        // Resource R1
        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertPermission($r1->id, $userAId, Permission::READ);
        $this->assertPermission($r1->id, $userBId, Permission::OWNER);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, FoldersRelation::ROOT);
        // Resource R2
        $this->assertItemIsInTrees($r2->id, 1);
        $this->assertPermission($r2->id, $userAId, Permission::OWNER);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
    }

    public function insertFixture_ShareFolderSuccess_MoveSelfOrganizedContentToRoot()
    {
        // Ada is OWNER of folder A
        // Ada has READ on folder B
        // Betty is OWNER of folder B
        // Ada is OWNER of folder C
        // Ada has READ on resource R1
        // Betty is OWNER on resource R1
        // Ada is OWNER of resource R2
        // Ada sees B in A
        // Ada sees C in A
        // Ada sees R1 in A
        // Ada sees R2 in A
        // ----
        // A (Ada:O)
        // |- B (Ada:R, Betty:O)
        // |- C (Ada:O)
        // |- R1 (Ada:R, Betty:O)
        // |- R2 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        // Folder B
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, 'User', $userAId, Permission::READ);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder C
        $folderC = $this->addFolderFor(['name' => 'C', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);
        // Resource R1
        $r1 = $this->addResource(['name' => 'R1']);
        $this->addPermission(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id, 'User', $userAId, Permission::READ);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $r1->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addPermission(FoldersRelation::FOREIGN_MODEL_RESOURCE, $r1->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $r1->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Resource R2
        $r2 = $this->addResourceFor(['name' => 'r2', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC, $r1, $r2, $userAId, $userBId];
    }

    public function testShareFolderError_UpdateUser_PermissionDoesNotExist()
    {
        [$folderA, $userAId] = $this->insertFixture_ShareFolderError_UpdateUser_PermissionDoesNotExist();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid(), 'type' => Permission::OWNER];

        try {
            $this->service->share($uac, $folderA->id, $data);
            $this->assertFalse('True', 'The test should throw an exception');
        } catch (ValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'permissions.0.id.exists');
        }
    }

    public function insertFixture_ShareFolderError_UpdateUser_PermissionDoesNotExist()
    {
        // Ada is OWNER of folder A
        // ----
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);

        return [$folderA, $userAId];
    }

    public function testShareFolderError_UpdateUser_InvalidPermission()
    {
        [$folderA, $userAId] = $this->insertFixture_ShareFolderError_UpdateUser_InvalidPermission();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userAId}"), 'type' => 100000];

        try {
            $this->service->share($uac, $folderA->id, $data);
            $this->assertFalse(true, 'The test should throw an exception');
        } catch (ValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'permissions.0.type.inList');
        }
    }

    public function insertFixture_ShareFolderError_UpdateUser_InvalidPermission()
    {
        // Ada is OWNER of folder A
        // ----
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);

        return [$folderA, $userAId];
    }

    public function testShareFolderError_UpdateUser_AtLeastOneOwner()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_ShareFolderError_UpdateUser_AtLeastOneOwner();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.{$folderA->id}-{$userAId}"), 'type' => Permission::READ];

        try {
            $this->service->share($uac, $folderA->id, $data);
            $this->assertFalse(true, 'The test should throw an exception');
        } catch (ValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'permissions.at_least_one_owner');
        }
    }

    public function insertFixture_ShareFolderError_UpdateUser_AtLeastOneOwner()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // A (Ada:O, Betty:R)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);

        return [$folderA, $userAId, $userBId];
    }
}
