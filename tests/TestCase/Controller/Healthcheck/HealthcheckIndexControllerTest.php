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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Healthcheck;

use App\Controller\Healthcheck\HealthcheckIndexController;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use Cake\Core\Configure;
use Cake\Http\Client;

class HealthcheckIndexControllerTest extends AppIntegrationTestCase
{
    use HealthcheckRequestTestTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles',];

    public function testHealthcheckIndexOk(): void
    {
        $this->mockService(Client::class, function () {
            return $this->getMockedHealthcheckStatusRequest();
        });
        $this->get('/healthcheck');
        $this->assertResponseContains('Passbolt API Status');
        $this->assertResponseOk();
    }

    public function testHealthcheckIndexJsonOk(): void
    {
        $this->mockService(Client::class, function () {
            return $this->getMockedHealthcheckStatusRequest();
        });
        $this->getJson('/healthcheck.json');
        $this->assertResponseSuccess();
        $attributes = [
            'ssl', 'application', 'gpg', 'core', 'configFile', 'environment', 'database', 'smtpSettings',
        ];
        foreach ($attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $this->_responseJsonBody);
        }
    }

    /**
     * Strangely, the status returned is OK, although the healthcheck failed
     * Leaving the test as documentation
     */
    public function testHealthcheckIndex_Healthcheck_Not_Reachable(): void
    {
        $this->mockService(Client::class, function () {
            return $this->getMockedHealthcheckStatusRequest(400);
        });
        $this->get('/healthcheck');
        $this->assertResponseContains('Passbolt API Status');
        $this->assertResponseOk();
    }

    /**
     * Throw a forbidden error if the endpoint is disabled
     */
    public function testHealthcheckIndex_Healthcheck_Endpoint_Disabled(): void
    {
        Configure::write(
            HealthcheckIndexController::PASSBOLT_PLUGINS_HEALTHCHECK_SECURITY_INDEX_ENDPOINT_ENABLED,
            false
        );
        $this->logInAsAdmin();
        $this->getJson('/healthcheck.json');
        $this->assertForbiddenError('Healthcheck security index endpoint disabled.');
    }
}
