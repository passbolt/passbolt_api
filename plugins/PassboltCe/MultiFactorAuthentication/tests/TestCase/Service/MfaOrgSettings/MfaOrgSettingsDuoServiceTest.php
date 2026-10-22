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

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\MfaOrgSettings;

use App\Error\Exception\CustomValidationException;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoVerifyDuoCodeService;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaOrgSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(MfaOrgSettingsDuoService::class)]
class MfaOrgSettingsDuoServiceTest extends TestCase
{
    use MfaOrgSettingsTestTrait;

    /**
     * Stored value (null = not stored), legacy config flag (null = not set), expected result.
     */
    public static function verifySubscriberProvider(): array
    {
        return [
            'stored true, legacy true' => [true, true, true],
            'stored true, legacy false' => [true, false, true],
            'stored true, legacy not set' => [true, null, true],
            'stored false, legacy true' => [false, true, false],
            'stored false, legacy false' => [false, false, false],
            'stored false, legacy not set' => [false, null, false],
            'not stored, legacy true' => [null, true, true],
            'not stored, legacy false' => [null, false, false],
            'not stored, legacy not set' => [null, null, false],
            'not stored, legacy string true' => [null, 'true', false],
        ];
    }

    #[DataProvider('verifySubscriberProvider')]
    public function testMfaOrgSettingsDuoService_IsVerifySubscriberEnabled(
        ?bool $storedValue,
        bool|string|null $legacyValue,
        bool $expected,
    ): void {
        $settings = $this->getDefaultMfaOrgSettings();
        if ($storedValue !== null) {
            $settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_VERIFY_SUBSCRIBER] = $storedValue;
        }
        // Written in all cases: a local config/passbolt.php may set the legacy flag
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, $legacyValue);

        $service = new MfaOrgSettingsDuoService($settings);

        $this->assertSame($expected, $service->isVerifySubscriberEnabled());
    }

    public function testMfaOrgSettingsDuoService_IsVerifySubscriberEnabled_NoDuoSettings(): void
    {
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, true);

        $service = new MfaOrgSettingsDuoService([MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP]]);

        $this->assertTrue($service->isVerifySubscriberEnabled());
    }

    /**
     * Received value, expected stored value.
     */
    public static function validVerifySubscriberProvider(): array
    {
        return [
            'null' => [null, true],
            'true' => [true, true],
            'int 1' => [1, true],
            'string 1' => ['1', true],
            'false' => [false, false],
            'int 0' => [0, false],
            'string 0' => ['0', false],
        ];
    }

    #[DataProvider('validVerifySubscriberProvider')]
    public function testMfaOrgSettingsDuoService_ApplyVerifySubscriberDefault_Success_CastsToBoolean(
        bool|int|string|null $receivedValue,
        bool $expectedValue,
    ): void {
        $data = $this->getDefaultMfaOrgSettings();
        $data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_VERIFY_SUBSCRIBER] = $receivedValue;

        $result = MfaOrgSettingsDuoService::applyVerifySubscriberDefault($data);

        $this->assertSame($expectedValue, $result[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_VERIFY_SUBSCRIBER]);
    }

    public function testMfaOrgSettingsDuoService_ApplyVerifySubscriberDefault_Success_MissingValueIsTrue(): void
    {
        $data = $this->getDefaultMfaOrgSettings();

        $result = MfaOrgSettingsDuoService::applyVerifySubscriberDefault($data);

        $this->assertTrue($result[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_VERIFY_SUBSCRIBER]);
    }

    public static function invalidVerifySubscriberProvider(): array
    {
        return [
            'string true' => ['true'],
            'string yes' => ['yes'],
            'int 2' => [2],
            'array' => [[]],
        ];
    }

    #[DataProvider('invalidVerifySubscriberProvider')]
    public function testMfaOrgSettingsDuoService_ApplyVerifySubscriberDefault_InvalidValueIsLeftUntouched(
        array|int|string $receivedValue,
    ): void {
        $data = $this->getDefaultMfaOrgSettings();
        $data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_VERIFY_SUBSCRIBER] = $receivedValue;

        $result = MfaOrgSettingsDuoService::applyVerifySubscriberDefault($data);

        $this->assertSame($data, $result);
    }

    public function testMfaOrgSettingsDuoService_ApplyVerifySubscriberDefault_NoDuoSettings(): void
    {
        $data = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP => true]];

        $this->assertSame($data, MfaOrgSettingsDuoService::applyVerifySubscriberDefault($data));
    }

    public function testMfaOrgSettingsDuoService_ApplyVerifySubscriberDefault_DuoSettingsNotAnArray(): void
    {
        $data = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => true], MfaSettings::PROVIDER_DUO => 'abc'];

        $this->assertSame($data, MfaOrgSettingsDuoService::applyVerifySubscriberDefault($data));
    }

    public function testMfaOrgSettingsDuoService_ValidateDuoSettings_Success_WithVerifySubscriber(): void
    {
        $settings = $this->getDefaultMfaOrgSettings();
        $settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_VERIFY_SUBSCRIBER] = false;

        (new MfaOrgSettingsDuoService($settings))->validateDuoSettings(null, false);

        $this->expectNotToPerformAssertions();
    }

    #[DataProvider('invalidVerifySubscriberProvider')]
    public function testMfaOrgSettingsDuoService_ValidateDuoSettings_Error_VerifySubscriberNotBoolean(
        array|int|string $receivedValue,
    ): void {
        $settings = $this->getDefaultMfaOrgSettings();
        $settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_VERIFY_SUBSCRIBER] = $receivedValue;

        try {
            (new MfaOrgSettingsDuoService($settings))->validateDuoSettings(null, false);
            $this->fail('A CustomValidationException was expected.');
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('boolean', $errors['duo']['verifySubscriber']);
        }
    }
}
