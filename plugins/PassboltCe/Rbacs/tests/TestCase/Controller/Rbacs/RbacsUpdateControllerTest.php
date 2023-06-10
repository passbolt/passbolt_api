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

namespace Passbolt\Rbacs\Test\TestCase\Controller\Rbacs;

use App\Test\Factory\RoleFactory;
use App\Utility\UuidFactory;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Service\Rbacs\RbacsInsertDefaultsService;
use Passbolt\Rbacs\Service\UiActions\UiActionsInsertDefaultsService;
use Passbolt\Rbacs\Test\Factory\RbacFactory;
use Passbolt\Rbacs\Test\Lib\RbacsIntegrationTestCase;

/**
 * Passbolt\Rbacs\Controller\Rbacs\RbacsUpdateController Test Case
 *
 * @uses \Passbolt\Rbacs\Controller\Rbacs\RbacsUpdateController
 */
class RbacsUpdateControllerTest extends RbacsIntegrationTestCase
{
    /**
     * @return \Passbolt\Rbacs\Model\Entity\Rbac entity
     * @throws \Exception
     */
    private function setupDefaultRbacs(): Rbac
    {
        RoleFactory::make()->guest()->persist();
        RoleFactory::make()->user()->persist();
        RoleFactory::make()->admin()->persist();
        (new UiActionsInsertDefaultsService())->insertDefaultsIfNotExist();
        $rbacs = (array)(new RbacsInsertDefaultsService())->allowAllUiActionsForUsers();

        /** @var \Passbolt\Rbacs\Model\Entity\Rbac $rbac */
        $rbac = $rbacs[0];

        return $rbac;
    }

    public function testRbacsUpdateController_Success(): void
    {
        $rbac = $this->setupDefaultRbacs();
        $this->logInAsAdmin();
        $this->putJson('/rbacs.json', [[
            'id' => $rbac->id,
            'control_function' => Rbac::CONTROL_FUNCTION_DENY,
        ]]);
        $this->assertSuccess();

        $c = RbacFactory::find()->where(['control_function' => Rbac::CONTROL_FUNCTION_DENY])->count();
        $this->assertEquals(1, $c);
    }

    public function testRbacsUpdateController_Error_NotExist(): void
    {
        $rbac = $this->setupDefaultRbacs();
        $this->logInAsAdmin();
        $this->putJson('/rbacs.json', [[
            'id' => $rbac->id,
            'control_function' => Rbac::CONTROL_FUNCTION_DENY,
        ],[
            'id' => UuidFactory::uuid(),
            'control_function' => Rbac::CONTROL_FUNCTION_DENY,
        ]]);
        $this->assertResponseCode(404);

        $c = RbacFactory::find()->where(['control_function' => Rbac::CONTROL_FUNCTION_DENY])->count();
        $this->assertEquals(0, $c);
    }

    public function testRbacsUpdateController_Error_NotValid(): void
    {
        $rbac = $this->setupDefaultRbacs();
        $this->logInAsAdmin();
        $this->putJson('/rbacs.json', [[
            'id' => $rbac->id,
            'control_function' => 'test',
        ]]);
        $this->assertResponseCode(400);
    }

    public function testRbacsUpdateController_Error_NotLoggedIn(): void
    {
        RoleFactory::make()->guest()->persist();
        $this->postJson('/rbacs.json', []);
        $this->assertResponseCode(401);
    }

    public function testRbacsUpdateController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->postJson('/rbacs.json', []);
        $this->assertResponseCode(403);
    }

    public function testRbacsUpdateController_Error_NotJson(): void
    {
        $rbac = $this->setupDefaultRbacs();
        $this->logInAsAdmin();
        $this->put('/rbacs', [[
            'id' => $rbac->id,
            'control_function' => Rbac::CONTROL_FUNCTION_DENY,
        ]]);
        $this->assertResponseCode(404);
    }
}
