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
 * @since         4.7.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Controller\Healthcheck;

use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Http\TestSuite\HttpClientTrait;
use Passbolt\DirectorySync\Middleware\DirectorySyncEndpointsSecurityMiddleware;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;

/**
 * @covers \App\Controller\Healthcheck\HealthcheckIndexController
 */
class DirectorySyncHealthcheckIndexControllerTest extends DirectorySyncIntegrationTestCase
{
    use HealthcheckRequestTestTrait;
    use HttpClientTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockService(Client::class, function () {
            return $this->getMockedHealthcheckStatusRequest();
        });
        $this->mockService('fullBaseUrlReachableClient', function () {
            return $this->getMockedHealthcheckStatusRequest(
                200,
                json_encode(['body' => 'OK'])
            );
        });
        $this->mockService('sslHealthcheckClient', function () {
            return $this->getMockedHealthcheckStatusRequest();
        });
    }

    public function testDirectorySyncHealthcheckIndexController_Success(): void
    {
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('directorySync', $result);
        $this->assertArrayEqualsCanonicalizing(
            ['endpointsDisabled' => false, 'sslVerifyPeer' => true],
            $result['directorySync']
        );
    }

    public function testDirectorySyncHealthcheckIndexController_Fail(): void
    {
        Configure::write(DirectorySyncEndpointsSecurityMiddleware::SECURITY_CONFIG_KEY, true);
        Configure::write('passbolt.plugins.directorySync.security.sslCustomOptions.enabled', true);
        Configure::write('passbolt.plugins.directorySync.security.sslCustomOptions.verifyPeer', false);
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('directorySync', $result);
        $this->assertArrayEqualsCanonicalizing(
            ['endpointsDisabled' => true, 'sslVerifyPeer' => false],
            $result['directorySync']
        );
    }
}
