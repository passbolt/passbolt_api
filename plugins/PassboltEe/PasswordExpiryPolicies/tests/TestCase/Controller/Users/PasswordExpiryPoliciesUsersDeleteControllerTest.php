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

namespace Passbolt\PasswordExpiryPolicies\Test\TestCase\Controller\Users;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiryPolicies\Test\Factory\PasswordExpiryPoliciesSettingFactory;

class PasswordExpiryPoliciesUsersDeleteControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
    }

    public function testPasswordExpiryPoliciesUsersDeleteController_Success_Expiry_Not_Automatically_Updated(): void
    {
        PasswordExpiryPoliciesSettingFactory::make()->automaticExpiryDisabled()->persist();

        /** @var \App\Model\Entity\User $userToDelete */
        $userToDelete = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resourceSharedViewed */
        $resourceSharedViewed = ResourceFactory::make()
            ->withPermissionsFor([$userToDelete])
            ->persist();
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userToDelete))
            ->withResources(ResourceFactory::make($resourceSharedViewed))
            ->persist();

        $this->logInAsAdmin();
        $this->deleteJson('/users/' . $userToDelete->id . '.json');
        $this->assertSuccess();

        $userDisabled = UserFactory::get($userToDelete->id);
        $this->assertTrue($userDisabled->isDeleted());
        $resourceSharedViewed = ResourceFactory::get($resourceSharedViewed->id);
        $this->assertFalse($resourceSharedViewed->isExpired());
    }
}
