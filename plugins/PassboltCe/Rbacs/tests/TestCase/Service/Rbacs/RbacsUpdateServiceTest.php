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
 * @since         4.1.0
 */

namespace Passbolt\Rbacs\Test\TestCase\Service\Rbacs;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\NotFoundException;
use Passbolt\Rbacs\Model\Dto\RbacsUpdateDtoCollection;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Model\Entity\UiAction;
use Passbolt\Rbacs\Service\Rbacs\RbacsUpdateService;
use Passbolt\Rbacs\Test\Factory\RbacFactory;
use Passbolt\Rbacs\Test\Factory\UiActionFactory;
use Passbolt\Rbacs\Test\Lib\RbacsTestCase;

class RbacsUpdateServiceTest extends RbacsTestCase
{
    /**
     * @return \Passbolt\Rbacs\Model\Entity\Rbac entity
     */
    public function setupRbac(): Rbac
    {
        // Setup test data
        RoleFactory::make()->admin()->persist();
        RoleFactory::make()->user()->persist();

        $uiAction = UiActionFactory::make()->name(UiAction::NAME_RESOURCES_IMPORT)->persist();

        /** @var \Passbolt\Rbacs\Model\Entity\Rbac $rbac */
        $rbac = RbacFactory::make()->user()->setUiAction($uiAction)->persist();

        $this->assertEquals(1, UiActionFactory::count());
        $this->assertEquals(1, RbacFactory::count());
        $this->assertEquals(2, RoleFactory::count());

        return $rbac;
    }

    public function setupUser(): UserAccessControl
    {
        $user = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl($user->role->name, $user->id, $user->username);

        return $uac;
    }

    public function testRbacsUpdateService_Success(): void
    {
        $rbac = $this->setupRbac();
        $uac = $this->setupUser();

        // SUT
        $rbacsDto = new RbacsUpdateDtoCollection([[
            'id' => $rbac->id,
            'control_function' => Rbac::CONTROL_FUNCTION_DENY,
        ]]);
        (new RbacsUpdateService())->update($uac, $rbacsDto);

        // Tests results
        $deny = RbacFactory::find()->where(['control_function' => Rbac::CONTROL_FUNCTION_DENY])->count();
        $this->assertEquals(1, $deny);
    }

    public function testRbacsUpdateService_Error_ControlFunctionNotInList(): void
    {
        $rbac = $this->setupRbac();
        $uac = $this->setupUser();

        // SUT
        $rbacsDto = new RbacsUpdateDtoCollection([[
            'id' => $rbac->id,
            'control_function' => 'test',
        ]]);

        $this->expectException(CustomValidationException::class);
        (new RbacsUpdateService())->update($uac, $rbacsDto);
    }

    public function testRbacsUpdateService_Error_ControlFunctionNotFound(): void
    {
        $this->setupRbac();
        $uac = $this->setupUser();

        // SUT
        $rbacsDto = new RbacsUpdateDtoCollection([[
            'id' => UuidFactory::uuid(),
            'control_function' => Rbac::CONTROL_FUNCTION_DENY,
        ]]);

        $this->expectException(NotFoundException::class);
        (new RbacsUpdateService())->update($uac, $rbacsDto);
    }
}
