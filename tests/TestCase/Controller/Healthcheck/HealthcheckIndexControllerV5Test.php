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
use App\Service\Healthcheck\HealthcheckApiSerializerService;
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
use Cake\Event\EventManager;
use Cake\Http\Client;
use Cake\Http\TestSuite\HttpClientTrait;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\DirectorySync\DirectorySyncPlugin;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\WebInstaller\WebInstallerPlugin;

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

    /**
     * Renaming a key silently breaks the excludedKeys configuration of an existing instance.
     *
     * @var array<string>
     */
    private const COLLECTED_HEALTHCHECK_KEYS = [
        'distribution',
        'php-version',
        'next-min-php-version',
        'operating-system',
        'gpg-version',
        'pcre',
        'mbstring',
        'intl',
        'image',
        'tmp-folder-writable',
        'log-folder-writable',
        'time-sync',
        'app-config-file',
        'passbolt-config-file',
        'cache',
        'debug-disabled',
        'excluded-healthchecks',
        'salt',
        'full-base-url',
        'valid-full-base-url',
        'full-base-url-reachable',
        'peer-valid',
        'host-valid',
        'not-self-signed',
        'smtp-settings-plugin-enabled',
        'jwt-plugin-enabled',
        'php-gpg-module-installed',
        'home-variable-defined',
        'home-variable-writable',
        'key-not-default',
        'public-key-readable-and-parsable',
        'private-key-readable-and-parsable',
        'fingerprint-match',
        'public-key-in-keyring',
        'public-key-email',
        'can-encrypt',
        'can-sign',
        'can-encrypt-sign',
        'can-decrypt',
        'can-decrypt-verify',
        'can-verify',
        'gopengpg-public-key-format',
        'gopengpg-private-key-format',
        'latest-version',
        'ssl-force',
        'ssl-full-base-url',
        'selenium-disabled',
        'robots-index-disabled',
        'self-registration-plugin-enabled',
        'self-registration-provider',
        'self-registration-public-removed',
        'host-availability-check-enabled',
        'js-prod',
        'email-notification-enabled',
        'session-provider',
        'connect',
        'tables-count',
        'default-content',
        'mariadb-mysql-version-deprecate',
        'schema-up-to-date',
        'model-cache',
        'subscription-key',
        'directory-not-writable',
        'valid-key-pair',
        'edition-present-in-database',
        'server-can-decrypt-metadata-private-key',
        'no-active-metadata-key',
        'server-missing-access-to-metadata-key',
        'server-private-metadata-key-validate',
        'server-metadata-key-access-in-zero-knowledge-mode',
        'settings-validation',
        'smtp-settings-settings-source',
        'smtp-settings-endpoints-disabled',
        'custom-ssl-options',
        'directory-sync-endpoints-disabled',
        'ssl-verify-peer',
        'ssl-host-verification',
        'sso-edit-endpoints-disabled',
        'sso-egress-guard-disabled',
        'scim-plugin-enabled',
        'scim-secret-token-expiry',
        'passbolt-config-writable-web-installer',
        'public-key-writable-web-installer',
        'private-key-writable-web-installer',
        'is-request-https',
    ];

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
        $this->enableFeaturePlugin(DirectorySyncPlugin::class);
        EventManager::instance()->on('Application.buildContainer', function (Event $event, ContainerInterface $container) {
            (new WebInstallerPlugin())->services($container);
            /** @var HealthcheckServiceCollector $collector */
            $collector = $container->get(HealthcheckServiceCollector::class);
            $this->assertServiceKeys($collector);
        });
        RoleFactory::make(3)->persist();
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json?api-version=v5');

        $this->assertResponseSuccess();
    }

    private function assertServiceKeys(HealthcheckServiceCollector $collector): void
    {
        $returnedKeys = [];
        foreach ($collector->getServices() as $service) {
            $returnedKeys[] = $service->getKey();
        }

        $this->assertSame(self::COLLECTED_HEALTHCHECK_KEYS, $returnedKeys);
    }

    public function testHealthcheckIndexControllerV5_ExcludesHealthchecksByKeysAndDomainsTogether(): void
    {
        $this->enableFeaturePlugin(SmtpSettingsPlugin::class);
        $this->enableFeaturePlugin(JwtAuthenticationPlugin::class);
        RoleFactory::make(3)->persist();
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, 'php-version next-min-php-version');
        Configure::write(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS,
            HealthcheckServiceCollector::DOMAIN_APPLICATION,
        );
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json?api-version=v5');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();

        $this->assertNotEmpty($result);
        $this->assertNotContains('php-version', array_column($result, 'key'));
        $this->assertNotContains('next-min-php-version', array_column($result, 'key'));
        $this->assertNotContains(
            HealthcheckServiceCollector::DOMAIN_APPLICATION,
            array_column($result, 'domain_key'),
        );
        $this->assertContains(HealthcheckServiceCollector::DOMAIN_ENVIRONMENT, array_column($result, 'domain_key'));
    }

    public function testHealthcheckIndexControllerV5_NoExclusionConfiguredHidesTheExclusionHealthcheck(): void
    {
        RoleFactory::make(3)->persist();
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, null);
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, null);
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json?api-version=v5');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();

        $this->assertNotEmpty($result);
        $this->assertNotContains('excluded-healthchecks', array_column($result, 'key'));
    }

    public function testHealthcheckIndexControllerV5_MisspeltExcludedDomainIsReported(): void
    {
        RoleFactory::make(3)->persist();
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, 'environments');
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json?api-version=v5');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();

        $entries = array_values(array_filter(
            $result,
            fn(array $entry) => $entry['key'] === 'excluded-healthchecks',
        ));
        $this->assertCount(1, $entries);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_WARNING, $entries[0]['status']);
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_CORE, $entries[0]['domain_key']);
        $this->assertStringContainsString(
            'The healthcheck exclusion configuration contains values matching no healthcheck on this instance.',
            $entries[0]['message'],
        );
        $this->assertContains(
            'The value must be a valid domain. environments not valid.',
            $entries[0]['help'],
        );
        $this->assertContains(HealthcheckServiceCollector::DOMAIN_ENVIRONMENT, array_column($result, 'domain_key'));
    }

    public function testHealthcheckIndexControllerV5_InvalidExcludedSettingIsReported(): void
    {
        RoleFactory::make(3)->persist();
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, ['php-version', 42]);
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, true);
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json?api-version=v5');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();

        $entries = array_values(array_filter(
            $result,
            fn(array $entry) => $entry['key'] === 'excluded-healthchecks',
        ));
        $this->assertCount(1, $entries);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_WARNING, $entries[0]['status']);
        $this->assertStringContainsString(
            'passbolt.plugins.healthcheck.excludedKeys is not a valid list of healthcheck names.',
            $entries[0]['message'],
        );
        $this->assertStringContainsString(
            'passbolt.plugins.healthcheck.excludedDomains is not a valid list of healthcheck names.',
            $entries[0]['message'],
        );
        $this->assertContains('php-version', array_column($result, 'key'));
    }
}
