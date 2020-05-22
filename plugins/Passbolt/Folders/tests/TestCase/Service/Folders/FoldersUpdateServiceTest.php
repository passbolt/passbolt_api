<?php
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
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\Folders\FoldersUpdateService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
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
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use IntegrationTestTrait;
    use PermissionsModelTrait;

    public $fixtures = [
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        UsersFixture::class,
    ];

    /**
     * @var FoldersTable
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
    public function setUp()
    {
        parent::setUp();
        $this->service = new FoldersUpdateService();
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
    }

    public function testUpdateFolderSuccess_UpdateFolderMeta()
    {
        list($folderA, $userAId) = $this->insertFixture_UpdateFolderMeta();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->update($uac, $folderA->id, ['name' => 'new name']);

        $folderBUpdated = $this->foldersTable->findById($folderA->id)->first();
        $this->assertEquals('new name', $folderBUpdated->name);
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
        $eventNameToTest = FoldersUpdateService::FOLDERS_UPDATE_FOLDER_EVENT;
        $eventWasDispatched = false;

        $callable = function (Event $event) use (&$eventWasDispatched) {
            $this->assertArrayHasKey('folder', $event->getData(), "Event should provide the `folder` entity as event data.");
            $this->assertArrayHasKey('uac', $event->getData(), "Event should provide the `uac` as event data.");
            $eventWasDispatched = true;
        };

        // We use the same instance of event manager that the service is using to test that dispatch is done.
        $this->service->getEventManager()->on($eventNameToTest, $callable);

        list($folderA, $userAId) = $this->insertFixture_UpdateFolderMeta();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->update($uac, $folderA->id, ['name' => 'new name']);

        $this->assertTrue($eventWasDispatched, "Event `$eventNameToTest` was not dispatched after folder was updated with success.");

//        $this->markTestIncomplete("should check that an email has been sent?");
    }

    public function testUpdateFolderError_ValidationError()
    {
        list($folderA, $userAId) = $this->insertFixture_UpdateFolderMeta();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $folderData = ['name' => ''];

        try {
            $this->service->update($uac, $folderA->id, $folderData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals("Could not validate folder data.", $e->getMessage());
            $errors = ['name' => ['_empty' => 'The name cannot be empty.']];
            $this->assertEquals($errors, $e->getErrors());
        }
    }

    public function testUpdateFolderError_InsufficientPermission()
    {
        list($folderA, $userAId, $userBId) = $this->insertFixture_InsufficientPermission();
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
        // A (Ada:O)
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
        list($folderA, $userAId) = $this->insertFixture_UpdateFolderMeta();
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userBId);

        $this->expectException(NotFoundException::class);
        $this->service->update($uac, $folderA->id, ['name' => 'new name']);
    }
}
