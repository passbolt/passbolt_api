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

namespace Passbolt\PasswordExpiry\Test\TestCase\Service\Resources;

use App\Model\Entity\Role;
use App\Service\Resources\ResourcesShareService;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryValidationService;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsService;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class PasswordExpiryResourcesShareServiceTest extends AppTestCase
{
    public ResourcesShareService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ResourcesShareService(
            new PasswordExpiryExpireResourcesService(
                new PasswordExpiryValidationService(
                    new PasswordExpiryGetSettingsService()
                )
            )
        );
        $this->loadPlugins([PasswordExpiryPlugin::class => []]);
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testPasswordExpiryResourcesShareService_Remove_Permission_Set_Expired_If_Secret_Access()
    {
        // Arrange
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid());
        // Enable the pwd expiry in settings
        PasswordExpirySettingFactory::make()->persist();

        // Create a resource shared with 2 users
        [$owner, $otherUserWithPermission] = UserFactory::make(2)->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withSecretsFor([$owner, $otherUserWithPermission,])->persist();
        $this->assertFalse($resource->isExpired());
        $permissionFactory = PermissionFactory::make()->acoResource($resource);
        $permissionFactory->aroUser($owner)->typeOwner()->persist();
        $permissionToDeleteId[] = $permissionFactory->aroUser($otherUserWithPermission)->typeRead()->persist()->get('id');

        // $otherUserWithAccess will be removed permission
        // It had access to resource 1 but never to resource 2, so only resource 1 will be expired
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($otherUserWithPermission))
            ->withResources(ResourceFactory::make($resource))
            ->persist();

        // Create a resource shared with the main owner, which should not be expired
        /** @var \App\Model\Entity\Resource $otherResource */
        $otherResource = ResourceFactory::make()->withSecretsFor([$owner])->persist();
        PermissionFactory::make()->acoResource($otherResource)->typeRead()->persist();

        // Act
        // Delete the permission on resource of a user with permission
        foreach ($permissionToDeleteId as $id) {
            $changes[] = ['id' => $id, 'delete' => true];
        }

        $this->service->share($uac, $resource->id, $changes);

        // Assert
        $resource = ResourceFactory::get($resource->id);
        $this->assertTrue($resource->isExpired());

        $otherResource = ResourceFactory::get($otherResource->id);
        $this->assertFalse($otherResource->isExpired());
    }

    public function testPasswordExpiryResourcesShareService_Remove_Permission_Do_Not_Set_Expired_If_No_Secret_Access()
    {
        // Arrange
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid());
        // Enable the pwd expiry in settings
        PasswordExpirySettingFactory::make()->persist();

        // Create a resource shared with 4 users
        [$owner, $otherUserWithPermission] = UserFactory::make(2)->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withSecretsFor([$owner, $otherUserWithPermission,])->persist();
        $this->assertFalse($resource->isExpired());
        $permissionFactory = PermissionFactory::make()->acoResource($resource);
        $permissionFactory->aroUser($owner)->typeOwner()->persist();
        $permissionToDeleteId[] = $permissionFactory->aroUser($otherUserWithPermission)->typeRead()->persist()->get('id');

        // Create a resource shared with the main owner, which should not be expired
        /** @var \App\Model\Entity\Resource $otherResource */
        $otherResource = ResourceFactory::make()->withSecretsFor([$owner])->persist();
        PermissionFactory::make()->acoResource($otherResource)->typeRead()->persist();

        // Act
        // Delete the permission on resource of a user with permission
        foreach ($permissionToDeleteId as $id) {
            $changes[] = ['id' => $id, 'delete' => true];
        }
        $this->service->share($uac, $resource->id, $changes);

        // Assert
        $resource = ResourceFactory::get($resource->id);
        $this->assertFalse($resource->isExpired());

        $otherResource = ResourceFactory::get($otherResource->id);
        $this->assertFalse($otherResource->isExpired());
    }
}
