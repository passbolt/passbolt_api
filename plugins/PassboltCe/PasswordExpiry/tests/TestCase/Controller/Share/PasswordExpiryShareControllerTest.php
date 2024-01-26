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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Test\TestCase\Controller\Share;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;
use Passbolt\PasswordExpiry\Test\Lib\PasswordExpiryTestTrait;

class PasswordExpiryShareControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;
    use PasswordExpiryTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(PasswordExpiryPlugin::class);
    }

    public function testPasswordExpiryShareController_Remove_Permission_Should_Expire_Only_If_Password_Was_Viewed(): void
    {
        PasswordExpirySettingFactory::make()->persist();

        // Define actors of this tests
        $allUsers = [$owner, $editor, $viewer, $userLosingPermission] = UserFactory::make(4)
            ->active()
            ->user()
            ->persist();
        [$resourceViewed, $resourceNotViewed] = ResourceFactory::make(2)
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$editor], Permission::UPDATE)
            ->withPermissionsFor([$viewer], Permission::READ)
            ->withPermissionsFor([$userLosingPermission], Permission::READ)
            ->withSecretsFor($allUsers)
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userLosingPermission))
            ->withResources(ResourceFactory::make($resourceViewed))
            ->persist();

        $this->logInAs($owner);

        // Users permissions changes.
        // Delete the permission of the viewer
        $data['permissions'][] = ['id' => $resourceViewed->permissions[3]->id, 'delete' => true];
        $this->putJson("/share/resource/{$resourceViewed->id}.json", $data);
        $this->assertSuccess();

        // Delete the permission of the viewer
        $data = [];
        $data['permissions'][] = ['id' => $resourceNotViewed->permissions[3]->id, 'delete' => true];
        $this->putJson("/share/resource/{$resourceNotViewed->id}.json", $data);
        $this->assertSuccess();

        $resourceViewed = ResourceFactory::get($resourceViewed->id);
        $resourceNotViewed = ResourceFactory::get($resourceNotViewed->id);
        $this->assertTrue($resourceViewed->isExpired());
        $this->assertFalse($resourceNotViewed->isExpired());

        $emailContent = [
            'Some of your passwords expired',
            'Access for users to your shared passwords have been revoked.',
            'These passwords are now marked as expired.',
            'Please rotate them to ensure continued security.',
        ];
        $this->assertEmailInBatchContains($emailContent, $owner->username);
        $this->assertEmailWithRecipientIsInNotQueue($editor->username);
        $this->assertEmailWithRecipientIsInNotQueue($viewer->username);
        $this->assertEmailQueueCount(1);
    }

    public function testPasswordExpiryShareController_Remove_Permission_Should_Not_Send_Email_If_Resource_Already_Expired(): void
    {
        PasswordExpirySettingFactory::make()->persist();

        // Define actors of this tests
        $allUsers = [$owner, $editor, $viewer, $userLosingPermission] = UserFactory::make(4)->active()->user()->persist();
        /** @var \App\Model\Entity\Resource $expiredResourceViewed */
        $expiredResourceViewed = ResourceFactory::make()
            ->expired()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$editor], Permission::UPDATE)
            ->withPermissionsFor([$viewer], Permission::READ)
            ->withPermissionsFor([$userLosingPermission], Permission::READ)
            ->withSecretsFor($allUsers)
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userLosingPermission))
            ->withResources(ResourceFactory::make($expiredResourceViewed))
            ->persist();

        $this->logInAs($owner);

        // Users permissions changes.
        // Delete the permission of the viewer
        $data['permissions'][] = ['id' => $expiredResourceViewed->permissions[3]->id, 'delete' => true];
        $this->putJson("/share/resource/{$expiredResourceViewed->id}.json", $data);
        $this->assertSuccess();

        // No emails should be sent
        $this->assertEmailQueueIsEmpty();
    }

    public function testPasswordExpiryShareController_Remove_Multiple_Permissions_Should_Send_Only_One_Email(): void
    {
        PasswordExpirySettingFactory::make()->persist();

        $allUsers = [$owner1, $owner2, $viewerActive1, $viewerActive2, $viewerInactive] = UserFactory::make(10)->persist();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$owner1, $owner2])
            ->withPermissionsFor([$viewerActive1, $viewerActive2, $viewerInactive], Permission::READ)
            ->withSecretsFor($allUsers)
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive1))
            ->withResources(ResourceFactory::make($resource))
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive2))
            ->withResources(ResourceFactory::make($resource))
            ->persist();

        $this->logInAs($owner1);

        // Users permissions changes.
        // Delete the permission of the 3 viewers
        $data['permissions'][] = ['id' => $resource->permissions[2]->id, 'delete' => true];
        $data['permissions'][] = ['id' => $resource->permissions[3]->id, 'delete' => true];
        $data['permissions'][] = ['id' => $resource->permissions[4]->id, 'delete' => true];
        $this->putJson("/share/resource/{$resource->id}.json", $data);
        $this->assertSuccess();

        /** @var \App\Model\Table\ResourcesTable $ResourcesTable */
        $ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->assertSame(0, $ResourcesTable->findIndex($viewerActive1->id, ['filter' => ['is-shared-with-me']])->count());
        $this->assertSame(0, $ResourcesTable->findIndex($viewerActive2->id, ['filter' => ['is-shared-with-me']])->count());
        $this->assertSame(0, $ResourcesTable->findIndex($viewerInactive->id, ['filter' => ['is-shared-with-me']])->count());

        $resource = ResourceFactory::get($resource->id);
        $this->assertTrue($resource->isExpired());

        $this->assertEmailWithRecipientIsInNotQueue($viewerActive1->username);
        $this->assertEmailWithRecipientIsInNotQueue($viewerActive2->username);
        $this->assertEmailWithRecipientIsInNotQueue($viewerInactive->username);

        $this->assertEmailQueueCount(2);

        $emailContent = [
            'Some of your passwords expired',
            'Access for users to your shared passwords have been revoked.',
            'These passwords are now marked as expired.',
            'Please rotate them to ensure continued security.',
        ];
        $this->assertEmailInBatchContains($emailContent, $owner1->username);
        $this->assertEmailInBatchContains($emailContent, $owner2->username);
    }

    public function testPasswordExpiryShareController_Remove_Multiple_Group_Permissions(): void
    {
        PasswordExpirySettingFactory::make()->persist();

        $allUsers = [$owner1, $owner2, $userInViewerGroup, $viewerActive1, $viewerActive2, $viewerInactive] = UserFactory::make(10)->persist();

        $group = GroupFactory::make()
            ->withGroupsUsersFor([$viewerActive1, $viewerActive2, $viewerInactive])
            ->persist();
        $viewerGroup = GroupFactory::make()
            ->withGroupsUsersFor([$userInViewerGroup,])
            ->persist();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$owner1, $owner2])
            ->withPermissionsFor([$group], Permission::READ)
            ->withPermissionsFor([$viewerGroup], Permission::READ)
            ->withSecretsFor($allUsers)
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive1))
            ->withResources(ResourceFactory::make($resource))
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive2))
            ->withResources(ResourceFactory::make($resource))
            ->persist();

        $this->logInAs($owner1);

        // Users permissions changes.
        // Delete the permission of the viewer2
        $data['permissions'][] = ['id' => $resource->permissions[2]->id, 'delete' => true];
        $this->putJson("/share/resource/{$resource->id}.json", $data);
        $this->assertSuccess();

        /** @var \App\Model\Table\ResourcesTable $ResourcesTable */
        $ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->assertSame(0, $ResourcesTable->findIndex($viewerActive1->id, ['filter' => ['is-shared-with-me']])->count());
        $this->assertSame(0, $ResourcesTable->findIndex($viewerActive2->id, ['filter' => ['is-shared-with-me']])->count());
        $this->assertSame(0, $ResourcesTable->findIndex($viewerInactive->id, ['filter' => ['is-shared-with-me']])->count());

        $resource = ResourceFactory::get($resource->id);
        $this->assertTrue($resource->isExpired());

        $this->assertEmailWithRecipientIsInNotQueue($viewerActive1->username);
        $this->assertEmailWithRecipientIsInNotQueue($viewerActive2->username);
        $this->assertEmailWithRecipientIsInNotQueue($viewerInactive->username);
        $this->assertEmailWithRecipientIsInNotQueue($userInViewerGroup->username);

        $this->assertEmailQueueCount(2);

        $emailContent = [
            'Some of your passwords expired',
            'Access for users to your shared passwords have been revoked.',
            'These passwords are now marked as expired.',
            'Please rotate them to ensure continued security.',
        ];
        $this->assertEmailInBatchContains($emailContent, $owner1->username);
        $this->assertEmailInBatchContains($emailContent, $owner2->username);
    }
}
