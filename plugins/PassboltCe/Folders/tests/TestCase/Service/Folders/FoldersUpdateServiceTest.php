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
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UuidFactory;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\Folders\Service\Folders\FoldersUpdateService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;

/**
 * Passbolt\Folders\Service\FoldersUpdateService Test Case
 *
 * @covers \Passbolt\Folders\Service\Folders\FoldersUpdateService
 */
class FoldersUpdateServiceTest extends FoldersTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use PermissionsModelTrait;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    private $foldersTable;

    /**
     * @var FoldersUpdateService
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

        $this->service = new FoldersUpdateService();
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
    }

    public function tearDown(): void
    {
        $this->unloadNotificationSettings();
        parent::tearDown();
    }

    public function testUpdateFolderSuccess_UpdateFolderMeta()
    {
        // Ada is OWNER of folder A
        // ---
        // A (Ada:O)
        $userA = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();

        $this->service->update($this->makeUac($userA), $folderA->get('id'), MetadataFolderDto::fromArray(['name' => 'new name']));

        $folderBUpdated = $this->foldersTable->findById($folderA->get('id'))->first();
        $this->assertEquals('new name', $folderBUpdated->get('name'));
    }

    public function testUpdateFolderSuccess_NotifyUserAfterUpdate()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // ---
        // A (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();

        $name = 'new name';
        $dto = MetadataFolderDto::fromArray(['name' => $name]);
        $this->service->update($this->makeUac($userA), $folderA->get('id'), $dto);

        $this->assertEmailQueueCount(2);
        $this->assertEmailSubject($userA->username, "You edited the folder $name");
        $this->assertEmailInBatchContains('You edited a folder', $userA->username);
        $this->assertEmailSubject($userB->username, "{$userA->profile->first_name} edited the folder $name");
        $this->assertEmailInBatchContains("{$userA->profile->first_name} edited a folder", $userB->username);
    }

    public function testUpdateFolderError_ValidationError()
    {
        // Ada is OWNER of folder A
        // ---
        // A (Ada:O)
        $userA = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();

        $folderData = ['name' => ''];

        try {
            $this->service->update($this->makeUac($userA), $folderA->get('id'), MetadataFolderDto::fromArray($folderData));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate folder data.', $e->getMessage());
            $errors = ['name' => ['_empty' => 'The name should not be empty.']];
            $this->assertEquals($errors, $e->getErrors());
        }
    }

    public function testUpdateFolderError_InsufficientPermission()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // ---
        // A (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();

        $this->expectException(ForbiddenException::class);
        $this->service->update($this->makeUac($userB), $folderA->get('id'), MetadataFolderDto::fromArray(['name' => 'new name']));
    }

    public function testUpdateFolderError_DoesNotExist()
    {
        $userA = UserFactory::make()->persist();
        $notExistFolderId = UuidFactory::uuid();

        $this->expectException(NotFoundException::class);
        $this->service->update($this->makeUac($userA), $notExistFolderId, MetadataFolderDto::fromArray(['name' => 'new name']));
    }

    public function testUpdateFolderError_NoAccessToFolder()
    {
        // Ada is OWNER of folder A
        // Betty has NO permissions on folder A
        // ---
        // A (Ada:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();

        $this->expectException(NotFoundException::class);
        $this->service->update($this->makeUac($userB), $folderA->get('id'), MetadataFolderDto::fromArray(['name' => 'new name']));
    }
}
