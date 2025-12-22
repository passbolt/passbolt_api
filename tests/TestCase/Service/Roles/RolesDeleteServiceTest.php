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

namespace App\Test\TestCase\Service\Roles;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use App\Service\Roles\RolesDeleteService;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Utility\Hash;

/**
 * @covers \App\Service\Roles\RolesDeleteService
 */
class RolesDeleteServiceTest extends AppTestCase
{
    private ?RolesDeleteService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new RolesDeleteService();
        // populate default roles
        RoleFactory::make()->guest()->persist();
        RoleFactory::make()->admin()->persist();
        RoleFactory::make()->user()->persist();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testRolesDeleteService_Success(): void
    {
        $role = RoleFactory::make()->persist();
        $admin = UserFactory::make()->admin()->persist();
        $uac = $this->makeUac($admin);

        $this->service->delete($uac, $role->id);

        /** @var \App\Model\Entity\Role $roleUpdated */
        $roleUpdated = RoleFactory::get($role->id);
        $this->assertNotNull($roleUpdated->deleted);
        $this->assertSame($uac->getId(), $roleUpdated->deleted_by);
    }

    /**
     * @return void
     */
    public function testRolesDeleteService_Error_UsersExistsForTheRole(): void
    {
        $uac = $this->mockAdminAccessControl();
        $role = RoleFactory::make()->persist();
        UserFactory::make(2)
            ->with('Roles', $role)
            ->active()
            ->persist();

        try {
            $this->service->delete($uac, $role->id);
        } catch (CustomValidationException $e) {
            $validationErrors = $e->getErrors();
            $this->assertTrue(Hash::check($validationErrors, 'id.hasNoActiveUserAssociatedRule'));
        }
    }

    /**
     * @return void
     */
    public function testRolesDeleteService_Error_ReservedRole(): void
    {
        $uac = $this->mockAdminAccessControl();
        $role = RoleFactory::find()->where(['name' => Role::ADMIN])->firstOrFail();

        try {
            $this->service->delete($uac, $role->id);
        } catch (CustomValidationException $e) {
            $validationErrors = $e->getErrors();
            $this->assertTrue(Hash::check($validationErrors, 'name.isReservedRole'));
        }
    }
}
