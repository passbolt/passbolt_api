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
namespace App\Test\TestCase\Service\Healthcheck\Core;

use App\Service\Healthcheck\Application\SeleniumDisabledApplicationHealthcheck;
use App\Service\Healthcheck\Core\ExcludedHealthchecksCoreHealthcheck;
use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use App\Service\Healthcheck\HealthcheckApiSerializerService;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;

/**
 * @covers \App\Service\Healthcheck\Core\ExcludedHealthchecksCoreHealthcheck
 */
class ExcludedHealthchecksCoreHealthcheckTest extends AppTestCase
{
    private ?HealthcheckServiceCollector $collector = null;

    private ?ExcludedHealthchecksCoreHealthcheck $healthcheck = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->collector = new HealthcheckServiceCollector();
        $this->collector->addService(new PhpVersionHealthcheck());
        $this->collector->addService(new SeleniumDisabledApplicationHealthcheck());
        $this->healthcheck = new ExcludedHealthchecksCoreHealthcheck(fn() => $this->collector);
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

    public function testExcludedHealthchecksCoreHealthcheck_IsSkippedWhenNothingIsExcluded(): void
    {
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, null);
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, null);

        $this->healthcheck->check();

        $this->assertTrue($this->healthcheck->isSkipped());
    }

    public function testExcludedHealthchecksCoreHealthcheck_PassesWhenEveryValueMatches(): void
    {
        Configure::write(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS,
            'php-version excluded-healthchecks',
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

    public function testExcludedHealthchecksCoreHealthcheck_FailsOnUnknownKeyAndDomain(): void
    {
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, 'php-versions');
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, 'environments');

        $this->healthcheck->check();

        $this->assertFalse($this->healthcheck->isSkipped());
        $this->assertFalse($this->healthcheck->isPassed());
        $this->assertSame(
            'The healthcheck exclusion configuration contains values matching no healthcheck on this instance.',
            $this->healthcheck->getFailureMessage(),
        );
        $this->assertContains(
            'The value must be a valid key. php-versions not valid.',
            $this->healthcheck->getHelpMessage(),
        );
        $this->assertContains(
            'The value must be a valid domain. environments not valid.',
            $this->healthcheck->getHelpMessage(),
        );
    }

    public function testExcludedHealthchecksCoreHealthcheck_FailsOnValueThatIsNeitherStringNorArray(): void
    {
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, true);
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, null);

        $this->healthcheck->check();

        $this->assertFalse($this->healthcheck->isSkipped());
        $this->assertFalse($this->healthcheck->isPassed());
        $this->assertSame(
            'The healthcheck exclusion setting passbolt.plugins.healthcheck.excludedKeys is not a valid list of healthcheck names.', // phpcs:ignore
            $this->healthcheck->getFailureMessage(),
        );
        $this->assertContains(
            'The value must be a string of names separated by spaces, or an array of names. passbolt.plugins.healthcheck.excludedKeys is not valid.', // phpcs:ignore
            $this->healthcheck->getHelpMessage(),
        );
    }

    public function testExcludedHealthchecksCoreHealthcheck_FailsOnArrayHoldingANonString(): void
    {
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, ['php-version', 42]);
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, null);

        $this->healthcheck->check();

        $this->assertFalse($this->healthcheck->isSkipped());
        $this->assertFalse($this->healthcheck->isPassed());
        $this->assertSame(
            'The healthcheck exclusion setting passbolt.plugins.healthcheck.excludedKeys is not a valid list of healthcheck names.', // phpcs:ignore
            $this->healthcheck->getFailureMessage(),
        );
    }

    public function testExcludedHealthchecksCoreHealthcheck_PassesWhenEveryValueMatchesGivenArrays(): void
    {
        Configure::write(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS,
            ['php-version', 'excluded-healthchecks'],
        );
        Configure::write(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS,
            [HealthcheckServiceCollector::DOMAIN_APPLICATION],
        );

        $this->healthcheck->check();

        $this->assertFalse($this->healthcheck->isSkipped());
        $this->assertTrue($this->healthcheck->isPassed());
    }

    public function testExcludedHealthchecksCoreHealthcheck_FailsOnUnknownValuesGivenArrays(): void
    {
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS, ['php-versions']);
        Configure::write(HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS, ['environments']);

        $this->healthcheck->check();

        $this->assertFalse($this->healthcheck->isPassed());
        $this->assertContains(
            'The value must be a valid key. php-versions not valid.',
            $this->healthcheck->getHelpMessage(),
        );
        $this->assertContains(
            'The value must be a valid domain. environments not valid.',
            $this->healthcheck->getHelpMessage(),
        );
    }
}
