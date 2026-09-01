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
    private HealthcheckApiSerializerService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new HealthcheckApiSerializerService(new HealthcheckServiceCollector());
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSerialize_ErrorLevelPassing(): void
    {
        $check = (new PhpVersionHealthcheck())->check();

        $result = $this->service->serialize([$check]);

        $this->assertSame('pass', $result['Environment'][0]['status']);
        $this->assertSame([], $result['Environment'][0]['help']);
    }

    public function testSerialize_ErrorLevelFailing(): void
    {
        Configure::write(PhpVersionHealthcheck::PHP_MIN_VERSION_CONFIG, '40');

        $check = [
            (new PhpVersionHealthcheck())->check(),
            (new SeleniumDisabledApplicationHealthcheck())->check(),
        ];

        $result = $this->service->serialize($check);

        $this->assertSame('error', $result['Environment'][0]['status']);
        $this->assertSame('error', $result['Application configuration'][0]['status']);
        $this->assertSame([], $result['Environment'][0]['help']);
        $this->assertSame(
            ['Set passbolt.selenium.active to false in /var/www/html/config/passbolt.php.'],
            $result['Application configuration'][0]['help'],
        );
    }

    public function testSerialize_WarningLevelPassing(): void
    {
        $check = (new NextMinPhpVersionHealthcheck())->check();

        $result = $this->service->serialize([$check]);

        $this->assertSame('pass', $result['Environment'][0]['status']);
        $this->assertSame([], $result['Environment'][0]['help']);
    }

    public function testSerialize_WarningLevelFailing(): void
    {
        Configure::write(NextMinPhpVersionHealthcheck::PHP_NEXT_MIN_VERSION_CONFIG, '50');

        $check = [
            (new NextMinPhpVersionHealthcheck())->check(),
            (new PluginEnabledJwtHealthcheck())->check(),
        ];

        $result = $this->service->serialize($check);

        $this->assertSame('warning', $result['Environment'][0]['status']);
        $this->assertSame('warning', $result['JWT Authentication'][0]['status']);
        $this->assertSame([], $result['Environment'][0]['help']);
        $this->assertSame(
            ['Set the environment variable PASSBOLT_PLUGINS_JWT_AUTHENTICATION_ENABLED to true'],
            $result['JWT Authentication'][0]['help'],
        );
    }

    public function testSerialize_InfoLevelPassing(): void
    {
        $this->enableFeaturePlugin('Scim');

        $check = (new ScimHealthcheck())->check();

        $result = $this->service->serialize([$check]);

        $this->assertSame('info', $result['Scim'][0]['status']);
        $this->assertSame($check->getSuccessMessage(), $result['Scim'][0]['message']);
    }

    public function testSerialize_InfoLevelFailing(): void
    {
        $this->disableFeaturePlugin('Scim');

        $check = (new ScimHealthcheck())->check();

        $result = $this->service->serialize([$check]);

        $this->assertSame('info', $result['Scim'][0]['status']);
        $this->assertSame($check->getFailureMessage(), $result['Scim'][0]['message']);
        $this->assertSame([], $result['Scim'][0]['help']);
    }

    public function testSerialize_DomainGrouping(): void
    {
        $check = [
            (new PhpVersionHealthcheck())->check(),
            (new AppConfigFileHealthcheck())->check(),
            (new NextMinPhpVersionHealthcheck())->check(),
            (new TimeSyncHealthcheck())->check(),
        ];

        $result = $this->service->serialize($check);

        $this->assertCount(3, $result['Environment']);
        $this->assertCount(1, $result['Config files']);
    }
}
