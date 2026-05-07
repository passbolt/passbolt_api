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

namespace Passbolt\Sso\Test\TestCase\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use Cake\Core\Configure;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;
use Passbolt\Sso\Service\Healthcheck\SslHostVerificationSsoHealthcheck;
use Passbolt\Sso\Test\Lib\SsoTestCase;

/**
 * @covers \Passbolt\Sso\Service\Healthcheck\SslHostVerificationSsoHealthcheck
 */
class SslHostVerificationSsoHealthcheckTest extends SsoTestCase
{
    private SslHostVerificationSsoHealthcheck $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new SslHostVerificationSsoHealthcheck();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);

        parent::tearDown();
    }

    public function testSslHostVerificationSsoHealthcheck_Pass_WithDefaultConfig(): void
    {
        // By default, value of `passbolt.security.sso.sslVerify` config is true
        $result = $this->sut->check();

        $this->assertTrue($result->isPassed());
        // Make sure other metadata info are correct
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_SSO, $result->domain());
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_SSO, $result->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_WARNING, $result->level());
        $this->assertStringContainsString('SSL certification validation for SSO instance is enabled', $result->getSuccessMessage());
        $this->assertStringContainsString('SSL certification validation for SSO instance is disabled', $result->getFailureMessage());
    }

    public function testSslHostVerificationSsoHealthcheck_Fail_WhenConfigIsSetToFalse(): void
    {
        Configure::write('passbolt.security.sso.sslVerify', false);

        $result = $this->sut->check();

        $this->assertFalse($result->isPassed());
    }
}
