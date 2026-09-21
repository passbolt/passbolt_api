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

namespace Passbolt\PasswordExpiry\Test\TestCase\Controller\Users;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class PasswordExpiryUsersDeleteControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
        PasswordExpirySettingFactory::make()->persist();
        $this->enableFeaturePlugin(PasswordExpiryPlugin::class);
    }

    public function testPasswordExpiryUsersDeleteController_Success_Expire_Resources(): void
    {
        [$owner, $userToDelete] = UserFactory::make(2)->user()->persist();

        [$resourceSharedViewed, $resourceSharedNotViewed] = ResourceFactory::make(2)
            ->withPermissionsFor([$userToDelete, $owner])
            ->withSecretsFor([$userToDelete, $owner])
            ->persist();
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userToDelete))
            ->withResources(ResourceFactory::make($resourceSharedViewed))
            ->persist();

        $admin = $this->logInAsAdmin();
        $this->deleteJson('/users/' . $userToDelete->id . '.json');
        $this->assertSuccess();

        $userDeleted = UserFactory::get($userToDelete->id);
        $this->assertTrue($userDeleted->isDeleted());
        $resourceSharedViewed = ResourceFactory::get($resourceSharedViewed->id);
        $resourceSharedNotViewed = ResourceFactory::get($resourceSharedNotViewed->id);
        $this->assertTrue($resourceSharedViewed->isExpired());
        $this->assertFalse($resourceSharedNotViewed->isExpired());
        // Two notifications: the owner about the expired resource, and the
        // acting admin about the user deletion (UserDeleteAdminEmailRedactor).
        $this->assertEmailQueueCount(2);
        $this->assertEmailInBatchContains(
            'Access for users to your shared passwords have been revoked.',
            $owner->username,
        );
        $this->assertEmailInBatchContains(
            "You deleted user {$userToDelete->profile->full_name}",
            $admin->username,
        );
    }
}
