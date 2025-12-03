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
 * @since         5.8.0
 */

namespace App\Test\TestCase\Controller\Roles;

use App\Test\Factory\RoleFactory;
use App\Utility\UuidFactory;
use Passbolt\Rbacs\Test\Lib\RbacsIntegrationTestCase;

/**
 * @covers \App\Controller\Roles\RolesUpdateController
 */
class RolesUpdateControllerTest extends RbacsIntegrationTestCase
{
    public function testRolesUpdateController_Success(): void
    {
        $role = RoleFactory::make(['name' => 'sales'])->persist();

        $admin = $this->logInAsAdmin();
        $this->putJson("/roles/$role->id.json", ['name' => 'growth']);

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes([
            'id',
            'name',
            'description',
            'created_by',
            'modified_by',
            'deleted_by',
            'created',
            'modified',
        ], $response);
        // Role updated in the database
        $expectedRole = RoleFactory::get($role->id);
        $this->assertSame('growth', $expectedRole->name);
        $this->assertSame($admin->id, $expectedRole->modified_by);
    }

    public function testRolesUpdateController_Success_Post(): void
    {
        $role = RoleFactory::make(['name' => 'sales'])->persist();

        $this->logInAsAdmin();
        $this->postJson("/roles/$role->id.json", ['name' => 'growth']);

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes([
            'id',
            'name',
            'description',
            'created_by',
            'modified_by',
            'deleted_by',
            'created',
            'modified',
        ], $response);
    }

    public function testRolesUpdateController_Error_NotLoggedIn(): void
    {
        RoleFactory::make()->guest()->persist();
        $role = RoleFactory::make(['name' => 'sales'])->persist();
        $this->putJson("/roles/$role->id.json", ['name' => 'growth']);
        $this->assertResponseCode(401);
    }

    public function testRolesUpdateController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $role = RoleFactory::make(['name' => 'sales'])->persist();
        $this->putJson("/roles/$role->id.json", ['name' => 'growth']);
        $this->assertResponseCode(403);
    }

    public function testRolesUpdateController_Error_RoleDoesNotExist(): void
    {
        $this->logInAsAdmin();
        $roleId = UuidFactory::uuid();
        $this->putJson("/roles/$roleId.json", ['name' => 'growth']);
        $this->assertResponseCode(404);
    }

    public function testRolesUpdateController_Error_RoleDeleted(): void
    {
        $this->logInAsAdmin();
        $role = RoleFactory::make(['name' => 'sales'])->deleted()->persist();
        $this->putJson("/roles/$role->id.json", ['name' => 'growth']);
        $this->assertResponseCode(404);
    }

    public function testRolesUpdateController_Error_NotJson(): void
    {
        $this->logInAsAdmin();
        $role = RoleFactory::make(['name' => 'sales'])->persist();
        $this->put("/roles/$role->id", ['name' => 'growth']);
        $this->assertResponseCode(404);
    }

    public function testRolesUpdateController_Error_InvalidData(): void
    {
        $this->logInAsAdmin();
        $this->postJson('/roles.json', ['name']);
        $this->assertResponseCode(400);
    }
}
