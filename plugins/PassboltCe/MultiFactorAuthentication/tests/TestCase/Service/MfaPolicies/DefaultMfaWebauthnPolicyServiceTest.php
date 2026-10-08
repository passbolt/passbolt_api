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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\MfaPolicies;

use App\Service\Webauthn\WebauthnPolicyInterface;
use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService
 */
class DefaultMfaWebauthnPolicyServiceTest extends TestCase
{
    public function testDefaultMfaWebauthnPolicyService_MaxCredentialsPerUser(): void
    {
        $policy = new DefaultMfaWebauthnPolicyService();

        $this->assertSame(10, $policy->getMaxCredentialsPerUser());
    }

    public function testDefaultMfaWebauthnPolicyService_InheritsNeutralDefaults(): void
    {
        $policy = new DefaultMfaWebauthnPolicyService();

        $this->assertNull($policy->getAuthenticatorAttachment());
        $this->assertFalse($policy->rejectBackupEligible());
        $this->assertSame(WebauthnPolicyInterface::RESIDENT_KEY_DISCOURAGED, $policy->getResidentKey());
        $this->assertSame(WebauthnPolicyInterface::USER_VERIFICATION_PREFERRED, $policy->getUserVerification());
        $this->assertSame([], $policy->getHints());
        $this->assertSame(60_000, $policy->getTimeoutMs());
        $this->assertSame(WebauthnPolicyInterface::ATTESTATION_NONE, $policy->getAttestationConveyancePreference());
        $this->assertSame([], $policy->getAaguidAllowlist());
        $this->assertSame([], $policy->getAaguidBlocklist());
    }
}
