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
namespace App\Test\TestCase\Service\Healthcheck\ConfigFiles;

use App\Service\Healthcheck\Application\SeleniumDisabledApplicationHealthcheck;
use App\Service\Healthcheck\ConfigFiles\ExcludedHealthchecksConfigurationHealthcheck;
use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use App\Service\Healthcheck\HealthcheckApiSerializerService;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;

/**
 * @covers \App\Service\Healthcheck\ConfigFiles\ExcludedHealthchecksConfigurationHealthcheck
 */
class ExcludedHealthchecksConfigurationHealthcheckTest extends AppTestCase
{
    private ?HealthcheckServiceCollector $collector = null;

    private ?ExcludedHealthchecksConfigurationHealthcheck $healthcheck = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->collector = new HealthcheckServiceCollector();
        $this->collector->addService(new PhpVersionHealthcheck());
        $this->collector->addService(new SeleniumDisabledApplicationHealthcheck());
        $this->healthcheck = new ExcludedHealthchecksConfigurationHealthcheck($this->collector);
        $this->collector->addService($this->healthcheck);
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->collector, $this->healthcheck);

        parent::tearDown();
    }

    public function testExcludedHealthchecksConfigurationHealthcheck_IsSkippedWhenNothingIsExcluded(): void
    {
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, null);
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, null);

        $this->healthcheck->check();

        $this->assertTrue($this->healthcheck->isSkipped());
    }

    public function testExcludedHealthchecksConfigurationHealthcheck_PassesWhenEveryValueMatches(): void
    {
        Configure::write(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS,
            'php-version excluded-healthchecks-configuration',
        );
        Configure::write(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS,
            HealthcheckServiceCollector::DOMAIN_APPLICATION,
        );

        $this->healthcheck->check();

        $this->assertFalse($this->healthcheck->isSkipped());
        $this->assertTrue($this->healthcheck->isPassed());
        $this->assertSame('The healthcheck exclusion configuration is valid.', $this->healthcheck->getSuccessMessage());
    }

    public function testExcludedHealthchecksConfigurationHealthcheck_FailsOnUnknownKeyAndDomain(): void
    {
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, 'php-versions');
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, 'environments');

        $this->healthcheck->check();

        $this->assertFalse($this->healthcheck->isSkipped());
        $this->assertFalse($this->healthcheck->isPassed());
        $this->assertStringContainsString('php-versions', $this->healthcheck->getFailureMessage());
        $this->assertStringContainsString('environments', $this->healthcheck->getFailureMessage());
    }

    public function testExcludedHealthchecksConfigurationHealthcheck_FailsOnNonStringValue(): void
    {
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, true);
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, null);

        $this->healthcheck->check();

        $this->assertFalse($this->healthcheck->isSkipped());
        $this->assertFalse($this->healthcheck->isPassed());
        $this->assertSame(
            'The healthcheck exclusion setting passbolt.plugins.healthcheck.excludedKeys is not a space separated list of names.', // phpcs:ignore
            $this->healthcheck->getFailureMessage(),
        );
        $this->assertContains(
            'An exclusion setting must be a single string holding the names separated by spaces, for example "php-version distribution".', // phpcs:ignore
            $this->healthcheck->getHelpMessage(),
        );
        $this->assertContains(
            'An array, a boolean or a integer is not a supported value for these settings.',
            $this->healthcheck->getHelpMessage(),
        );
    }

    public function testExcludedHealthchecksConfigurationHealthcheck_ReportsBothSettingsWhenBothAreNotStrings(): void
    {
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, ['php-version']);
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, false);

        $this->healthcheck->check();

        $this->assertFalse($this->healthcheck->isPassed());
        $this->assertSame(
            'The healthcheck exclusion setting passbolt.plugins.healthcheck.excludedKeys is not a space separated list of names. ' // phpcs:ignore
            . 'The healthcheck exclusion setting passbolt.plugins.healthcheck.excludedDomains is not a space separated list of names.', // phpcs:ignore
            $this->healthcheck->getFailureMessage(),
        );
    }
}
