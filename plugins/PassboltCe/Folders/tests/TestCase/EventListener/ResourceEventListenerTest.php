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
use App\Test\Factory\RoleFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * \Passbolt\Folders\EventListener\ResourcesEventListener Test Case
 *
 * @covers \Passbolt\Folders\EventListener\ResourcesEventListener
 */
class ResourceEventListenerTest extends FoldersIntegrationTestCase
{
    use EmailQueueTrait;
    use FoldersModelTrait;
    use EmailNotificationSettingsTestTrait;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testFoldersResourcesEventListenerSuccess_AfterResourceAdded()
    {
        $this->loadNotificationSettings();
        $this->setEmailNotificationSetting('send.password.create', true);

        ResourceTypeFactory::make()->default()->persist();
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$userA])->persist();
        FoldersRelationFactory::make()->user($userA)->folderParent($folder)->persist();

        $data = [
            'name' => 'R1',
            'folder_parent_id' => $folder->get('id'),
            'secrets' => [$this->getDummySecretData(['resource_id' => null])],
        ];

        $this->logInAs($userA);
        $this->postJson('/resources.json?api-version=v2', $data);
        $this->assertSuccess();

        $resource = $this->_responseJsonBody;
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folder->get('id'));
        $this->assertEmailIsInQueue([
            'email' => $userA->username,
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

    public function testFoldersResourcesEventListenerSuccess_AfterResourceSoftDeleted()
    {
        RoleFactory::make()->guest()->persist();
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA])->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withSecretsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folder)
            ->persist();

        $data = $resource->toArray();
        $data['folder_parent_id'] = null;

        $this->logInAs($userA);
        $this->deleteJson("/resources/{$resource->id}.json?api-version=v2", $data);
        $this->assertSuccess();

        $this->assertFolderRelationNotExist($resource->id, $userA->id);
    }

    public function testFoldersResourcesEventListenerSuccess_AfterAccessGranted()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB])->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withSecretsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folder)
            ->persist();

        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userB->id, 'type' => Permission::OWNER];
        $data['secrets'][] = ['user_id' => $userB->id, 'data' => Hash::get($this->getDummySecretData(), 'data')];

        $this->logInAs($userA);
        /*
         * If not cleared, the folderizable behavior cannot be applied on the ResourcesTable as the ResourcesTable is already in the registry.
         * Error: Unknown finder method "folder_parent" on App\Model\Table\ResourcesTable.
         */
        TableRegistry::getTableLocator()->clear();
        $this->putJson("/share/resource/{$resource->id}.json", $data);
        $this->assertSuccess();

        $this->assertItemIsInTrees($resource->id, 2);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folder->get('id'));
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folder->get('id'));
    }

    public function testFoldersResourcesEventListenerSuccess_AfterAccessRevoked()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB])->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withSecretsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB], $folder)
            ->persist();

        $permission = $resource->permissions[1];
        $data['permissions'][] = ['id' => $permission->get('id'), 'delete' => true];

        $this->loginAs($userA);
        /*
         * If not cleared, the folderizable behavior cannot be applied on the ResourcesTable as the ResourcesTable is already in the registry.
         * Error: Unknown finder method "folder_parent" on App\Model\Table\ResourcesTable.
         */
        TableRegistry::getTableLocator()->clear();
        $this->putJson("/share/resource/{$resource->id}.json", $data);
        $this->assertSuccess();

        $this->assertItemIsInTrees($resource->id, 1);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folder->get('id'));
    }
}
