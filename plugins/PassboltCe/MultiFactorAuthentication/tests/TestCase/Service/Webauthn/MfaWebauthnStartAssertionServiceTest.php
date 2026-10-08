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
use ParagonIE\ConstantTime\Base64UrlSafe;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartAssertionService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartAssertionService
 */
class MfaWebauthnStartAssertionServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnStartAssertionService $service;

    public function setUp(): void
    {
        parent::setUp();
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');
        $policy = new DefaultMfaWebauthnPolicyService();
        $this->service = new MfaWebauthnStartAssertionService(new MfaWebauthnRelyingPartyService($policy), $policy);
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMfaWebauthnStartAssertionService_Start_ReturnsHandleAndRequestOptions(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $result = $this->service->start($uac);

        $this->assertArrayHasKey('handle', $result);
        $this->assertIsString($result['handle']);
        $this->assertArrayHasKey('credentialRequestOptions', $result);
        $options = $result['credentialRequestOptions'];
        $this->assertIsArray($options);
        $this->assertArrayHasKey('challenge', $options);
        $this->assertSame('passbolt.example.com', $options['rpId']);
        $this->assertArrayHasKey('hints', $options);
        $this->assertIsArray($options['hints']);
    }

    public function testMfaWebauthnStartAssertionService_Start_PersistsVerifyToken(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $result = $this->service->start($uac);

        $token = AuthenticationTokenFactory::find()
            ->where(['token' => $result['handle']])
            ->firstOrFail();
        $this->assertSame(AuthenticationToken::TYPE_MFA_VERIFY, $token->get('type'));
        $this->assertSame($uac->getId(), $token->get('user_id'));
        $this->assertTrue($token->get('active'));
        $data = json_decode($token->get('data'), true);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('options', $data);
        $this->assertJson($data['options']);
    }

    public function testMfaWebauthnStartAssertionService_Start_RestrictsAllowCredentialsToCaller(): void
    {
        /** @var \App\Model\Entity\User $callerUser */
        $callerUser = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\User $otherUser */
        $otherUser = UserFactory::make()->user()->persist();
        MfaWebauthnCredentialFactory::make()->withUser($callerUser)->credentialId('caller-cred')->persist();
        MfaWebauthnCredentialFactory::make()->withUser($otherUser)->credentialId('other-cred')->persist();

        $result = $this->service->start(new UserAccessControl(Role::USER, $callerUser->id, $callerUser->username));

        $allow = $result['credentialRequestOptions']['allowCredentials'] ?? [];
        $ids = array_column($allow, 'id');
        $this->assertContains(Base64UrlSafe::encodeUnpadded('caller-cred'), $ids);
        $this->assertNotContains(Base64UrlSafe::encodeUnpadded('other-cred'), $ids);
    }

    public function testMfaWebauthnStartAssertionService_Start_UnderDefaultPolicy_UsesPreferredUvAndSixtySecondTimeout(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $result = $this->service->start($uac);

        $options = $result['credentialRequestOptions'];
        $this->assertSame('preferred', $options['userVerification']);
        $this->assertSame(60_000, $options['timeout']);
        $this->assertSame([], $options['hints']);
    }

    public function testMfaWebauthnStartAssertionService_Start_ProducesUniqueChallengePerCall(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $first = $this->service->start($uac);
        $second = $this->service->start($uac);

        $this->assertNotSame(
            $first['credentialRequestOptions']['challenge'],
            $second['credentialRequestOptions']['challenge'],
        );
        $this->assertNotSame($first['handle'], $second['handle']);
    }

    public function testMfaWebauthnStartAssertionService_Start_ChallengeIs32Bytes(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $result = $this->service->start($uac);
        $raw = Base64UrlSafe::decodeNoPadding($result['credentialRequestOptions']['challenge']);

        $this->assertSame(32, strlen($raw));
    }

    public function testMfaWebauthnStartAssertionService_Start_PersistedOptionsMatchReturnedChallenge(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $result = $this->service->start($uac);
        $token = AuthenticationTokenFactory::find()
            ->where(['token' => $result['handle']])
            ->firstOrFail();
        $persistedOptions = json_decode(json_decode($token->get('data'), true)['options'], true);

        $this->assertSame(
            $result['credentialRequestOptions']['challenge'],
            $persistedOptions['challenge'],
        );
    }

    public function testMfaWebauthnStartAssertionService_Start_UserWithNoCredentialsReturnsEmptyAllowCredentials(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $result = $this->service->start($uac);

        $this->assertSame([], $result['credentialRequestOptions']['allowCredentials'] ?? []);
    }

    public function testMfaWebauthnStartAssertionService_Start_PersistedAllowCredentialsMatchReturnedAllowCredentials(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('caller-cred')->persist();

        $result = $this->service->start(new UserAccessControl(Role::USER, $user->id, $user->username));
        $token = AuthenticationTokenFactory::find()
            ->where(['token' => $result['handle']])
            ->firstOrFail();
        $persistedOptions = json_decode(json_decode($token->get('data'), true)['options'], true);

        $this->assertSame(
            $result['credentialRequestOptions']['allowCredentials'],
            $persistedOptions['allowCredentials'],
        );
    }

    public function testMfaWebauthnStartAssertionService_Start_AllowCredentialsDescriptorDoesNotLeakCredentialRecord(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('caller-cred')->persist();

        $result = $this->service->start(new UserAccessControl(Role::USER, $user->id, $user->username));

        $allow = $result['credentialRequestOptions']['allowCredentials'];
        $this->assertNotEmpty($allow);
        foreach ($allow as $descriptor) {
            $this->assertArrayHasKey('id', $descriptor);
            $this->assertSame('public-key', $descriptor['type']);
            $this->assertArrayNotHasKey('data', $descriptor);
            $this->assertArrayNotHasKey('publicKey', $descriptor);
            $this->assertArrayNotHasKey('signCount', $descriptor);
        }
    }

    public function testMfaWebauthnStartAssertionService_Start_HandleAndChallengeAreIndependentBytes(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $result = $this->service->start($uac);

        $this->assertStringNotContainsString(
            $result['handle'],
            $result['credentialRequestOptions']['challenge'],
        );
        $this->assertStringNotContainsString(
            $result['credentialRequestOptions']['challenge'],
            $result['handle'],
        );
    }
}
