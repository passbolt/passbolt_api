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
 * @since         5.3.2
 */

namespace Passbolt\Sso\Test\TestCase\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use Cake\Core\Configure;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;
use Passbolt\Sso\Middleware\SsoEndpointsSecurityMiddleware;
use Passbolt\Sso\Service\Healthcheck\SsoEditEndpointsDisabledHealthcheck;
use Passbolt\Sso\Test\Lib\SsoTestCase;

/**
 * @covers \Passbolt\Sso\Service\Healthcheck\SsoEditEndpointsDisabledHealthcheck
 */
class SsoEndpointsDisabledHealthcheckTest extends SsoTestCase
{
    private SsoEditEndpointsDisabledHealthcheck $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new SsoEditEndpointsDisabledHealthcheck();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);

        parent::tearDown();
    }

    public function testSsoEndpointsDisabledHealthcheck_Fail_WithDefaultConfig(): void
    {
        $result = $this->sut->check();

        $this->assertFalse($result->isPassed());
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_SSO, $result->domain());
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_SSO, $result->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_WARNING, $result->level());
        $this->assertStringContainsString('The endpoints for updating the SSO configurations are disabled', $result->getSuccessMessage());
        $this->assertStringContainsString('The endpoints for updating the SSO configurations are enabled', $result->getFailureMessage());
    }

    public function testSsoEndpointsDisabledHealthcheck_Pass(): void
    {
        Configure::write(SsoEndpointsSecurityMiddleware::SECURITY_CONFIG_KEY, true);
        $result = $this->sut->check();
        $this->assertTrue($result->isPassed());
    }
}
