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
 * @since         5.18.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Passbolt\MultiFactorAuthentication\MultiFactorAuthenticationPlugin;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoVerifyDuoCodeService;
use Passbolt\MultiFactorAuthentication\Service\Healthcheck\DuoVerifySubscriberMfaHealthcheck;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(DuoVerifySubscriberMfaHealthcheck::class)]
class DuoVerifySubscriberMfaHealthcheckTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;

    private DuoVerifySubscriberMfaHealthcheck $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new DuoVerifySubscriberMfaHealthcheck();
    }

    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testDuoVerifySubscriberMfaHealthcheck_Definition(): void
    {
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_MFA, $this->sut->domain());
        $this->assertSame(HealthcheckServiceCollector::DOMAIN_MFA, $this->sut->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_WARNING, $this->sut->level());
        $this->assertSame('duoVerifySubscriberEnabled', $this->sut->getLegacyArrayKey());
    }

    public function testDuoVerifySubscriberMfaHealthcheck_Success_DuoNotEnabled(): void
    {
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, false);
        MfaOrganizationSettingFactory::make()->totp()->persist();

        $result = $this->sut->check();

        $this->assertTrue($result->isPassed());
        $this->assertSame(
            'Duo is not enabled, the Duo subscriber verification does not apply.',
            $result->getSuccessMessage(),
        );
    }

    public function testDuoVerifySubscriberMfaHealthcheck_Success_OrgSettingsUnreadable(): void
    {
        // Settings without the "providers" key cannot be read by MfaOrgSettings
        MfaOrganizationSettingFactory::make()->value(['duo' => []])->persist();

        $result = $this->sut->check();

        $this->assertTrue($result->isPassed());
        $this->assertSame(
            'Duo is not enabled, the Duo subscriber verification does not apply.',
            $result->getSuccessMessage(),
        );
    }

    public function testDuoVerifySubscriberMfaHealthcheck_Success_DuoEnabledWithVerification(): void
    {
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, false);
        MfaOrganizationSettingFactory::make()->duo(verifySubscriber: true)->persist();

        $result = $this->sut->check();

        $this->assertTrue($result->isPassed());
        $this->assertSame('Duo subscriber verification is enabled.', $result->getSuccessMessage());
    }

    public function testDuoVerifySubscriberMfaHealthcheck_Error_DuoEnabledWithoutVerification(): void
    {
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, false);
        MfaOrganizationSettingFactory::make()->duo(verifySubscriber: false)->persist();

        $result = $this->sut->check();

        $this->assertFalse($result->isPassed());
        $this->assertSame(
            'Duo is enabled but the Duo subscriber verification is disabled.',
            $result->getFailureMessage(),
        );
    }

    /**
     * Stored verifySubscriber (null = not stored), legacy config flag, expected result.
     */
    public static function duoEnabledProvider(): array
    {
        return [
            'stored true' => [true, false, true],
            'stored false, legacy true' => [false, true, false],
            'not stored, legacy true' => [null, true, true],
            'not stored, legacy false' => [null, false, false],
        ];
    }

    #[DataProvider('duoEnabledProvider')]
    public function testDuoVerifySubscriberMfaHealthcheck_DuoEnabled(
        ?bool $storedValue,
        bool $legacyValue,
        bool $expectedPassed,
    ): void {
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, $legacyValue);
        MfaOrganizationSettingFactory::make()->duo(verifySubscriber: $storedValue)->persist();

        $this->assertSame($expectedPassed, $this->sut->check()->isPassed());
    }

    public function testDuoVerifySubscriberMfaHealthcheck_CliOptionIsListed(): void
    {
        $this->enableFeaturePlugin(MultiFactorAuthenticationPlugin::class);

        $this->exec('passbolt healthcheck -h');

        $this->assertExitSuccess();
        $this->assertOutputContains('--' . HealthcheckServiceCollector::DOMAIN_MFA);
        $this->assertOutputContains('Run Multi-factor authentication tests only.');
    }
}
