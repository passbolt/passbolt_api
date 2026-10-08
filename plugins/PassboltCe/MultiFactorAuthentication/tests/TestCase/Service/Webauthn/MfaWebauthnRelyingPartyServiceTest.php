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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\Webauthn;

use App\Error\Exception\WebauthnRelyingPartyIdInvalidException;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;

/**
 * @covers \App\Service\Webauthn\WebauthnRelyingPartyService
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService
 */
class MfaWebauthnRelyingPartyServiceTest extends TestCase
{
    private function make(?MfaWebauthnPolicyInterface $policy = null): MfaWebauthnRelyingPartyService
    {
        return new MfaWebauthnRelyingPartyService($policy ?? new DefaultMfaWebauthnPolicyService());
    }

    public function testMfaWebauthnRelyingPartyService_RpId_DerivedFromFullBaseUrl(): void
    {
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com/subpath');
        $this->assertSame('passbolt.example.com', $this->make()->getRpId());
    }

    public function testMfaWebauthnRelyingPartyService_RpId_Missing_Throws(): void
    {
        Configure::write('App.fullBaseUrl', '');
        $this->expectException(WebauthnRelyingPartyIdInvalidException::class);
        $this->make()->getRpId();
    }

    public function testMfaWebauthnRelyingPartyService_RpName_FromMetaTitle_FallsBackToPassbolt(): void
    {
        Configure::write('passbolt.meta.title', 'ACME Vault');
        $this->assertSame('ACME Vault', $this->make()->getRpName());

        Configure::write('passbolt.meta.title', '');
        $this->assertSame('Passbolt', $this->make()->getRpName());
    }

    public function testMfaWebauthnRelyingPartyService_Validators_AreConstructible(): void
    {
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');
        $rp = $this->make();
        // Constructing validators pulls in the whole CeremonyStep pipeline — a smoke test
        // that we've wired up the right web-auth/webauthn-lib class names for 5.3.
        $this->assertNotNull($rp->getAttestationValidator());
        $this->assertNotNull($rp->getAssertionValidator());
        $this->assertNotNull($rp->getSerializer());
    }

    public function testMfaWebauthnRelyingPartyService_AttestationSupports_NonePolicy_OnlyRegistersNone(): void
    {
        $none = $this->createMock(MfaWebauthnPolicyInterface::class);
        $none->method('getAttestationConveyancePreference')
            ->willReturn(MfaWebauthnPolicyInterface::ATTESTATION_NONE);

        $asm = $this->make($none)->getAttestationStatementSupportManager();
        $this->assertTrue($asm->has('none'));
        $this->assertFalse($asm->has('packed'));
    }

    public function testMfaWebauthnRelyingPartyService_AttestationSupports_DirectPolicy_RegistersEveryFormat(): void
    {
        $direct = $this->createMock(MfaWebauthnPolicyInterface::class);
        $direct->method('getAttestationConveyancePreference')
            ->willReturn(MfaWebauthnPolicyInterface::ATTESTATION_DIRECT);

        $asm = $this->make($direct)->getAttestationStatementSupportManager();

        // All attestation formats available is supported at the moment
        $this->assertTrue($asm->has('none'));
        $this->assertTrue($asm->has('packed'));
        $this->assertTrue($asm->has('tpm'));
        $this->assertTrue($asm->has('android-key'));
        $this->assertTrue($asm->has('apple'));
        $this->assertTrue($asm->has('fido-u2f'));
    }
}
