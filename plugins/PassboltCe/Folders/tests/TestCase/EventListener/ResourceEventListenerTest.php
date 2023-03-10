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

namespace Passbolt\Folders\Test\TestCase\EventListener;

use App\Model\Entity\Permission;
use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Fixture\Alt0\SecretsFixture;
use App\Test\Fixture\Base\FavoritesFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\ResourceTypesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * \Passbolt\Folders\EventListener\ResourcesEventListener Test Case
 *
 * @covers \Passbolt\Folders\EventListener\ResourcesEventListener
 */
class ResourceEventListenerTest extends FoldersIntegrationTestCase
{
    use EmailQueueTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use ResourcesModelTrait;
    use SecretsModelTrait;
    use EmailNotificationSettingsTestTrait;

    public $fixtures = [
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
        GroupsFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        UsersFixture::class,
        FavoritesFixture::class,
        RolesFixture::class,
        SecretsFixture::class,
        ResourcesFixture::class,
        ResourceTypesFixture::class,
    ];

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    public function setUp(): void
    {
        parent::setUp();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
    }

    public function testFoldersResourcesEventListenerSuccess_AfterResourceAdded()
    {
        $this->loadNotificationSettings();
        $this->setEmailNotificationSetting('send.password.create', true);

        [$userId, $folder] = $this->insertFixture_AfterResourceAdded();
        $data = [
            'name' => 'R1',
            'folder_parent_id' => $folder->id,
            'secrets' => [$this->getDummySecretData(['resource_id' => null])],
        ];

        $this->authenticateAs('ada');
        $this->postJson('/resources.json?api-version=v2', $data);
        $this->assertSuccess();

        $resource = $this->_responseJsonBody;
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userId, $folder->id);
        $this->assertEmailIsInQueue([
            'email' => 'ada@passbolt.com',
            'subject' => 'You added the password ' . $data['name'],
            'template' => ResourceCreateEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailQueueCount(1);
        $this->unloadNotificationSettings();
    }

    public function testFoldersResourcesEventListenerError_AfterResourceAdded()
    {
        $this->loadNotificationSettings();
        $this->setEmailNotificationSetting('send.password.create', true);

        $nResources = ResourceFactory::count();
        $nPermissions = PermissionFactory::count();
        $nSecrets = SecretFactory::count();
        $nFolders = FolderFactory::count();
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        $data = [
            'name' => 'R1',
            'folder_parent_id' => UuidFactory::uuid(),
            'secrets' => [$this->getDummySecretData(['resource_id' => null])],
        ];

        $this->postJson('/resources.json?api-version=v2', $data);
        $this->assertError(400, 'Could not validate resource data');

        $this->assertSame($nResources, ResourceFactory::count());
        $this->assertSame($nPermissions, PermissionFactory::count());
        $this->assertSame($nSecrets, SecretFactory::count());
        $this->assertSame($nFolders, FolderFactory::count());
        $this->assertEmailQueueIsEmpty();
        $this->unloadNotificationSettings();
    }

    public function insertFixture_AfterResourceAdded()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor([], [$userId => Permission::OWNER]);
        $folder = $this->addFolderFor(['folder_parent_id' => $folder->id], [$userId => Permission::OWNER]);

        return [$userId, $folder];
    }

    public function testFoldersResourcesEventListenerSuccess_AfterResourceSoftDeleted()
    {
        [$userId, $resource] = $this->insertFixture_AfterResourceSoftDeleted();
        $data = $resource->toArray();
        $data['folder_parent_id'] = null;

        $this->authenticateAs('ada');
        $this->deleteJson("/resources/{$resource->id}.json?api-version=v2", $data);
        $this->assertSuccess();

        $this->assertFolderRelationNotExist($resource->id, $userId);
    }

    public function insertFixture_AfterResourceSoftDeleted()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $folder = $this->addFolderFor([], [$userId => Permission::OWNER]);
        $resource = $this->addResourceFor(['folder_parent_id' => $folder->id], [$userId => Permission::OWNER]);

        return [$userId, $resource];
    }

    public function testFoldersResourcesEventListenerSuccess_AfterAccessGranted()
    {
        [$folder, $resource, $userAId, $userBId] = $this->insertFixture_AfterAccessGranted();

        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userBId, 'type' => Permission::OWNER];
        $data['secrets'][] = ['user_id' => $userBId, 'data' => Hash::get($this->getDummySecretData(), 'data')];

        $this->authenticateAs('ada');
        /*
         * If not cleared, the folderizable behavior cannot be applied on the ResourcesTable as the ResourcesTable is already in the registry.
         * Error: Unknown finder method "folder_parent" on App\Model\Table\ResourcesTable.
         */
        TableRegistry::getTableLocator()->clear();
        $this->putJson("/share/resource/{$resource->id}.json", $data);
        $this->assertSuccess();

        $this->assertItemIsInTrees($resource->id, 2);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folder->id);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folder->id);
    }

    public function insertFixture_AfterAccessGranted()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor([], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $resource = $this->addResourceFor(['folder_parent_id' => $folder->id], [$userAId => Permission::OWNER]);

        return [$folder, $resource, $userAId, $userBId];
    }

    public function testFoldersResourcesEventListenerSuccess_AfterAccessRevoked()
    {
        [$folder, $resource, $userAId, $userBId] = $this->insertFixture_AfterAccessRevoked();

        $permission = $this->permissionsTable->findByAcoForeignKeyAndAroForeignKey($resource->id, $userBId)->first();
        $data['permissions'][] = ['id' => $permission->get('id'), 'delete' => true];

        $this->authenticateAs('ada');
        /*
         * If not cleared, the folderizable behavior cannot be applied on the ResourcesTable as the ResourcesTable is already in the registry.
         * Error: Unknown finder method "folder_parent" on App\Model\Table\ResourcesTable.
         */
        TableRegistry::getTableLocator()->clear();
        $this->putJson("/share/resource/{$resource->id}.json", $data);
        $this->assertSuccess();

        $this->assertItemIsInTrees($resource->id, 1);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folder->id);
    }

    public function insertFixture_AfterAccessRevoked()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folder = $this->addFolderFor([], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $resource = $this->addResourceFor(['folder_parent_id' => $folder->id], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folder, $resource, $userAId, $userBId];
    }
}
