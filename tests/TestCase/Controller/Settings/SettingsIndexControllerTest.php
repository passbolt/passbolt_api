<?php
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

namespace App\Test\TestCase\Controller\Settings;

use App\Test\Lib\AppIntegrationTestCase;

class SettingsIndexControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [];

    public function testSettingsIndexGetSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/settings.json?api-version=2');
        $this->assertSuccess();
        $this->assertGreaterThan(0, count((array)$this->_responseJsonBody));
        $this->assertGreaterThan(1, count((array)$this->_responseJsonBody->app));
        $this->assertTrue(isset($this->_responseJsonBody->app->version));
    }

    public function testSettingsIndexErrorNotAuthenticated()
    {
        $this->getJson('/settings.json?api-version=2');
        $this->assertSuccess();
        $this->assertGreaterThan(0, count((array)$this->_responseJsonBody));
        $this->assertFalse(isset($this->_responseJsonBody->app->version));
        $this->assertTrue(isset($this->_responseJsonBody->app->url));
    }
}
