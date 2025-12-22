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
use Passbolt\Rbacs\Model\Entity\UiAction;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;
use Passbolt\Rbacs\Service\Rbacs\RbacsInsertDefaultsService;
use Passbolt\Rbacs\Service\UiActions\UiActionsInsertDefaultsService;
use Passbolt\Rbacs\Test\Factory\RbacFactory;
use Passbolt\Rbacs\Test\Factory\UiActionFactory;
use Passbolt\Rbacs\Test\Lib\RbacsIntegrationTestCase;

/**
 * @covers \Passbolt\Rbacs\Controller\Rbacs\RbacsUpdateController
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
        // UiAction - model
        (new UiActionsInsertDefaultsService())->insertDefaultsIfNotExist();
        $rbacs = (new RbacsInsertDefaultsService())->allowAllUiActionsForUsers();
        // Action - model
        $actions = (new RbacsControlledActionsInsertService())->insertRbacsControlledActions();
        foreach ($actions as $action) {
            RbacFactory::make()
                ->setField('foreign_id', $action->get('id'))
                ->setField('foreign_model', Rbac::FOREIGN_MODEL_ACTION)
                ->persist();
        }

        /** @var \Passbolt\Rbacs\Model\Entity\Rbac $rbac */
        $rbac = $rbacs[0];

        return $rbac;
    }

    /**
     * @param string $uiAction UI Action name.
     * @return \Passbolt\Rbacs\Model\Entity\Rbac
     */
    private function findRbacFromUiActionName(string $uiAction): Rbac
    {
        $uiActionSubQuery = UiActionFactory::find()->select(['id'])->where(['name' => $uiAction]);
        /** @var \Passbolt\Rbacs\Model\Entity\Rbac $rbac */
        $rbac = RbacFactory::find()->where([
            'foreign_model' => Rbac::FOREIGN_MODEL_UI_ACTION,
            'foreign_id' => $uiActionSubQuery,
        ])->firstOrFail();

        return $rbac;
    }

    public function testRbacsUpdateController_Success(): void
    {
        $this->setupDefaultRbacs();
        $uiActionRbac = RbacFactory::find()->where(['foreign_model' => Rbac::FOREIGN_MODEL_UI_ACTION])->firstOrFail();
        $actionRbac = RbacFactory::find()->where(['foreign_model' => Rbac::FOREIGN_MODEL_ACTION])->firstOrFail();

        $this->logInAsAdmin();
        $this->putJson('/rbacs.json', [
            // UiAction
            [
                'id' => $uiActionRbac->id,
                'control_function' => Rbac::CONTROL_FUNCTION_DENY,
            ],
            // Action
            [
                'id' => $actionRbac->id,
                'control_function' => Rbac::CONTROL_FUNCTION_DENY,
            ],
        ]);

        $this->assertSuccess();
        // Assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        foreach ($response as $item) {
            if ($item['foreign_model'] === Rbac::FOREIGN_MODEL_UI_ACTION) {
                $this->assertNotEmpty($item['ui_action']);
                $this->assertArrayHasAttributes(['id', 'name'], $item['ui_action']);
                $this->assertNull($item['action']);
            } else {
                $this->assertNotEmpty($item['action']);
                $this->assertArrayHasAttributes(['id', 'name'], $item['action']);
                $this->assertNull($item['ui_action']);
            }
        }
        // Assert db entries
        $expectedUiActionRbac = RbacFactory::get($uiActionRbac->id);
        $this->assertSame(Rbac::CONTROL_FUNCTION_DENY, $expectedUiActionRbac->control_function);
        $expectedActionRbac = RbacFactory::get($actionRbac->id);
        $this->assertSame(Rbac::CONTROL_FUNCTION_DENY, $expectedActionRbac->control_function);
    }

    public function testRbacsUpdateController_Success_AllowIfGroupManager(): void
    {
        $this->setupDefaultRbacs();
        $this->logInAsAdmin();
        $rbac = $this->findRbacFromUiActionName(UiAction::NAME_USERS_VIEW_WORKSPACE);

        $this->putJson('/rbacs.json', [
            [
                'id' => $rbac->id,
                'control_function' => Rbac::CONTROL_FUNCTION_ALLOW_IF_GROUP_MANAGER_IN_ONE_GROUP,
            ],
        ]);

        $this->assertSuccess();
        /** @var \Passbolt\Rbacs\Model\Entity\Rbac[] $result */
        $result = RbacFactory::find()->where(['id' => $rbac->id])->toArray();
        $this->assertCount(1, $result);
        $this->assertSame(
            Rbac::CONTROL_FUNCTION_ALLOW_IF_GROUP_MANAGER_IN_ONE_GROUP,
            $result[0]->control_function
        );
        $this->assertSame(
            Rbac::FOREIGN_MODEL_UI_ACTION,
            $result[0]->foreign_model
        );
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

        $c = RbacFactory::find()->where(['control_function' => Rbac::CONTROL_FUNCTION_DENY])->all()->count();
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

    public function testRbacsUpdateController_Error_NotAllowedControlFunction(): void
    {
        $this->setupDefaultRbacs();
        $uiActionSubQuery = UiActionFactory::find()->select(['id'])->where(['name' => UiAction::NAME_RESOURCES_IMPORT]);
        /** @var \Passbolt\Rbacs\Model\Entity\Rbac $usersViewWorkspaceRbac */
        $usersViewWorkspaceRbac = RbacFactory::find()->where([
            'foreign_model' => Rbac::FOREIGN_MODEL_UI_ACTION,
            'foreign_id' => $uiActionSubQuery,
        ])->firstOrFail();
        $this->logInAsAdmin();

        $this->putJson('/rbacs.json', [
            [
                'id' => $usersViewWorkspaceRbac->id,
                'control_function' => Rbac::CONTROL_FUNCTION_ALLOW_IF_GROUP_MANAGER_IN_ONE_GROUP,
            ],
        ]);

        $this->assertResponseCode(400);
        $responseArray = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('isControlFunctionAllowed', $responseArray['control_function']);
    }
}
