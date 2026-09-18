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
 * @since         5.16.0
 */
namespace App\Test\TestCase\Service\Healthcheck;

use App\Service\Healthcheck\Application\SeleniumDisabledApplicationHealthcheck;
use App\Service\Healthcheck\Environment\NextMinPhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use App\Service\Healthcheck\HealthcheckApiSerializerService;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\Jwt\PluginEnabledJwtHealthcheck;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Passbolt\Metadata\Service\Healthcheck\ZeroKnowledge\ServerMetadataKeyAccessInZeroKnowledgeModeHealthcheck;
use Passbolt\Scim\Service\Healthcheck\ScimHealthcheck;

class HealthcheckApiSerializerServiceTest extends AppTestCase
{
    /**
     * @var \App\Service\Healthcheck\HealthcheckServiceCollector
     */
    private HealthcheckServiceCollector $collector;

    /**
     * @var \App\Service\Healthcheck\HealthcheckApiSerializerService
     */
    private HealthcheckApiSerializerService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->collector = new HealthcheckServiceCollector();
        $this->service = new HealthcheckApiSerializerService($this->collector);
    }

    public function tearDown(): void
    {
        unset($this->collector, $this->service);
        parent::tearDown();
    }

    public function testHealthcheckApiSerializerServiceTest_ErrorLevelPassing(): void
    {
        Configure::write(PhpVersionHealthcheck::PHP_MIN_VERSION_CONFIG, PHP_VERSION);

        $this->collector->addService(new PhpVersionHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame('php-version', $result[0]['key']);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_PASS, $result[0]['status']);
        $this->assertSame([], $result[0]['help']);
    }

    public function testHealthcheckApiSerializerServiceTest_ErrorLevelFailing(): void
    {
        Configure::write(PhpVersionHealthcheck::PHP_MIN_VERSION_CONFIG, '40');

        $this->collector->addService(new PhpVersionHealthcheck());
        $this->collector->addService(new SeleniumDisabledApplicationHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame('php-version', $result[0]['key']);
        $this->assertSame('selenium-disabled-application', $result[1]['key']);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_ERROR, $result[0]['status']);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_ERROR, $result[1]['status']);
        $this->assertSame([], $result[0]['help']);
        $this->assertSame(
            ['Set passbolt.selenium.active to false in ' . CONFIG . 'passbolt.php.'],
            $result[1]['help'],
        );
    }

    public function testHealthcheckApiSerializerServiceTest_WarningLevelPassing(): void
    {
        Configure::write(NextMinPhpVersionHealthcheck::PHP_NEXT_MIN_VERSION_CONFIG, PHP_VERSION);

        $this->collector->addService(new NextMinPhpVersionHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame('next-min-php-version', $result[0]['key']);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_PASS, $result[0]['status']);
        $this->assertSame([], $result[0]['help']);
    }

    public function testHealthcheckApiSerializerServiceTest_WarningLevelFailing(): void
    {
        Configure::write(NextMinPhpVersionHealthcheck::PHP_NEXT_MIN_VERSION_CONFIG, '50');

        $this->collector->addService(new NextMinPhpVersionHealthcheck());
        $this->collector->addService(new PluginEnabledJwtHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame('next-min-php-version', $result[0]['key']);
        $this->assertSame('plugin-enabled-jwt', $result[1]['key']);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_WARNING, $result[0]['status']);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_WARNING, $result[1]['status']);
        $this->assertSame([], $result[0]['help']);
        $this->assertSame(
            ['Set the environment variable PASSBOLT_PLUGINS_JWT_AUTHENTICATION_ENABLED to true'],
            $result[1]['help'],
        );
    }

    public function testHealthcheckApiSerializerServiceTest_InfoLevelPassing(): void
    {
        $this->enableFeaturePlugin('Scim');

        $this->collector->addService(new ScimHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame('scim', $result[0]['key']);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_INFO, $result[0]['status']);
        $this->assertSame('SCIM plugin is enabled.', $result[0]['message']);
    }

    public function testHealthcheckApiSerializerServiceTest_InfoLevelFailing(): void
    {
        $this->disableFeaturePlugin('Scim');

        $this->collector->addService(new ScimHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame('scim', $result[0]['key']);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_INFO, $result[0]['status']);
        $this->assertSame('SCIM plugin is disabled.', $result[0]['message']);
        $this->assertSame([], $result[0]['help']);
    }

    public function testHealthcheckApiSerializerServiceTest_ReturnsTheAgreedFormat(): void
    {
        Configure::write(PhpVersionHealthcheck::PHP_MIN_VERSION_CONFIG, PHP_VERSION);

        $this->collector->addService(new PhpVersionHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame([
            'key' => 'php-version',
            'domain_key' => HealthcheckServiceCollector::DOMAIN_ENVIRONMENT,
            'domain' => [
                'key' => HealthcheckServiceCollector::DOMAIN_ENVIRONMENT,
                'title' => 'Environment',
            ],
            'status' => HealthcheckApiSerializerService::STATUS_PASS,
            'message' => 'PHP version ' . PHP_VERSION . '.',
            'help' => [],
        ], $result[0]);
    }

    public function testHealthcheckApiSerializerServiceTest_ReturnsFlatListNotGroupedByDomain(): void
    {
        $this->collector->addService(new PhpVersionHealthcheck());
        $this->collector->addService(new NextMinPhpVersionHealthcheck());

        $result = $this->service->serialize();

        $this->assertCount(2, $result);
        $this->assertArrayHasKey('key', $result[0]);
        $this->assertArrayHasKey('key', $result[1]);
    }

    public function testHealthcheckApiSerializerServiceTest_ExcludesSkippedHealthcheck(): void
    {
        $this->collector->addService(new PhpVersionHealthcheck());
        $this->collector->addService(new ServerMetadataKeyAccessInZeroKnowledgeModeHealthcheck());

        $result = $this->service->serialize();

        $this->assertCount(1, $result);
        $this->assertNotSame('server-metadata-key-access-in-zero-knowledge-mode', $result[0]['key']);
    }

    public function testHealthcheckApiSerializerServiceTest_ExcludesHealthcheckByKey(): void
    {
        Configure::write(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS,
            'php-version selenium-disabled-application',
        );
        $this->collector->addService(new PhpVersionHealthcheck());
        $this->collector->addService(new NextMinPhpVersionHealthcheck());
        $this->collector->addService(new PluginEnabledJwtHealthcheck());
        $this->collector->addService(new SeleniumDisabledApplicationHealthcheck());
        $result = $this->service->serialize();

        $this->assertCount(2, $result);
        $this->assertSame(['next-min-php-version', 'plugin-enabled-jwt'], array_column($result, 'key'));
    }

    public function testHealthcheckApiSerializerServiceTest_ExcludesHealthchecksByDomain(): void
    {
        Configure::write(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS,
            HealthcheckServiceCollector::DOMAIN_ENVIRONMENT . ' ' . HealthcheckServiceCollector::DOMAIN_JWT,
        );
        $this->collector->addService(new PhpVersionHealthcheck());
        $this->collector->addService(new NextMinPhpVersionHealthcheck());
        $this->collector->addService(new PluginEnabledJwtHealthcheck());
        $this->collector->addService(new SeleniumDisabledApplicationHealthcheck());
        $result = $this->service->serialize();

        $this->assertCount(1, $result);
        $this->assertSame(['selenium-disabled-application'], array_column($result, 'key'));
    }

    public function testHealthcheckApiSerializerServiceTest_ExcludesHealthchecksByKeysAndDomainsTogether(): void
    {
        Configure::write(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS,
            ['php-version next-min-php-version', 'php-version', '', '  '],
        );
        Configure::write(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS,
            HealthcheckServiceCollector::DOMAIN_JWT,
        );
        $this->collector->addService(new PhpVersionHealthcheck());
        $this->collector->addService(new NextMinPhpVersionHealthcheck());
        $this->collector->addService(new PluginEnabledJwtHealthcheck());
        $this->collector->addService(new SeleniumDisabledApplicationHealthcheck());

        $result = $this->service->serialize();

        $this->assertCount(2, $result);
        $this->assertSame(['selenium-disabled-application'], array_column($result, 'key'));
    }
}
