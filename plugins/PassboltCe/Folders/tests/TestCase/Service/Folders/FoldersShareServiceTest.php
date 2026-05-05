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
use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Folders\FoldersShareService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;

/**
 * Passbolt\Folders\Service\Folders\FoldersShareService Test Case
 *
 * @covers \Passbolt\Folders\Service\Folders\FoldersShareService
 */
class FoldersShareServiceTest extends FoldersTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;

    /**
     * @var FoldersShareService
     */
    private $service;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

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
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
    }

    public function tearDown(): void
    {
        $this->unloadNotificationSettings();
        parent::tearDown();
    }

    /**
     * Fetch the DB permission ID for a given folder + ARO (user or group).
     *
     * @param string $folderId
     * @param string $aroForeignKeyId
     * @return string
     */
    private function getPermissionId(string $folderId, string $aroForeignKeyId): string
    {
        $permission = $this->Permissions
            ->find()
            ->where([
                'aco_foreign_key' => $folderId,
                'aro_foreign_key' => $aroForeignKeyId,
            ])
            ->firstOrFail();

        return $permission->id;
    }

    public function testShareFolderError_FolderNotFound()
    {
        $notExistFolderId = UuidFactory::uuid();
        $userA = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $userA->get('id'));

        $this->expectException(NotFoundException::class);
        $this->service->share($uac, $notExistFolderId);
    }

    public function testShareFolderError_NoAccess()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userB], Permission::READ)
            ->withFoldersRelationsFor([$userB])
            ->persist();

        $this->expectException(ForbiddenException::class);
        $this->service->share($uac, $folderA->id);
    }

    public function testShareFolderError_InsufficientPermission()
    {
        // Ada has access to folder A as a READ
        // Betty has access to folder A as a OWNER
        // A (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userB])
            ->withPermissionsFor([$userA], Permission::READ)
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->expectException(ForbiddenException::class);
        $this->service->share($uac, $folderA->id);
    }

    public function testShareFolderError_AddUser_InvalidPermission()
    {
        // Ada is OWNER of folder A
        // A (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $userNotExistId = UuidFactory::uuid();
        $uac = new UserAccessControl(Role::USER, $userA->get('id'));

        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userNotExistId, 'type' => Permission::READ];

        try {
            $this->service->share($uac, $folderA->id, $data);
            $this->assertFalse(true, 'The test should throw an exception');
        } catch (ValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'permissions.0.aro_foreign_key._existsIn');
        }
    }

    private function assertUpdatePermissionsValidationException(ValidationException $e, string $errorFieldName)
    {
        $this->assertEquals('Could not validate folder data.', $e->getMessage());
        $error = Hash::get($e->getErrors(), $errorFieldName);
        $this->assertNotNull($error, "Expected error not found : {$errorFieldName}. Errors: " . json_encode($e->getErrors()));
    }

    public function testShareFolderSuccess_AddUser()
    {
        // Ada is OWNER of folder A
        // A (Ada:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userB->id, 'type' => Permission::READ];
        $this->service->share($uac, $folderA->id, $data);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertPermission($folderA->id, $userA->id, Permission::OWNER);
        $this->assertPermission($folderA->id, $userB->id, Permission::READ);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id);
    }

    public function testShareFolderSuccess_NotifyUserAfterShare()
    {
        // Ada is OWNER of folder A
        // A (Ada:O)
        $userA = UserFactory::make()->withProfileName('Ada', 'Lovelace')->user()->persist();
        [$userB, $userC] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make(['name' => 'A'])
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->get('id'));

        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userB->id, 'type' => Permission::READ];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userC->id, 'type' => Permission::READ];
        $this->service->share($uac, $folderA->id, $data);

        $this->assertEmailQueueCount(2);
        $this->assertEmailSubject($userB->username, 'Ada shared the folder A');
        $this->assertEmailInBatchContains('Ada shared a folder with you', $userC->username);
        $this->assertEmailSubject($userC->username, 'Ada shared the folder A');
        $this->assertEmailInBatchContains('Ada shared a folder with you', $userB->username);
    }

    public function testShareFolderSuccess_AddGroup()
    {
        // Ada is OWNER of folderA
        // Betty is member of groupA
        // Carol is member of groupA
        // ---
        // A (Ada:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $groupA = GroupFactory::make()->withGroupsManagersFor([$userB, $userC])->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $data['permissions'][] = ['aro' => 'Group', 'aro_foreign_key' => $groupA->get('id'), 'type' => Permission::OWNER];
        $this->service->share($uac, $folderA->id, $data);

        $this->assertPermission($folderA->id, $userA->id, Permission::OWNER);
        $this->assertPermission($folderA->id, $groupA->get('id'), Permission::OWNER);
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
    }

    public function testShareFolderError_RemoveUser_AtLeastOneOwner()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // A (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $data['permissions'][] = ['id' => $this->getPermissionId($folderA->id, $userA->id), 'delete' => true];

        try {
            $this->service->share($uac, $folderA->id, $data);
            $this->assertFalse(true, 'The test should throw an exception');
        } catch (ValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'permissions.at_least_one_owner');
        }
    }

    public function testShareFolderSuccess_RemoveUser()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // A (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $data['permissions'][] = ['id' => $this->getPermissionId($folderA->id, $userA->id), 'type' => Permission::OWNER];
        $data['permissions'][] = ['id' => $this->getPermissionId($folderA->id, $userB->id), 'delete' => true];
        $folder = $this->service->share($uac, $folderA->id, $data);

        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelationNotExist($folder->id, $userB->id);
        $this->assertPermission($folder->id, $userA->id, Permission::OWNER);
        $this->assertPermissionNotExist($folder->id, $userB->id);
        $this->assertItemIsInTrees($folder->id, 1);
    }

    public function testShareFolderSuccess_RemoveGroup()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Betty is member of group G1
        // Carol is member of group G1
        // ---
        // A (Ada:O, G1:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $groupA = GroupFactory::make()->withGroupsManagersFor([$userB, $userC])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $groupA])
            ->withFoldersRelationsFor([$userA, $userB, $userC])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $data['permissions'][] = ['id' => $this->getPermissionId($folderA->id, $groupA->get('id')), 'delete' => true];
        $this->service->share($uac, $folderA->id, $data);

        $this->assertPermission($folderA->id, $userA->id, Permission::OWNER);
        $this->assertPermissionNotExist($folderA->id, $groupA->get('id'));
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
    }

    public function testShareFolderSuccess_UpdateUser()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // ----
        // A (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $data['permissions'][] = ['id' => $this->getPermissionId($folderA->id, $userB->id), 'type' => Permission::OWNER];
        $this->service->share($uac, $folderA->id, $data);

        // Folder A.
        $this->assertPermission($folderA->id, $userA->id, Permission::OWNER);
        $this->assertPermission($folderA->id, $userB->id, Permission::OWNER);
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
    }

    public function testShareFolderSuccess_UpdateGroup()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Betty is member of group G1
        // Carol is member of group G1
        // ---
        // A (Ada:O, G1:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->user()->persist();
        $groupA = GroupFactory::make()->withGroupsManagersFor([$userB, $userC])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $groupA])
            ->withFoldersRelationsFor([$userA, $userB, $userC])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $data['permissions'][] = ['id' => $this->getPermissionId($folderA->id, $groupA->get('id')), 'type' => Permission::READ];
        $this->service->share($uac, $folderA->id, $data);

        // Folder A.
        $this->assertPermission($folderA->id, $userA->id, Permission::OWNER);
        $this->assertPermission($folderA->id, $groupA->get('id'), Permission::READ);
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
    }

    public function testShareFolderSuccess_MoveSelfOrganizedContentToRoot()
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
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB])
            ->persist();
        $resourceB = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userB->id, 'type' => Permission::OWNER];
        $this->service->share($uac, $folderA->id, $data);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertPermission($folderA->id, $userA->id, Permission::OWNER);
        $this->assertPermission($folderA->id, $userB->id, Permission::OWNER);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertPermission($folderB->id, $userA->id, Permission::READ);
        $this->assertPermission($folderB->id, $userB->id, Permission::OWNER);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertPermission($folderC->id, $userA->id, Permission::OWNER);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        // Resource R1
        $this->assertItemIsInTrees($resourceA->get('id'), 2);
        $this->assertPermission($resourceA->get('id'), $userA->id, Permission::READ);
        $this->assertPermission($resourceA->get('id'), $userB->id, Permission::OWNER);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, FoldersRelation::ROOT);
        // Resource R2
        $this->assertItemIsInTrees($resourceB->get('id'), 1);
        $this->assertPermission($resourceB->get('id'), $userA->id, Permission::OWNER);
        $this->assertFolderRelation($resourceB->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->id);
    }

    public function testShareFolderError_UpdateUser_PermissionDoesNotExist()
    {
        // Ada is OWNER of folder A
        // ----
        // A (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->get('id'));

        $data['permissions'][] = ['id' => UuidFactory::uuid(), 'type' => Permission::OWNER];

        try {
            $this->service->share($uac, $folderA->id, $data);
            $this->assertFalse('True', 'The test should throw an exception');
        } catch (ValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'permissions.0.id.exists');
        }
    }

    public function testShareFolderError_UpdateUser_InvalidPermission()
    {
        // Ada is OWNER of folder A
        // ----
        // A (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->get('id'));

        $data['permissions'][] = ['id' => $this->getPermissionId($folderA->id, $userA->get('id')), 'type' => 100000];

        try {
            $this->service->share($uac, $folderA->id, $data);
            $this->assertFalse(true, 'The test should throw an exception');
        } catch (ValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'permissions.0.type.inList');
        }
    }

    public function testShareFolderError_UpdateUser_AtLeastOneOwner()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // A (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $data['permissions'][] = ['id' => $this->getPermissionId($folderA->id, $userA->id), 'type' => Permission::READ];

        try {
            $this->service->share($uac, $folderA->id, $data);
            $this->assertFalse(true, 'The test should throw an exception');
        } catch (ValidationException $e) {
            $this->assertUpdatePermissionsValidationException($e, 'permissions.at_least_one_owner');
        }
    }

    public function testFoldersShareService_Permissions_Not_An_Array_Should_Not_Throw_500()
    {
        $user = UserFactory::make()->user()->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$user])->persist();
        $userBId = UuidFactory::uuid();
        $uac = new UserAccessControl(Role::USER, $user->get('id'));

        $data['permissions'] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::READ];
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The permissions data must be an array.');
        $this->service->share($uac, $folder->get('id'), $data);
    }

    public function testFoldersShareService_Permissions_Key_Not_An_Integer_Should_Not_Throw_500()
    {
        $user = UserFactory::make()->user()->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$user])->persist();
        $userB = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->get('id'));

        $data['permissions'] = ['b' => ['aro' => 'User', 'aro_foreign_key' => $userB->get('id'), 'type' => Permission::READ]];
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The permissions data array keys must be integers.');
        $this->service->share($uac, $folder->get('id'), $data);
    }
}
