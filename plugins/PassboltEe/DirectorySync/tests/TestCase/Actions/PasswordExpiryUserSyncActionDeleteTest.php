<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.7.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Factory\DirectoryEntryFactory;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncDeprecatedIntegrationTestCase;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryValidationService;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsService;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class PasswordExpiryUserSyncActionDeleteTest extends DirectorySyncDeprecatedIntegrationTestCase
{
    public $fixtures = [];

    public function setUp(): void
    {
        parent::setUp();
        UserFactory::make()->admin()->persist();
        $this->action = new UserSyncAction(
            new PasswordExpiryExpireResourcesService(
                new PasswordExpiryValidationService(
                    new PasswordExpiryGetSettingsService()
                )
            )
        );
    }

    public function testPasswordExpiryUserSyncActionDelete_User_With_Access_To_Resources()
    {
        PasswordExpirySettingFactory::make()->persist();
        $owner = UserFactory::make()->persist();
        $directoryEntry = DirectoryEntryFactory::make()
            ->withUser()
            ->persist();
        $userToDelete = $directoryEntry->get('user');

        [$resourceSharedViewed, $resourceSharedNotViewed] = ResourceFactory::make(2)
            ->withPermissionsFor([$userToDelete, $owner])
            ->withSecretsFor([$userToDelete, $owner])
            ->persist();
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userToDelete))
            ->withResources(ResourceFactory::make($resourceSharedViewed))
            ->persist();

        $this->action->execute();

        $userToDelete = UserFactory::get($userToDelete->id);
        $this->assertTrue($userToDelete->isDeleted());
        $resourceSharedViewed = ResourceFactory::get($resourceSharedViewed->id);
        $resourceSharedNotViewed = ResourceFactory::get($resourceSharedNotViewed->id);
        $this->assertTrue($resourceSharedViewed->isExpired());
        $this->assertFalse($resourceSharedNotViewed->isExpired());
    }
}
