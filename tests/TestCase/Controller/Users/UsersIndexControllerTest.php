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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Users;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\Utility\Hash;

/**
 * @covers \App\Controller\Users\UsersIndexController
 */
class UsersIndexControllerTest extends AppIntegrationTestCase
{
    public function testUsersIndexController_Success_AsAdmin(): void
    {
        RoleFactory::make()->guest()->persist();
        $active = UserFactory::make()->user()->active()->persist();
        $inactive = UserFactory::make()->user()->inactive()->persist();
        $disabled = UserFactory::make()->user()->disabled()->persist();
        $deleted = UserFactory::make()->user()->deleted()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();

        $this->logInAs($admin);
        $this->getJson('/users.json');
        $this->assertSuccess();
        $usersIds = Hash::extract($this->_responseJsonBody, '{n}.id');

        // Should contain everyone.
        $this->assertContains($active->id, $usersIds);
        $this->assertContains($inactive->id, $usersIds);
        $this->assertContains($disabled->id, $usersIds);
        $this->assertNotContains($deleted->id, $usersIds);
        $this->assertContains($admin->id, $usersIds);
    }

    public function testUsersIndexController_Success_FilterActiveAsAdmin(): void
    {
        RoleFactory::make()->guest()->persist();
        $active = UserFactory::make()->user()->active()->persist();
        $inactive = UserFactory::make()->user()->inactive()->persist();
        $disabled = UserFactory::make()->user()->disabled()->persist();
        $deleted = UserFactory::make()->user()->deleted()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();

        $this->logInAs($admin);
        $this->getJson('/users.json?filter[is-active]=1');
        $this->assertSuccess();

        // Should contain everyone but active user.
        $usersIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $this->assertNotContains($inactive->id, $usersIds);
        $this->assertContains($active->id, $usersIds);
        $this->assertContains($disabled->id, $usersIds);
        $this->assertNotContains($deleted->id, $usersIds);
        $this->assertContains($admin->id, $usersIds);
    }

    public function testUsersIndexController_Success_FilterInactiveAsUser(): void
    {
        RoleFactory::make()->guest()->persist();
        $active = UserFactory::make()->user()->active()->persist();
        $inactive = UserFactory::make()->user()->inactive()->persist();
        $disabled = UserFactory::make()->user()->disabled()->persist();
        $deleted = UserFactory::make()->user()->deleted()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();

        $this->logInAs($active);
        $this->getJson('/users.json?filter[is-active]=0');
        $this->assertSuccess();

        // Regular users have not the right to view inactive users
        // Should contain everyone but inactive user.
        $usersIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $this->assertNotContains($inactive->id, $usersIds);
        $this->assertContains($active->id, $usersIds);
        $this->assertContains($disabled->id, $usersIds);
        $this->assertNotContains($deleted->id, $usersIds);
        $this->assertContains($admin->id, $usersIds);
    }

    public function testUsersIndexController_Success_FilterInactiveAsAdmin(): void
    {
        RoleFactory::make()->guest()->persist();
        $active = UserFactory::make()->user()->active()->persist();
        $inactive = UserFactory::make()->user()->inactive()->persist();
        $disabled = UserFactory::make()->user()->disabled()->persist();
        $deleted = UserFactory::make()->user()->deleted()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();

        $this->logInAs($admin);
        $this->getJson('/users.json?filter[is-active]=0');
        $this->assertSuccess();

        // Should contain only inactive users
        $usersIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $this->assertContains($inactive->id, $usersIds);
        $this->assertNotContains($active->id, $usersIds);
        $this->assertNotContains($disabled->id, $usersIds);
        $this->assertNotContains($deleted->id, $usersIds);
        $this->assertNotContains($admin->id, $usersIds);
    }

    public function testUsersIndexController_Success_FilterBySearch(): void
    {
        RoleFactory::make()->guest()->persist();
        $active = UserFactory::make()->user()->active()->persist();
        $inactive = UserFactory::make()->user()->inactive()->persist();
        $disabled = UserFactory::make()->user()->disabled()->persist();
        $deleted = UserFactory::make()->user()->deleted()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();

        $this->logInAs($active);
        $this->getJson('/users.json?filter[search]=' . $admin->username);
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->id, $admin->id);

        $this->getJson('/users.json?filter[search]=' . $disabled->username);
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 1);
        $this->assertEquals($this->_responseJsonBody[0]->id, $disabled->id);

        // Deleted user should not be shown
        $this->getJson('/users.json?filter[search]=' . $deleted->username);
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 0);

        // Inactive user should not be shown
        $this->getJson('/users.json?filter[search]=' . $inactive->username);
        $this->assertSuccess();
        $this->assertEquals(count($this->_responseJsonBody), 0);
    }

    public function testUsersIndexController_Error_FilterByInvalidSearch(): void
    {
        $this->logInAsUser();
        // too long
        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
        $this->getJson('/users.json?filter[search]=' . $lorem);
        $this->assertError(400);
        // not utf8
        $emo = '🔥🔥🔥';
        $this->getJson('/users.json?filter[search]=' . $emo);
        $this->assertError(400);
    }

    public function testUsersIndexController_Error_NotAuthenticated(): void
    {
        $this->getJson('/users.json');
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testUsersIndexController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->get('/users');
        $this->assertResponseCode(404);
    }
}
