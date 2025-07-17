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
use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\Event\EventDispatcherTrait;
use Cake\Utility\Hash;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Folders\FoldersCreateService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;

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
        $userA = UserFactory::make()->persist();
        $folderData = MetadataFolderDto::fromArray(['name' => '']);

        try {
            $this->service->create($this->makeUac($userA), $folderData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate folder data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'name._empty'));
        }
    }

    public function testCreateFolder_CommonError2_ParentFolderNotExist()
    {
        $userA = UserFactory::make()->persist();
        $folderData = MetadataFolderDto::fromArray([
            'name' => 'B',
            'folder_parent_id' => UuidFactory::uuid('folder.id.not-exist'),
        ]);

        try {
            $this->service->create($this->makeUac($userA), $folderData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate folder data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.folder_exists'));
        }
    }

    public function testCreateFolder_CommonError3_ParentFolderNoPermission()
    {
        // Betty is OWNER of folder A
        // A (Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userB])->withFoldersRelationsFor([$userB])->persist();

        $folderData = MetadataFolderDto::fromArray(['name' => 'B', 'folder_parent_id' => $folderA->id]);

        try {
            $this->service->create($this->makeUac($userA), $folderData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate folder data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    public function testCreateFolder_CommonSuccess_NotifyUserAfterCreate()
    {
        $this->loadNotificationSettings();
        $this->setEmailNotificationSetting('send.folder.create', true);
        (new EmailSubscriptionDispatcher())->collectSubscribedEmailRedactors();

        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $folderData = MetadataFolderDto::fromArray(['name' => 'A']);
        $this->service->create($this->makeUac($userA), $folderData);

        $this->assertEmailIsInQueue([
            'email' => $userA->username,
            'subject' => 'You added the folder A',
            'template' => 'Passbolt/Folders.LU/folder_create',
        ]);
        $this->assertEmailInBatchContains('You have created a new folder');
        $this->unloadNotificationSettings();
    }

    /* PERSONAL FOLDER */

    public function testCreateFolder_PersoSuccess1_CreateToRoot()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $folderData = MetadataFolderDto::fromArray(['name' => 'A']);
        $folder = $this->service->create($this->makeUac($userA), $folderData);

        $this->assertEquals('A', $folder->name);
        $this->assertEquals(null, $folder->folder_parent_id);
        $this->assertEquals($userA->id, $folder->created_by);
        $this->assertEquals($userA->id, $folder->modified_by);
        $this->assertPermission($folder->id, $userA->id, Permission::OWNER);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id);
    }

    public function testCreateFolder_PersoSuccess2_CreateInFolder()
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA])->persist();

        $folderData = MetadataFolderDto::fromArray(['name' => 'B', 'folder_parent_id' => $folderA->id]);
        $folder = $this->service->create($this->makeUac($userA), $folderData);

        $this->assertEquals('B', $folder->name);
        $this->assertEquals($folderA->id, $folder->folder_parent_id);
        $this->assertEquals($userA->id, $folder->created_by);
        $this->assertEquals($userA->id, $folder->modified_by);
        $this->assertPermission($folder->id, $userA->id, Permission::OWNER);
        $this->assertFolderRelation($folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
    }

    /* SHARED FOLDER */

    public function testCreateFolder_SharedError1_ParentFolderInsufficientPermission()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // A (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userB])
            ->withPermissionsFor([$userA], Permission::READ)
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        $folderData = MetadataFolderDto::fromArray(['name' => 'B', 'folder_parent_id' => $folderA->id]);

        try {
            $this->service->create($this->makeUac($userA), $folderData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate folder data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    public function testCreateFolder_SharedSuccess1_CreateInFolder()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // A (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        $folderData = MetadataFolderDto::fromArray(['name' => 'B', 'folder_parent_id' => $folderA->id]);
        $folderB = $this->service->create($this->makeUac($userA), $folderData);

        $this->assertEquals('B', $folderB->name);
        $this->assertEquals($folderA->id, $folderB->folder_parent_id);
        $this->assertEquals($userA->id, $folderB->created_by);
        $this->assertEquals($userA->id, $folderB->modified_by);
        $this->assertPermission($folderB->id, $userA->id, Permission::OWNER);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertPermissionNotExist($folderB->id, $userB->id);
        $this->assertFolderRelationNotExist($folderB->id, $userB->id, $folderA->id);
    }
}
