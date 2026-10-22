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

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnrollCredentialService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAttestationService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAttestationService
 */
class MfaWebauthnVerifyAttestationServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnVerifyAttestationService $service;

    public function setUp(): void
    {
        parent::setUp();
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');
        $policy = new DefaultMfaWebauthnPolicyService();
        $this->service = new MfaWebauthnVerifyAttestationService(
            new MfaWebauthnRelyingPartyService($policy),
            new MfaWebauthnEnrollCredentialService($policy),
        );
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMfaWebauthnVerifyAttestationService_Verify_UnknownHandle_Throws(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $this->expectException(NotFoundException::class);
        $this->service->verify($uac, UuidFactory::uuid(), ['not' => 'a real credential'], 'Yubikey');
    }

    public function testMfaWebauthnVerifyAttestationService_Verify_MalformedCredentialPayload_Throws(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $token = (new MfaWebauthnChallengeTokenService())->create(
            $uac,
            AuthenticationToken::TYPE_MFA_SETUP,
            '{"challenge":"abc"}',
        );

        $this->expectException(BadRequestException::class);
        $this->service->verify($uac, $token->token, ['missing' => 'required fields'], 'Yubikey');
    }

    public function testMfaWebauthnVerifyAttestationService_Verify_IncompleteCredentialShape_ThrowsWithoutTriggeringDenormalizerWarning(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $token = (new MfaWebauthnChallengeTokenService())->create(
            $uac,
            AuthenticationToken::TYPE_MFA_SETUP,
            '{"challenge":"abc"}',
        );

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The credential payload is not a valid WebAuthn response.');
        $this->service->verify($uac, $token->token, ['id' => 'anything'], 'Fresh name');
    }

    public function testMfaWebauthnVerifyAttestationService_Verify_DuplicateName_RejectsBeforeConsumingHandle(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        MfaWebauthnCredentialFactory::make()
            ->patchData(['user_id' => $uac->getId()])
            ->name('Yubikey 5C')
            ->persist();
        $tokens = new MfaWebauthnChallengeTokenService();
        $token = $tokens->create($uac, AuthenticationToken::TYPE_MFA_SETUP, '{"challenge":"abc"}');

        try {
            $this->service->verify($uac, $token->token, ['id' => 'anything'], 'Yubikey 5C');
            $this->fail('Expected BadRequestException on duplicate name.');
        } catch (BadRequestException $e) {
            $this->assertSame('A credential with this name already exists for this user.', $e->getMessage());
        }

        $this->assertSame(
            '{"challenge":"abc"}',
            $tokens->consume($uac, AuthenticationToken::TYPE_MFA_SETUP, $token->token),
        );
    }
}
