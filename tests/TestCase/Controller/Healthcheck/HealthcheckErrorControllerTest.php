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
 * @since         3.7.4
 */
namespace App\Test\TestCase\Controller\Healthcheck;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;

class HealthcheckErrorControllerTest extends AppIntegrationTestCase
{
    use IntegrationTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        // Mock a client IP to check it surfaces as-is and is not hidden by
        // a proxy. Chosen IP is safe for simulating external networks (RFC5737).
        $this->mockUserIp('198.51.100.42');
    }

    public function testHealthcheckErrorController_Error_Disabled(): void
    {
        $og = Configure::read('passbolt.healthcheck.error');
        Configure::write('passbolt.healthcheck.error', false);
        $this->get('/healthcheck/error.json');
        $this->assertResponseCode(404);
        Configure::write('passbolt.healthcheck.error', $og);
    }

    public function testHealthcheckErrorController_Error_Enabled(): void
    {
        $og = Configure::read('passbolt.healthcheck.error');
        Configure::write('passbolt.healthcheck.error', true);
        $this->get('/healthcheck/error.json');
        $this->assertResponseCode(500);

        // ensure the message contains the mocked up user IP
        $this->assertResponseContains('198.51.100.42');
        Configure::write('passbolt.healthcheck.error', $og);
    }
}
