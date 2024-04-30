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
namespace Passbolt\Sso\Test\TestCase\Controller\Healthcheck;

use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use Cake\Http\Client;
use Cake\Http\TestSuite\HttpClientTrait;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

/**
 * @covers \App\Controller\Healthcheck\HealthcheckIndexController
 */
class SsoHealthcheckIndexControllerTest extends SsoIntegrationTestCase
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

    public function testSsoHealthcheckIndexController_Success_Json(): void
    {
        $this->logInAsAdmin();
        $this->getJson('/healthcheck.json');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('sso', $result);
        $this->assertArrayEqualsCanonicalizing(['sslHostVerification' => true], $result['sso']);
    }
}
