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
 * @since         5.17.0
 */
namespace App\Test\TestCase\Controller\Healthcheck;

use App\Controller\Healthcheck\HealthcheckIndexController;
use App\Test\Factory\RoleFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Http\TestSuite\HttpClientTrait;
use Passbolt\JwtAuthentication\JwtAuthenticationPlugin;
use Passbolt\SmtpSettings\SmtpSettingsPlugin;

/**
 * Covers the generic response format, requested via api-version=v5.
 *
 * Kept separate from HealthcheckIndexControllerTest so that the legacy coverage
 * stays untouched and can be dropped as a whole class when legacy is removed.
 *
 * @covers \App\Controller\Healthcheck\HealthcheckIndexController
 */
class HealthcheckIndexControllerV5Test extends AppIntegrationTestCase
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
                json_encode(['body' => 'OK']),
            );
        });
        $this->mockService('sslHealthcheckClient', function () {
            return $this->getMockedHealthcheckStatusRequest();
        });
    }

    public function testHealthcheckIndexControllerV5_No_Admin_Should_Throw_Exception(): void
    {
        $this->logInAsUser();
        $this->getJson('/healthcheck.json?api-version=v5');
        $this->assertForbiddenError('Access restricted to administrators.');
    }

    public function testHealthcheckIndexControllerV5_NotAuthenticated_ShouldThrowException(): void
    {
        $this->getJson('/healthcheck.json?api-version=v5');
        $this->assertResponseCode(401);
    }

    /**
     * Throw a forbidden error if the endpoint is disabled
     */
    public function testHealthcheckIndexControllerV5_ErrorEndpointDisabled_Json(): void
    {
        $this->logInAsAdmin();
        Configure::write(
            HealthcheckIndexController::PASSBOLT_PLUGINS_HEALTHCHECK_SECURITY_INDEX_ENDPOINT_ENABLED,
            false,
        );
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json?api-version=v5');

        $this->assertForbiddenError('Healthcheck security index endpoint disabled.');
        // Assert body
        $resultBody = $this->_responseJsonBody;
        $this->assertSame('', $resultBody);
        // Assert headers
        $resultHeader = $this->_responseJsonHeader;
        $expectedHeaderAttributes = ['id', 'status', 'servertime', 'action', 'message', 'url', 'code'];
        $this->assertObjectHasAttributes($expectedHeaderAttributes, $resultHeader);
        $this->assertSame('error', $resultHeader->status);
        $this->assertSame('/healthcheck.json?api-version=v5', $resultHeader->url);
    }

    public function testHealthcheckIndexControllerV5_ReturnsStandardEnvelope(): void
    {
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json?api-version=v5');

        $this->assertResponseSuccess();

        $resultHeader = $this->_responseJsonHeader;
        $expectedHeaderAttributes = ['id', 'status', 'servertime', 'action', 'message', 'url', 'code'];
        $this->assertObjectHasAttributes($expectedHeaderAttributes, $resultHeader);
        $this->assertSame('success', $resultHeader->status);
        $this->assertSame(200, $resultHeader->code);
    }

    public function testHealthcheckIndexControllerV5_ReturnsGenericFormat(): void
    {
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json?api-version=v5');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();

        $this->assertArrayHasKey('Environment', $result);
        $this->assertArrayNotHasKey('environment', $result);
    }

    public function testHealthcheckIndexControllerV5_Success_Json(): void
    {
        $this->enableFeaturePlugin(SmtpSettingsPlugin::class);
        $this->enableFeaturePlugin(JwtAuthenticationPlugin::class);
        RoleFactory::make(3)->persist();
        $this->logInAsAdmin();
        $this->getJson('/healthcheck.json?api-version=v5');
        $this->assertResponseSuccess();

        $result = $this->getResponseBodyAsArray();

        $expectedDomains = [
            'Environment',
            'Config files',
            'Core config',
            'SSL Certificate',
            'SMTP settings',
            'JWT Authentication',
            'GPG Configuration',
            'Application configuration',
            'Database',
            'Metadata',
        ];
        foreach ($expectedDomains as $domain) {
            $this->assertArrayHasKey($domain, $result);
            $this->assertNotEmpty($result[$domain], "Domain {$domain} was returned with no checks.");
        }
    }
}
