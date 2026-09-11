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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Model\Dto;

use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Model\Dto\MfaWebauthnSettingsDto;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Model\Dto\MfaWebauthnSettingsDto
 */
class MfaWebauthnSettingsDtoTest extends TestCase
{
    public function testMfaWebauthnSettingsDto_FromPolicy_MapsEveryFieldWithMatchingJsonKey(): void
    {
        $policy = $this->createMock(MfaWebauthnPolicyInterface::class);
        $policy->method('getAuthenticatorAttachment')->willReturn(MfaWebauthnPolicyInterface::ATTACHMENT_CROSS_PLATFORM);
        $policy->method('rejectBackupEligible')->willReturn(true);
        $policy->method('getResidentKey')->willReturn(MfaWebauthnPolicyInterface::RESIDENT_KEY_REQUIRED);
        $policy->method('getUserVerification')->willReturn(MfaWebauthnPolicyInterface::USER_VERIFICATION_REQUIRED);
        $policy->method('getHints')->willReturn([MfaWebauthnPolicyInterface::HINT_SECURITY_KEY]);
        $policy->method('getTimeoutMs')->willReturn(45_000);
        $policy->method('getMaxCredentialsPerUser')->willReturn(25);
        $policy->method('getAttestationConveyancePreference')->willReturn(MfaWebauthnPolicyInterface::ATTESTATION_DIRECT);
        $policy->method('getAaguidAllowlist')->willReturn(['cb69481e-8ff7-4039-93ec-0a2729a154a8']);
        $policy->method('getAaguidBlocklist')->willReturn(['bada5566-a7aa-401f-bd96-45619a55120d']);

        $expected = [
            'authenticator_attachment' => MfaWebauthnPolicyInterface::ATTACHMENT_CROSS_PLATFORM,
            'reject_backup_eligible' => true,
            'resident_key' => MfaWebauthnPolicyInterface::RESIDENT_KEY_REQUIRED,
            'user_verification' => MfaWebauthnPolicyInterface::USER_VERIFICATION_REQUIRED,
            'hints' => [MfaWebauthnPolicyInterface::HINT_SECURITY_KEY],
            'timeout_ms' => 45_000,
            'max_credentials_per_user' => 25,
            'attestation' => MfaWebauthnPolicyInterface::ATTESTATION_DIRECT,
            'aaguid_allowlist' => ['cb69481e-8ff7-4039-93ec-0a2729a154a8'],
            'aaguid_blocklist' => ['bada5566-a7aa-401f-bd96-45619a55120d'],
        ];

        $this->assertSame($expected, MfaWebauthnSettingsDto::fromPolicy($policy)->toArray());
    }

    public function testMfaWebauthnSettingsDto_FromPolicy_PreservesNullableAttachment(): void
    {
        $policy = $this->createMock(MfaWebauthnPolicyInterface::class);
        $policy->method('getAuthenticatorAttachment')->willReturn(null);
        $policy->method('getResidentKey')->willReturn(MfaWebauthnPolicyInterface::RESIDENT_KEY_DISCOURAGED);
        $policy->method('getUserVerification')->willReturn(MfaWebauthnPolicyInterface::USER_VERIFICATION_PREFERRED);
        $policy->method('getAttestationConveyancePreference')->willReturn(MfaWebauthnPolicyInterface::ATTESTATION_NONE);

        $body = MfaWebauthnSettingsDto::fromPolicy($policy)->toArray();
        $this->assertNull($body['authenticator_attachment']);
    }
}
