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

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use LogicException;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Passbolt\MultiFactorAuthentication\Error\Exception\Webauthn\WebauthnAssertionInvalidException;
use Passbolt\MultiFactorAuthentication\Error\Exception\Webauthn\WebauthnCloneDetectedException;
use Passbolt\MultiFactorAuthentication\Error\Exception\Webauthn\WebauthnCredentialNotRecognisedException;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAssertionService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\Utility\WebauthnCredentialTestUtility;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\AuthenticatorData;
use Webauthn\AuthenticatorResponse;
use Webauthn\CollectedClientData;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialRequestOptions;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAssertionService
 */
class MfaWebauthnVerifyAssertionServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnRelyingPartyService&MockObject $rp;
    private MfaWebauthnVerifyAssertionService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->rp = $this->getMockBuilder(MfaWebauthnRelyingPartyService::class)
            ->setConstructorArgs([new DefaultMfaWebauthnPolicyService()])
            ->onlyMethods(['getSerializer', 'getDenormalizer', 'getAssertionValidator'])
            ->getMock();
        $this->service = new MfaWebauthnVerifyAssertionService($this->rp);
    }

    public function tearDown(): void
    {
        unset($this->rp, $this->service);
        parent::tearDown();
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_RefusesCredentialOwnedByOtherUser(): void
    {
        $victimUac = UserFactory::make()->user()->persistedUAC();
        $attackerUac = UserFactory::make()->user()->persistedUAC();
        $rawId = random_bytes(16);
        $this->persistCredential($victimUac, $rawId);
        $handle = $this->issueVerifyHandle($attackerUac);

        $this->stubDeserializer();
        $this->stubDenormalizer($this->makePublicKeyCredential($rawId));
        $this->stubValidatorNeverRuns();

        $this->expectException(WebauthnCredentialNotRecognisedException::class);
        $this->expectExceptionMessage('WebAuthn credential is not recognised');
        $this->service->verify($attackerUac, $handle, WebauthnCredentialTestUtility::getDummyPayload());
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_RefusesUnknownCredentialId(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $handle = $this->issueVerifyHandle($uac);

        $this->stubDeserializer();
        $this->stubDenormalizer($this->makePublicKeyCredential(random_bytes(16)));
        $this->stubValidatorNeverRuns();

        $this->expectException(WebauthnCredentialNotRecognisedException::class);
        $this->expectExceptionMessage('WebAuthn credential is not recognised');
        $this->service->verify($uac, $handle, WebauthnCredentialTestUtility::getDummyPayload());
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_RefusesReplayedHandle(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $tokens = new MfaWebauthnChallengeTokenService();
        $handle = $tokens->create($uac, AuthenticationToken::TYPE_MFA_VERIFY, '{"c":"a"}')->token;
        $tokens->consume($uac, AuthenticationToken::TYPE_MFA_VERIFY, $handle);

        $this->expectException(CustomValidationException::class);
        $this->service->verify($uac, $handle, WebauthnCredentialTestUtility::getDummyPayload());
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_RefusesHandleBelongingToOtherUser(): void
    {
        $victimUac = UserFactory::make()->user()->persistedUAC();
        $attackerUac = UserFactory::make()->user()->persistedUAC();
        $handle = (new MfaWebauthnChallengeTokenService())
            ->create($victimUac, AuthenticationToken::TYPE_MFA_VERIFY, '{"c":"a"}')
            ->token;

        $this->expectException(NotFoundException::class);
        $this->service->verify($attackerUac, $handle, WebauthnCredentialTestUtility::getDummyPayload());
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_RefusesSetupHandleUsedAsVerify(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $setupHandle = (new MfaWebauthnChallengeTokenService())
            ->create($uac, AuthenticationToken::TYPE_MFA_SETUP, '{"c":"a"}')
            ->token;

        $this->expectException(NotFoundException::class);
        $this->service->verify($uac, $setupHandle, WebauthnCredentialTestUtility::getDummyPayload());
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_IncompleteCredentialShape_ThrowsWithoutTriggeringDenormalizerWarning(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $handle = $this->issueVerifyHandle($uac);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The credential payload is not a valid WebAuthn response.');
        $this->service->verify($uac, $handle, ['id' => 'anything']);
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_ThrowsBadRequestOnMalformedCredentialPayload(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $handle = $this->issueVerifyHandle($uac);

        $this->stubDeserializer();
        $denormalizer = $this->createMock(DenormalizerInterface::class);
        $denormalizer->method('denormalize')->willThrowException(new RuntimeException('malformed'));
        $this->rp->method('getDenormalizer')->willReturn($denormalizer);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('credential payload is not a valid WebAuthn response');
        $this->service->verify($uac, $handle, WebauthnCredentialTestUtility::getDummyPayload());
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_ThrowsBadRequestOnNonAssertionResponse(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $handle = $this->issueVerifyHandle($uac);

        $nonAssertion = $this->createMock(AuthenticatorResponse::class);
        $credential = PublicKeyCredential::create('public-key', random_bytes(16), $nonAssertion);
        $this->stubDeserializer();
        $this->stubDenormalizer($credential);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('credential response is not an assertion');
        $this->service->verify($uac, $handle, WebauthnCredentialTestUtility::getDummyPayload());
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_ThrowsAssertionInvalidOnValidatorFailure(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $rawId = random_bytes(16);
        $this->persistCredential($uac, $rawId);
        $handle = $this->issueVerifyHandle($uac);

        $this->stubDeserializer();
        $this->stubDenormalizer($this->makePublicKeyCredential($rawId));
        $this->rp->method('getAssertionValidator')->willThrowException(new RuntimeException('signature invalid'));

        $this->expectException(WebauthnAssertionInvalidException::class);
        $this->expectExceptionMessage('WebAuthn assertion failed to validate');
        $this->service->verify($uac, $handle, WebauthnCredentialTestUtility::getDummyPayload());
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_ThrowsCloneDetectedOnCounterViolation(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $rawId = random_bytes(16);
        $credentialId = Base64UrlSafe::encodeUnpadded($rawId);
        MfaWebauthnCredentialFactory::make()
            ->patchData(['user_id' => $uac->getId()])
            ->credentialId($credentialId)
            ->signCount(10)
            ->persist();
        $handle = $this->issueVerifyHandle($uac);

        $this->stubDeserializer();
        $this->stubDenormalizer($this->makePublicKeyCredential($rawId));
        $stallCounterRecord = MfaWebauthnCredentialFactory::makeCredentialRecord($credentialId);
        $validator = $this->createMock(AuthenticatorAssertionResponseValidator::class);
        $validator->method('check')->willReturn($stallCounterRecord);
        $this->rp->method('getAssertionValidator')->willReturn($validator);

        $this->expectException(WebauthnCloneDetectedException::class);
        $this->expectExceptionMessage('failed clone detection');
        $this->service->verify($uac, $handle, WebauthnCredentialTestUtility::getDummyPayload());
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_ThrowsBadRequestWhenDenormalizerReturnsNonCredential(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $handle = $this->issueVerifyHandle($uac);

        $this->stubDeserializer();
        $this->stubDenormalizer(['raw' => 'array']);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('credential payload is not a valid WebAuthn response');
        $this->service->verify($uac, $handle, WebauthnCredentialTestUtility::getDummyPayload());
    }

    public function testMfaWebauthnVerifyAssertionService_Verify_Success_TouchesLastUsed(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $rawId = random_bytes(16);
        $credentialId = $this->persistCredential($uac, $rawId);
        $handle = $this->issueVerifyHandle($uac);

        $this->stubDeserializer();
        $this->stubDenormalizer($this->makePublicKeyCredential($rawId));
        $validator = $this->createMock(AuthenticatorAssertionResponseValidator::class);
        $validator->method('check')->willReturn(MfaWebauthnCredentialFactory::makeCredentialRecord($credentialId));
        $this->rp->method('getAssertionValidator')->willReturn($validator);

        $this->service->verify($uac, $handle, WebauthnCredentialTestUtility::getDummyPayload());

        /** @var \App\Model\Entity\WebauthnCredential $reloaded */
        $reloaded = MfaWebauthnCredentialFactory::find()
            ->where(['user_id' => $uac->getId()])
            ->firstOrFail();
        $this->assertNotNull($reloaded->last_used);
    }

    private function persistCredential(UserAccessControl $uac, string $rawId): string
    {
        $credentialId = Base64UrlSafe::encodeUnpadded($rawId);
        MfaWebauthnCredentialFactory::make()
            ->patchData(['user_id' => $uac->getId()])
            ->credentialId($credentialId)
            ->persist();

        return $credentialId;
    }

    private function issueVerifyHandle(UserAccessControl $uac): string
    {
        return (new MfaWebauthnChallengeTokenService())
            ->create($uac, AuthenticationToken::TYPE_MFA_VERIFY, '{"challenge":"a"}')
            ->token;
    }

    private function stubDeserializer(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->method('deserialize')->willReturn(PublicKeyCredentialRequestOptions::create(random_bytes(32)));
        $this->rp->method('getSerializer')->willReturn($serializer);
    }

    private function stubDenormalizer(mixed $result): void
    {
        $denormalizer = $this->createMock(DenormalizerInterface::class);
        $denormalizer->method('denormalize')->willReturn($result);
        $this->rp->method('getDenormalizer')->willReturn($denormalizer);
    }

    private function stubValidatorNeverRuns(): void
    {
        $this->rp->method('getAssertionValidator')->willThrowException(
            new LogicException('Guard bypassed — assertion validator should not run.'),
        );
    }

    private function makePublicKeyCredential(string $rawId): PublicKeyCredential
    {
        return PublicKeyCredential::create('public-key', $rawId, $this->makeStubAssertionResponse());
    }

    private function makeStubAssertionResponse(): AuthenticatorAssertionResponse
    {
        $clientData = new CollectedClientData(
            '{}',
            [
                'type' => 'webauthn.get',
                'challenge' => 'YQ',
                'origin' => 'https://passbolt.example.com',
                'crossOrigin' => false,
            ],
        );
        $authenticatorData = AuthenticatorData::create("\x00", str_repeat("\x00", 32), "\x00", 0);

        return AuthenticatorAssertionResponse::create($clientData, $authenticatorData, "\x00", null);
    }
}
