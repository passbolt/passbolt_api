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
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Event\EventDispatcherTrait;
use Cake\Utility\Hash;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Folders\FoldersCreateService;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\Folders\FoldersCreateService Test Case
 *
 * @uses \Passbolt\Folders\Service\Folders\FoldersCreateService
 */
class FoldersCreateServiceTest extends FoldersTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;
    use EventDispatcherTrait;
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
     * @var FoldersCreateService
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
        /** @var FoldersCreateService $service */
        $this->service = new FoldersCreateService();
    }

    /* COMMON & VALIDATION */

    public function testCreateFolder_CommonError1_ValidationError()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => ''];

        try {
            $this->service->create($uac, $folderData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate folder data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'name._empty'));
        }
    }

    public function testCreateFolder_CommonError2_ParentFolderNotExist()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = [
            'name' => 'B',
            'folder_parent_id' => UuidFactory::uuid('folder.id.not-exist'),
        ];

        try {
            $this->service->create($uac, $folderData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate folder data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.folder_exists'));
        }
    }

    public function testCreateFolder_CommonError3_ParentFolderNoPermission()
    {
        [$parentFolder] = $this->insertCommonError3Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => 'B', 'folder_parent_id' => $parentFolder->id];

        try {
            $this->service->create($uac, $folderData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate folder data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    private function insertCommonError3Fixture()
    {
        // Betty is OWNER of folder A
        // A (Betty:O)
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::OWNER]);

        return [$folderA];
    }

    public function testCreateFolder_CommonSuccess_NotifyUserAfterCreate()
    {
        $this->loadNotificationSettings();
        $this->setEmailNotificationSetting('send.folder.create', true);
        (new EmailSubscriptionDispatcher())->collectSubscribedEmailRedactors();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => 'A'];
        $this->service->create($uac, $folderData);

        $this->assertEmailIsInQueue([
            'email' => 'ada@passbolt.com',
            'subject' => 'You added the folder A',
            'template' => 'Passbolt/Folders.LU/folder_create',
        ]);
        $this->assertEmailInBatchContains('You have created a new folder');
        $this->unloadNotificationSettings();
    }

    /* PERSONAL FOLDER */

    public function testCreateFolder_PersoSuccess1_CreateToRoot()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => 'A'];
        $folder = $this->service->create($uac, $folderData);

        $this->assertEquals('A', $folder->name);
        $this->assertEquals(null, $folder->folder_parent_id);
        $this->assertEquals($userId, $folder->created_by);
        $this->assertEquals($userId, $folder->modified_by);
        $this->assertPermission($folder->id, $userId, Permission::OWNER);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId);
    }

    public function testCreateFolder_PersoSuccess2_CreateInFolder()
    {
        $parentFolder = $this->insertPersoSuccess2Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => 'B', 'folder_parent_id' => $parentFolder->id];
        $folder = $this->service->create($uac, $folderData);

        $this->assertEquals('B', $folder->name);
        $this->assertEquals($parentFolder->id, $folder->folder_parent_id);
        $this->assertEquals($userId, $folder->created_by);
        $this->assertEquals($userId, $folder->modified_by);
        $this->assertPermission($folder->id, $userId, Permission::OWNER);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userId, $parentFolder->id);
    }

    private function insertPersoSuccess2Fixture()
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);

        return $folderA;
    }

    /* SHARED FOLDER */

    public function testCreateFolder_SharedError1_ParentFolderInsufficientPermission()
    {
        [$parentFolder] = $this->insertSharedError1Fixture();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $folderData = ['name' => 'B', 'folder_parent_id' => $parentFolder->id];

        try {
            $this->service->create($uac, $folderData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate folder data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    private function insertSharedError1Fixture()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // A (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folderA];
    }

    public function testCreateFolder_SharedSuccess1_CreateInFolder()
    {
        [$folderA] = $this->insertSharedSuccess1Fixture();

        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folderData = ['name' => 'B', 'folder_parent_id' => $folderA->id];
        $folderB = $this->service->create($uac, $folderData);

        $this->assertEquals('B', $folderB->name);
        $this->assertEquals($folderA->id, $folderB->folder_parent_id);
        $this->assertEquals($userAId, $folderB->created_by);
        $this->assertEquals($userAId, $folderB->modified_by);
        $this->assertPermission($folderB->id, $userAId, Permission::OWNER);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertPermissionNotExist($folderB->id, $userBId);
        $this->assertFolderRelationNotExist($folderB->id, $userBId, $folderA->id);
    }

    private function insertSharedSuccess1Fixture()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // A (Ada:O, Betty:R)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);

        return [$folderA];
    }
}
