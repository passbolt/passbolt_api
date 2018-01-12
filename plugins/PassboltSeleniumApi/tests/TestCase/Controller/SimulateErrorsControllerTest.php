<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\SeleniumTests;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;

class SimulateErrorsControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/users'];

    public function testSimulateError404()
    {
        $this->getJson('/seleniumtests/error404.json');
        $this->assertError(404);
    }

    public function testSimulateError403()
    {
        $this->getJson('/seleniumtests/error403.json');
        $this->assertError(403);
    }

    public function testSimulateError400()
    {
        $this->getJson('/seleniumtests/error400.json');
        $this->assertError(400);
    }

    public function testSimulateError500()
    {
        $this->getJson('/seleniumtests/error500.json');
        $this->assertError(500);
    }

    public function testSimulateErrorNotFound()
    {
        // Check selenium api endpoints are marked as not found when
        // selenium is marked as inactive in the config
        Configure::write('passbolt.selenium.active', false);
        $this->getJson('/seleniumtests/error404.json');
        $this->assertError(404);
    }
}
