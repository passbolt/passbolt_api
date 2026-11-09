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
 * @since         5.18.0
 */

namespace App\Test\TestCase\Middleware;

use App\Middleware\PreventHttpMethodOverrideMiddleware;
use App\Test\Factory\GroupFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;

/**
 * @covers \App\Middleware\PreventHttpMethodOverrideMiddleware
 */
class PreventHttpMethodOverrideMiddlewareIntegrationTest extends AppIntegrationTestCase
{
    public function testPreventHttpMethodOverrideMiddlewareIntegration_Success_NoOverride(): void
    {
        $this->logInAsUser();
        $this->getJson('/settings.json');
        $this->assertSuccess();
    }

    public function testPreventHttpMethodOverrideMiddlewareIntegration_Error_BodyOverride(): void
    {
        $this->logInAsUser();
        $this->post('/settings.json', ['_method' => 'GET']);
        $this->assertResponseCode(405);
    }

    public function testPreventHttpMethodOverrideMiddlewareIntegration_Error_HeaderOverride(): void
    {
        $this->logInAsUser();
        $this->configRequest(['headers' => ['X-Http-Method-Override' => 'GET']]);

        $this->post('/settings.json');

        $this->assertResponseCode(405);
    }

    public function testPreventHttpMethodOverrideMiddlewareIntegration_Error_GroupIsNotDeleted(): void
    {
        $this->logInAsAdmin();
        $group = GroupFactory::make()->persist();

        $this->post('/groups/' . $group->id . '.json', ['_method' => 'DELETE']);

        $this->assertResponseCode(405);
        $this->assertFalse(GroupFactory::get($group->id)->deleted);
    }

    public function testPreventHttpMethodOverrideMiddlewareIntegration_Success_OverrideAllowedWhenPreventionDisabledInConfig(): void
    {
        Configure::write(PreventHttpMethodOverrideMiddleware::PASSBOLT_SECURITY_PREVENT_HTTP_METHOD_OVERRIDE, false);
        $this->logInAsUser();

        $this->postJson('/settings.json', ['_method' => 'GET']);

        $this->assertSuccess();
    }
}
