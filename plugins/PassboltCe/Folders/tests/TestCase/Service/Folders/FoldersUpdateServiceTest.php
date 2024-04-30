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
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\Folders\Service\Folders\FoldersUpdateService;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

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

    public $fixtures = [
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        RolesFixture::class,
        UsersFixture::class,
    ];

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
        [$folderA, $userAId] = $this->insertFixture_UpdateFolderMeta();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->update($uac, $folderA->id, ['name' => 'new name']);

        $folderBUpdated = $this->foldersTable->findById($folderA->id)->first();
        $this->assertEquals('new name', $folderBUpdated->get('name'));
    }

    private function insertFixture_UpdateFolderMeta()
    {
        // Ada is OWNER of folder A
        // ---
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);

        return [$folderA, $userAId];
    }

    public function testUpdateFolderSuccess_NotifyUserAfterUpdate()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_InsufficientPermission();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $name = 'new name';
        $this->service->update($uac, $folderA->id, compact('name'));

        $this->assertEmailQueueCount(2);
        $this->assertEmailSubject('ada@passbolt.com', "You edited the folder $name");
        $this->assertEmailInBatchContains('You edited a folder', 'ada@passbolt.com');
        $this->assertEmailSubject('betty@passbolt.com', "Ada edited the folder $name");
        $this->assertEmailInBatchContains('Ada edited a folder', 'betty@passbolt.com');
    }

    public function testUpdateFolderError_ValidationError()
    {
        [$folderA, $userAId] = $this->insertFixture_UpdateFolderMeta();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folderData = ['name' => ''];

        try {
            $this->service->update($uac, $folderA->id, $folderData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate folder data.', $e->getMessage());
            $errors = ['name' => ['_empty' => 'The name should not be empty.']];
            $this->assertEquals($errors, $e->getErrors());
        }
    }

    public function testUpdateFolderError_InsufficientPermission()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_InsufficientPermission();
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userBId);

        $this->expectException(ForbiddenException::class);
        $this->service->update($uac, $folderA->id, ['name' => 'new name']);
    }

    private function insertFixture_InsufficientPermission()
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

    public function testUpdateFolderError_DoesNotExist()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $notExistFolderId = UuidFactory::uuid();

        $this->expectException(NotFoundException::class);
        $this->service->update($uac, $notExistFolderId, ['name' => 'new name']);
    }

    public function testUpdateFolderError_NoAccessToFolder()
    {
        [$folderA, $userAId] = $this->insertFixture_UpdateFolderMeta();
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userBId);

        $this->expectException(NotFoundException::class);
        $this->service->update($uac, $folderA->id, ['name' => 'new name']);
    }
}
