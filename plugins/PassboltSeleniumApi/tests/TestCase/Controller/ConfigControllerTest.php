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
use PassboltSeleniumApi\Controller\ConfigController;

class ConfigControllerTest extends AppIntegrationTestCase
{
    /**
     * Clears the state used for tests.
     *
     * @return void
     */
    public function tearDown()
    {
        // delete any tmp config files if any
        if (file_exists(ConfigController::EXTRA_CONFIG_FILE)) {
            unlink(ConfigController::EXTRA_CONFIG_FILE);
        }
        parent::tearDown();
    }

    public function testSeleniumConfigSetExtraConfig()
    {
        $this->postJson('/seleniumtests/setExtraConfig.json', [
            'passbolt' => ['meta' => ['description' => 'Alternative description']]
        ]);
        $this->assertSuccess();
        $this->assertContains('Additional configuration added', $this->_responseJsonHeader->message);
        $this->assertTrue(file_exists(ConfigController::EXTRA_CONFIG_FILE));
    }

    public function testSeleniumConfigGet()
    {
        $this->getJson('/seleniumtests/config.json');
        $body = $this->_responseJsonBody;
        $this->assertNotEmpty($body->passbolt->meta->description);
    }

    public function testSeleniumConfigResetExtraConfigSuccess()
    {
        $description = 'Alternative description';
        $this->postJson('/seleniumtests/setExtraConfig.json', [
            'passbolt' => ['meta' => ['description' => 'Alternative description']]
        ]);
        $this->assertSuccess();
        $this->assertTrue(file_exists(ConfigController::EXTRA_CONFIG_FILE));

        $this->getJson('/seleniumtests/resetExtraConfig.json');
        $this->assertSuccess();
        $this->assertFalse(file_exists(ConfigController::EXTRA_CONFIG_FILE));
    }

    public function testSeleniumConfigResetExtraConfigNoFile()
    {
        $this->assertFalse(file_exists(ConfigController::EXTRA_CONFIG_FILE));
        $this->getJson('/seleniumtests/resetExtraConfig.json');
        $this->assertSuccess();
        $this->assertFalse(file_exists(ConfigController::EXTRA_CONFIG_FILE));
    }
}
