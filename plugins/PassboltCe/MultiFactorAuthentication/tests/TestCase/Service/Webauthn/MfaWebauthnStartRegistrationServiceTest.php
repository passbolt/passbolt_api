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
use App\Model\Entity\Role;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Cose\Algorithms;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartRegistrationService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartRegistrationService
 */
class MfaWebauthnStartRegistrationServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnStartRegistrationService $service;

    public function setUp(): void
    {
        parent::setUp();
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');
        $policy = new DefaultMfaWebauthnPolicyService();
        $this->service = new MfaWebauthnStartRegistrationService(new MfaWebauthnRelyingPartyService($policy), $policy);
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMfaWebauthnStartRegistrationService_Start_ReturnsHandleAndCreationOptions(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $result = $this->service->start($uac);

        $this->assertArrayHasKey('handle', $result);
        $this->assertIsString($result['handle']);
        $this->assertArrayHasKey('credentialCreationOptions', $result);
        $options = $result['credentialCreationOptions'];
        $this->assertIsArray($options);
        $this->assertArrayHasKey('challenge', $options);
        $this->assertArrayHasKey('rp', $options);
        $this->assertSame('passbolt.example.com', $options['rp']['id']);
        $this->assertArrayHasKey('user', $options);
        $this->assertSame($uac->getUsername(), $options['user']['name']);
        $this->assertArrayHasKey('hints', $options);
        $this->assertIsArray($options['hints']);
    }

    public function testMfaWebauthnStartRegistrationService_Start_AdvertisesEs256AndRs256(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $result = $this->service->start($uac);

        $algs = array_column($result['credentialCreationOptions']['pubKeyCredParams'], 'alg');
        $this->assertContains(Algorithms::COSE_ALGORITHM_ES256, $algs);
        $this->assertContains(Algorithms::COSE_ALGORITHM_RS256, $algs);
    }

    public function testMfaWebauthnStartRegistrationService_Start_PersistsSetupToken(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $result = $this->service->start($uac);

        $token = AuthenticationTokenFactory::find()
            ->where(['token' => $result['handle']])
            ->firstOrFail();
        $this->assertSame(AuthenticationToken::TYPE_MFA_SETUP, $token->get('type'));
        $this->assertSame($uac->getId(), $token->get('user_id'));
        $this->assertTrue($token->get('active'));
        $data = json_decode($token->get('data'), true);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('options', $data);
        $this->assertJson($data['options']);
    }

    public function testMfaWebauthnStartRegistrationService_Start_ExcludesAlreadyRegisteredCredentials(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('already-registered')->persist();

        $result = $this->service->start(new UserAccessControl(Role::USER, $user->id, $user->username));

        $exclude = $result['credentialCreationOptions']['excludeCredentials'] ?? [];
        $this->assertContains(
            Base64UrlSafe::encodeUnpadded('already-registered'),
            array_column($exclude, 'id'),
        );
    }

    public function testMfaWebauthnStartRegistrationService_Start_PropagatesPolicyKnobs(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $policy = $this->createMock(MfaWebauthnPolicyInterface::class);
        $policy->method('getAuthenticatorAttachment')
            ->willReturn(MfaWebauthnPolicyInterface::ATTACHMENT_CROSS_PLATFORM);
        $policy->method('getUserVerification')
            ->willReturn(MfaWebauthnPolicyInterface::USER_VERIFICATION_REQUIRED);
        $policy->method('getResidentKey')
            ->willReturn(MfaWebauthnPolicyInterface::RESIDENT_KEY_REQUIRED);
        $policy->method('getAttestationConveyancePreference')
            ->willReturn(MfaWebauthnPolicyInterface::ATTESTATION_DIRECT);
        $policy->method('getTimeoutMs')->willReturn(45_000);
        $policy->method('getHints')
            ->willReturn([MfaWebauthnPolicyInterface::HINT_SECURITY_KEY]);
        $this->service = new MfaWebauthnStartRegistrationService(new MfaWebauthnRelyingPartyService($policy), $policy);

        $result = $this->service->start($uac);

        $options = $result['credentialCreationOptions'];
        $this->assertSame('cross-platform', $options['authenticatorSelection']['authenticatorAttachment']);
        $this->assertSame('required', $options['authenticatorSelection']['userVerification']);
        $this->assertSame('required', $options['authenticatorSelection']['residentKey']);
        $this->assertSame('direct', $options['attestation']);
        $this->assertSame(45_000, $options['timeout']);
        $this->assertSame(['security-key'], $options['hints']);
    }
}
