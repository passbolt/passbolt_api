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

namespace Passbolt\Rbacs\Test\TestCase\Service\ActionAccessControl;

use App\Test\Factory\RoleFactory;
use App\Utility\UuidFactory;
use Cake\Http\Exception\ForbiddenException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Service\ActionAccessControl\RbacsRoleActionAccessControlService;
use Passbolt\Rbacs\Test\Factory\RbacFactory;

class RbacsRoleActionAccessControlServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private RbacsRoleActionAccessControlService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new RbacsRoleActionAccessControlService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testRbacsRoleActionAccessControlService_IsAdmin(): void
    {
        $actionId = UuidFactory::uuid();
        $role = RoleFactory::make()->admin()->persist();

        $this->expectNotToPerformAssertions();
        $this->service->controlUserRoleActionAccess($role, $actionId);
    }

    public function testRbacsRoleActionAccessControlService_Is_User(): void
    {
        $actionId = UuidFactory::uuid();
        $role = RoleFactory::make()->user()->persist();

        $this->expectException(ForbiddenException::class);
        $this->service->controlUserRoleActionAccess($role, $actionId);
    }

    public function testRbacsRoleActionAccessControlService_Has_Role_With_Access(): void
    {
        $actionId = UuidFactory::uuid();
        $role = RoleFactory::make()->persist();

        RbacFactory::make()
            ->setField('foreign_id', $actionId)
            ->setField('foreign_model', Rbac::FOREIGN_MODEL_ACTION)
            ->setField('control_function', Rbac::CONTROL_FUNCTION_ALLOW)
            ->setField('role_id', $role->get('id'))
            ->persist();

        $this->expectNotToPerformAssertions();
        $this->service->controlUserRoleActionAccess($role, $actionId);
    }

    public function testRbacsRoleActionAccessControlService_Has_Role_Without_Access(): void
    {
        $actionId = UuidFactory::uuid();
        $role = RoleFactory::make()->persist();

        RbacFactory::make()
            ->setField('foreign_id', $actionId)
            ->setField('foreign_model', Rbac::FOREIGN_MODEL_ACTION)
            ->setField('role_id', $role->get('id'))
            ->deny()
            ->persist();

        $this->expectException(ForbiddenException::class);
        $this->service->controlUserRoleActionAccess($role, $actionId);
    }
}
