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
 * @since         4.3.0
 */
namespace App\Test\TestCase\Controller\Users;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\Purifier;
use Cake\I18n\DateTime;

/**
 * @covers \App\Controller\Users\UsersEditController
 */
class UsersEditDisableControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
        // Mock user agent and IP
        $this->mockUserAgent('PHPUnit');
        $this->mockUserIp();
    }

    public function testUsersEditDisableController_Success_AsUserCannotEditDisabled(): void
    {
        $user = $this->logInAsUser();
        $data = [
            'id' => $user->id,
            'disabled' => DateTime::yesterday(),
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertSuccess();
        $this->assertNull($this->_responseJsonBody->disabled);
    }

    public function testUsersEditDisableController_Success_AsAdminCannotEditDisabled(): void
    {
        $admin = $this->logInAsAdmin();
        $data = [
            'id' => $admin->id,
            'disabled' => DateTime::yesterday(),
        ];
        $this->postJson('/users/' . $admin->id . '.json', $data);
        $this->assertSuccess();
        $this->assertNull($this->_responseJsonBody->disabled);
    }

    public function testUsersEditDisableController_Error_Disabled_Not_Parsable(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAsAdmin();
        $data = [
            'id' => $user->id,
            'disabled' => 'Foo',
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertBadRequestError('Could not validate user data.');
    }

    public function testUsersEditDisableController_Success_Admin_Disable_User(): void
    {
        [$admin1, $admin2] = UserFactory::make(2)->admin()->persist();
        $user = UserFactory::make()
            ->withProfileName('Helene', 'D\'Amore')
            ->user()
            ->persist();
        $this->logInAs($admin1);
        $userFullName = Purifier::clean($user->profile->full_name);

        $data = [
            'id' => $user->id,
            'disabled' => DateTime::now(),
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);

        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody->disabled);
        $user = UserFactory::get($user->id);
        $this->assertTrue($user->isDisabled());
        $this->assertEmailQueueCount(2);
        $this->assertEmailInBatchContains("The user {$userFullName} has been suspended.", $admin1->username);
        $this->assertEmailInBatchContains("The user {$userFullName} has been suspended.", $admin2->username);
    }

    public function testUsersEditDisableController_Success_Admin_Disable_Admin(): void
    {
        [$admin1, $admin2, $user] = UserFactory::make(3)
            ->withProfileName('Helene', 'D\'Amore')
            ->admin()
            ->persist();
        $this->logInAs($admin1);
        $userFullName = Purifier::clean($user->profile->full_name);

        $data = [
            'id' => $user->id,
            'disabled' => DateTime::now(),
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);

        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody->disabled);
        $user = UserFactory::get($user->id);
        $this->assertTrue($user->isDisabled());
        $this->assertEmailQueueCount(3);
        $this->assertEmailInBatchContains("The user {$userFullName} has been suspended.", $admin1->username);
        $this->assertEmailInBatchContains("The user {$userFullName} has been suspended.", $admin2->username);
        $this->assertEmailInBatchContains('Your account has been suspended.', $user->username);
        $this->assertEmailInBatchContains("mailto:{$admin1->username}", $user->username);
    }

    public function testUsersEditDisableController_Success_AdminDisableAlreadyDisabled(): void
    {
        $user = UserFactory::make()->user()->disabled()->persist();
        $this->logInAsAdmin();
        $data = [
            'id' => $user->id,
            'disabled' => DateTime::now(),
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody->disabled);
        $user = UserFactory::get($user->id);
        $this->assertTrue($user->isDisabled());
        $this->assertEmailQueueCount(0);
    }

    public function testUsersEditDisableController_Success_EnableDisabledUser(): void
    {
        $user = UserFactory::make()->user()->disabled()->persist();
        $this->logInAsAdmin();

        $data = [
            'id' => $user->id,
            'disabled' => null,
            'username' => $user->username,
            'role_id' => $user->role_id,
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);

        $this->assertSuccess();
        $this->assertNull($this->_responseJsonBody->disabled);
        $user = UserFactory::get($user->id);
        $this->assertFalse($user->isDisabled());
        $this->assertEmailQueueCount(0);
    }
}
