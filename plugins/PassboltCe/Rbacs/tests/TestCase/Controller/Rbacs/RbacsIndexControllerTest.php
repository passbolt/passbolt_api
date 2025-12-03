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

use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Rbacs\Test\Factory\RbacFactory;
use Passbolt\Rbacs\Test\Factory\UiActionFactory;
use Passbolt\Rbacs\Test\Lib\RbacsIntegrationTestCase;

/**
 * Passbolt\Rbacs\Controller\Rbacs\RbacsCreateController Test Case
 *
 * @uses \Passbolt\Rbacs\Controller\Rbacs\RbacsIndexController
 */
class RbacsIndexControllerTest extends RbacsIntegrationTestCase
{
    /**
     * Check complete list of Rbacs is available to admin
     */
    public function testRbacsIndexController_Success(): void
    {
        $uiAction = UiActionFactory::make()->persist();
        $action = ActionFactory::make()->persist();
        RbacFactory::make()->setUiAction($uiAction)->persist();
        RbacFactory::make()->setAction($action)->persist();

        $this->logInAsAdmin();
        $this->getJson('/rbacs.json');
        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        foreach ($response as $item) {
            if (is_null($item['action'])) {
                $this->assertSame(['id' => $uiAction->get('id'), 'name' => $uiAction->get('name')], $item['ui_action']);
            } else {
                $this->assertNull($item['ui_action']);
                $this->assertSame(['id' => $action->get('id'), 'name' => $action->get('name')], $item['action']);
            }
        }
    }

    /**
     * Check complete list of Rbacs is not available to guests
     */
    public function testRbacsIndexController_Error_NotAuthenticated(): void
    {
        $this->getJson('/rbacs.json');
        $this->assertAuthenticationError();
    }

    /**
     * Check complete list of Rbacs is only available to admin
     */
    public function testRbacsIndexController_Error_ForbiddenForUser(): void
    {
        $this->logInAsUser();
        $this->getJson('/rbacs.json');
        $this->assertError(403);
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testRbacsIndexController_Error_NotJson(): void
    {
        $this->logInAsAdmin();
        $this->get('/rbacs');
        $this->assertResponseCode(404);
    }
}
