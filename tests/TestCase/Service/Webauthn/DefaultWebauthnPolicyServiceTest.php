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
namespace App\Test\TestCase\Service\Webauthn;

use App\Service\Webauthn\DefaultWebauthnPolicyService;
use App\Service\Webauthn\WebauthnPolicyInterface;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Service\Webauthn\DefaultWebauthnPolicyService
 */
class DefaultWebauthnPolicyServiceTest extends TestCase
{
    public function testDefaultWebauthnPolicyService_Values(): void
    {
        $policy = new DefaultWebauthnPolicyService();

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
