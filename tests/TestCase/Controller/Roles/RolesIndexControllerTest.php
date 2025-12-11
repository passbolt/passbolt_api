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

namespace App\Test\TestCase\Controller\Roles;

use App\Model\Entity\Role;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\Utility\Hash;

/**
 * @covers \App\Controller\Roles\RolesIndexController
 */
class RolesIndexControllerTest extends AppIntegrationTestCase
{
    public function testRolesIndexController_Success(): void
    {
        RoleFactory::make()->guest()->persist();
        RoleFactory::make()->user()->persist();
        RoleFactory::make()->admin()->persist();
        RoleFactory::make()->root()->persist();
        RoleFactory::make(['name' => 'board members'])->deleted()->persist();

        $this->logInAsUser();
        $this->getJson('/roles.json');
        $this->assertSuccess();
        $this->assertCount(4, $this->_responseJsonBody);
        $this->assertRoleAttributes($this->_responseJsonBody[0]);
    }

    public function testRolesIndexController_Success_FilterDeleted(): void
    {
        RoleFactory::make()->guest()->persist();
        RoleFactory::make()->user()->persist();
        RoleFactory::make()->admin()->persist();
        RoleFactory::make(['name' => 'board members'])->deleted()->persist();
        RoleFactory::make(['name' => 'marketing'])->deleted()->persist();

        $this->logInAsAdmin();
        $this->getJson('/roles.json?filter[is-deleted]=1');
        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        $this->assertRoleAttributes($response[0]);
        $this->assertRoleAttributes($response[1]);
        $expectedNames = ['board members', 'marketing'];
        $names = Hash::extract($response, '{n}.name');
        // Sort array to have consistent assertions
        sort($expectedNames);
        sort($names);
        $this->assertSame($expectedNames, $names);
    }

    public function testRolesIndexController_Success_ContainUserCount(): void
    {
        RoleFactory::make()->guest()->persist();
        $roleUser = RoleFactory::make()->user()->persist();
        $roleAdmin = RoleFactory::make()->admin()->persist();
        $roleCustom = RoleFactory::make(['name' => 'marketing'])->persist();
        RoleFactory::make(['name' => 'sales'])->deleted()->persist();
        // users
        UserFactory::make(4)->with('Roles', $roleUser)->active()->persist();
        UserFactory::make()->with('Roles', $roleUser)->deleted()->persist();
        UserFactory::make(2)->with('Roles', $roleAdmin)->active()->persist();
        UserFactory::make(3)->with('Roles', $roleCustom)->active()->persist();

        $this->logInAsAdmin();
        $this->getJson('/roles.json?contain[user_count]=1');
        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(4, $response);
        $responseUserRole = Hash::extract($response, '{n}[name=' . Role::USER . ']')[0];
        $this->assertRoleAttributes($responseUserRole);
        $this->assertSame(4, $responseUserRole['user_count']);
        $responseAdminRole = Hash::extract($response, '{n}[name=' . Role::ADMIN . ']')[0];
        $this->assertRoleAttributes($responseAdminRole);
        $this->assertSame(3, $responseAdminRole['user_count']);
        $responseCustomRole = Hash::extract($response, '{n}[name=marketing]')[0];
        $this->assertRoleAttributes($responseCustomRole);
        $this->assertSame(3, $responseCustomRole['user_count']);
    }

    public function testRolesIndexController_Success_UserPerformFilterAndContain(): void
    {
        RoleFactory::make()->guest()->persist();
        $roleUser = RoleFactory::make()->user()->persist();
        $roleAdmin = RoleFactory::make()->admin()->persist();
        $roleCustom = RoleFactory::make(['name' => 'marketing'])->persist();
        RoleFactory::make(['name' => 'sales'])->deleted()->persist();
        // users
        UserFactory::make(4)->with('Roles', $roleUser)->active()->persist();
        UserFactory::make()->with('Roles', $roleUser)->deleted()->persist();
        UserFactory::make(2)->with('Roles', $roleAdmin)->active()->persist();
        UserFactory::make(3)->with('Roles', $roleCustom)->active()->persist();

        $this->logInAsUser();
        $this->getJson('/roles.json?filter[is-deleted]=1&contain[user_count]=1');
        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(4, $response);
        $responseUserRole = Hash::extract($response, '{n}[name=' . Role::USER . ']')[0];
        $this->assertRoleAttributes($responseUserRole);
        $this->assertArrayNotHasKey('user_count', $responseUserRole);
        $responseAdminRole = Hash::extract($response, '{n}[name=' . Role::ADMIN . ']')[0];
        $this->assertRoleAttributes($responseAdminRole);
        $this->assertArrayNotHasKey('user_count', $responseUserRole);
        $responseCustomRole = Hash::extract($response, '{n}[name=marketing]')[0];
        $this->assertRoleAttributes($responseCustomRole);
        $this->assertArrayNotHasKey('user_count', $responseUserRole);
    }

    public function testRolesIndexController_Error_NotAuthenticated(): void
    {
        $this->getJson('/roles.json');
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testRolesIndexController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->get('/roles');
        $this->assertResponseCode(404);
    }
}
