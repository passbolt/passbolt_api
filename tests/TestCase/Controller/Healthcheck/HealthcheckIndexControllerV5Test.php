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
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Factory\RoleFactory;
use App\Test\Lib\Utility\ErrorIntegrationTestTrait;
use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use App\Test\Lib\Utility\JsonRequestTrait;
use App\Test\Lib\Utility\LoginTestTrait;
use App\Test\Lib\Utility\ObjectTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Event\Event;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Client;
use Cake\Http\TestSuite\HttpClientTrait;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\DirectorySync\DirectorySyncPlugin;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

/**
 * Covers the generic response format, requested via api-version=v5.
 *
 * Kept separate from HealthcheckIndexControllerTest so that the legacy coverage
 * stays untouched and can be dropped as a whole class when legacy is removed.
 *
 * @covers \App\Controller\Healthcheck\HealthcheckIndexController
 */
class HealthcheckIndexControllerV5Test extends TestCase
{
    use ErrorIntegrationTestTrait;
    use FeaturePluginAwareTrait;
    use HealthcheckRequestTestTrait;
    use HttpClientTrait;
    use IntegrationTestTrait;
    use JsonRequestTrait;
    use LoginTestTrait;
    use ObjectTrait;
    use TruncateDirtyTables;

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

    public function tearDown(): void
    {
        $this->clearPlugins();
        MfaSettings::clear();
        parent::tearDown();
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

        $this->assertNotEmpty($result);
        $this->assertArrayNotHasKey('Environment', $result);
        $this->assertArrayNotHasKey('environment', $result);
        foreach ($result as $entry) {
            $this->assertSame(
                ['key', 'domain_key', 'domain', 'status', 'message', 'help'],
                array_keys($entry),
            );
            $this->assertSame(['key', 'title'], array_keys($entry['domain']));
        }
    }

    public function testHealthcheckIndexControllerV5_Success_Json(): void
    {
        $this->enableFeaturePlugin(DirectorySyncPlugin::class);
        RoleFactory::make(3)->persist();
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json?api-version=v5');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();

        $domains = array_column($result, 'domain_key');

        $expectedDomains = [
            HealthcheckServiceCollector::DOMAIN_ENVIRONMENT,
            HealthcheckServiceCollector::DOMAIN_CONFIG_FILES,
            HealthcheckServiceCollector::DOMAIN_CORE,
            HealthcheckServiceCollector::DOMAIN_SSL,
            HealthcheckServiceCollector::DOMAIN_SMTP_SETTINGS,
            HealthcheckServiceCollector::DOMAIN_JWT,
            HealthcheckServiceCollector::DOMAIN_GPG,
            HealthcheckServiceCollector::DOMAIN_APPLICATION,
            HealthcheckServiceCollector::DOMAIN_DATABASE,
            HealthcheckServiceCollector::DOMAIN_METADATA,
            EeHealthcheckServiceCollector::DOMAIN_SSO,
            EeHealthcheckServiceCollector::DOMAIN_SCIM,
            EeHealthcheckServiceCollector::DOMAIN_DIRECTORY_SYNC,
        ];
        foreach ($expectedDomains as $domain) {
            $this->assertContains($domain, $domains, "Domain {$domain} is missing from the response.");
        }
    }

    public function testHealthcheckIndexControllerV5_KeysAreDefinedAndUnique(): void
    {
        EventManager::instance()->setEventList(new EventList());
        EventManager::instance()->on('Application.buildContainer', function (Event $event, ContainerInterface $container) {
            /** @var HealthcheckServiceCollector $collector */
            $collector = $container->get(HealthcheckServiceCollector::class);
            $this->assertServiceKeys($collector);
        });
        $this->enableFeaturePlugin(DirectorySyncPlugin::class);
        RoleFactory::make(3)->persist();
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json?api-version=v5');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();

        $this->assertNotEmpty($result);
    }

    private function assertServiceKeys(HealthcheckServiceCollector $collector): void
    {
        $returnedKeys = [];
        foreach ($collector->getServices() as $service) {
            $returnedKeys[] = $service->getKey();
        }
        $expectedKeys = [
            // To populate
        ];

        $this->assertSame($expectedKeys, $returnedKeys);
    }
}
