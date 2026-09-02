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
use App\Service\Healthcheck\ConfigFiles\AppConfigFileHealthcheck;
use App\Service\Healthcheck\Environment\NextMinPhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\TimeSyncHealthcheck;
use App\Service\Healthcheck\HealthcheckApiSerializerService;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\Jwt\PluginEnabledJwtHealthcheck;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
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

        $this->assertSame(HealthcheckApiSerializerService::STATUS_PASS, $result['Environment'][0]['status']);
        $this->assertSame([], $result['Environment'][0]['help']);
    }

    public function testHealthcheckApiSerializerServiceTest_ErrorLevelFailing(): void
    {
        Configure::write(PhpVersionHealthcheck::PHP_MIN_VERSION_CONFIG, '40');

        $this->collector->addService(new PhpVersionHealthcheck());
        $this->collector->addService(new SeleniumDisabledApplicationHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame(HealthcheckApiSerializerService::STATUS_ERROR, $result['Environment'][0]['status']);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_ERROR, $result['Application configuration'][0]['status']);
        $this->assertSame([], $result['Environment'][0]['help']);
        $this->assertSame(
            ['Set passbolt.selenium.active to false in ' . CONFIG . 'passbolt.php.'],
            $result['Application configuration'][0]['help'],
        );
    }

    public function testHealthcheckApiSerializerServiceTest_WarningLevelPassing(): void
    {
        Configure::write(NextMinPhpVersionHealthcheck::PHP_NEXT_MIN_VERSION_CONFIG, PHP_VERSION);

        $this->collector->addService(new NextMinPhpVersionHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame(HealthcheckApiSerializerService::STATUS_PASS, $result['Environment'][0]['status']);
        $this->assertSame([], $result['Environment'][0]['help']);
    }

    public function testHealthcheckApiSerializerServiceTest_WarningLevelFailing(): void
    {
        Configure::write(NextMinPhpVersionHealthcheck::PHP_NEXT_MIN_VERSION_CONFIG, '50');

        $this->collector->addService(new NextMinPhpVersionHealthcheck());
        $this->collector->addService(new PluginEnabledJwtHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame(HealthcheckApiSerializerService::STATUS_WARNING, $result['Environment'][0]['status']);
        $this->assertSame(HealthcheckApiSerializerService::STATUS_WARNING, $result['JWT Authentication'][0]['status']);
        $this->assertSame([], $result['Environment'][0]['help']);
        $this->assertSame(
            ['Set the environment variable PASSBOLT_PLUGINS_JWT_AUTHENTICATION_ENABLED to true'],
            $result['JWT Authentication'][0]['help'],
        );
    }

    public function testHealthcheckApiSerializerServiceTest_InfoLevelPassing(): void
    {
        $this->enableFeaturePlugin('Scim');

        $this->collector->addService(new ScimHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame(HealthcheckApiSerializerService::STATUS_INFO, $result['Scim'][0]['status']);
        $this->assertSame('SCIM plugin is enabled.', $result['Scim'][0]['message']);
    }

    public function testHealthcheckApiSerializerServiceTest_InfoLevelFailing(): void
    {
        $this->disableFeaturePlugin('Scim');

        $this->collector->addService(new ScimHealthcheck());

        $result = $this->service->serialize();

        $this->assertSame(HealthcheckApiSerializerService::STATUS_INFO, $result['Scim'][0]['status']);
        $this->assertSame('SCIM plugin is disabled.', $result['Scim'][0]['message']);
        $this->assertSame([], $result['Scim'][0]['help']);
    }

    public function testHealthcheckApiSerializerServiceTest_DomainGrouping(): void
    {
        $this->collector->addService(new PhpVersionHealthcheck());
        $this->collector->addService(new AppConfigFileHealthcheck());
        $this->collector->addService(new NextMinPhpVersionHealthcheck());
        $this->collector->addService(new TimeSyncHealthcheck());

        $result = $this->service->serialize();

        $this->assertCount(3, $result['Environment']);
        $this->assertCount(1, $result['Config files']);
    }
}
