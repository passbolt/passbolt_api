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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Service;

use App\Model\Entity\AuthenticationToken;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\UnauthorizedException;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Throwable;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialRequestOptions;

/**
 * Verifies the assertion returned by the authenticator on passkey login finish: it consumes the
 * challenge token (bound to the target user), resolves the credential used, validates the assertion
 * (signature, origin, rp id hash and signature counter), bumps the signature counter and, only then,
 * returns the server half of the passphrase kit so the client can recover the passphrase locally.
 *
 * There is no session at this point; the user id is the one the challenge token was minted for on
 * begin. A tampered user id simply fails to consume the token.
 */
class PasskeyVerifyAssertionService
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService
     */
    protected MfaWebauthnRelyingPartyService $relyingParty;

    /**
     * @var \Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService
     */
    protected PasskeyCredentialsService $credentials;

    /**
     * @var \Passbolt\PasskeyAuthentication\Service\PasskeyChallengeTokenService
     */
    protected PasskeyChallengeTokenService $challengeToken;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService|null $relyingParty Relying party service
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService|null $credentials Credentials service
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyChallengeTokenService|null $challengeToken Challenge token service
     */
    public function __construct(
        ?MfaWebauthnRelyingPartyService $relyingParty = null,
        ?PasskeyCredentialsService $credentials = null,
        ?PasskeyChallengeTokenService $challengeToken = null
    ) {
        $this->relyingParty = $relyingParty ?? new MfaWebauthnRelyingPartyService();
        $this->credentials = $credentials ?? new PasskeyCredentialsService($this->relyingParty);
        $this->challengeToken = $challengeToken ?? new PasskeyChallengeTokenService();
    }

    /**
     * Verify the assertion and return the server half of the passphrase kit.
     *
     * @param string $userId The target user id
     * @param string $token The challenge token uuid
     * @param string $credentialJson The raw JSON credential returned by the client
     * @return string The server half of the passphrase kit (base64 JWK)
     * @throws \Cake\Http\Exception\BadRequestException If the assertion response cannot be read
     * @throws \Cake\Http\Exception\UnauthorizedException If the assertion cannot be validated
     */
    public function verify(string $userId, string $token, string $credentialJson): string
    {
        $optionsJson = $this->challengeToken->consume($userId, AuthenticationToken::TYPE_PASSKEY_LOGIN, $token);
        $serializer = $this->relyingParty->getSerializer();

        /** @var \Webauthn\PublicKeyCredentialRequestOptions $options */
        $options = $serializer->deserialize($optionsJson, PublicKeyCredentialRequestOptions::class, 'json');

        try {
            /** @var \Webauthn\PublicKeyCredential $publicKeyCredential */
            $publicKeyCredential = $serializer->deserialize($credentialJson, PublicKeyCredential::class, 'json');
        } catch (Throwable $th) {
            throw new BadRequestException(__('The authentication response could not be read.'), null, $th);
        }

        $response = $publicKeyCredential->response;
        if (!$response instanceof AuthenticatorAssertionResponse) {
            throw new BadRequestException(__('The authentication response is invalid.'));
        }

        $credentialId = $this->credentials->encodeCredentialId(
            $publicKeyCredential->getPublicKeyCredentialDescriptor()->id
        );
        $entity = $this->credentials->findEntityByCredentialId($userId, $credentialId);
        if ($entity === null) {
            throw new UnauthorizedException(__('This security key is not registered.'));
        }

        try {
            $updatedRecord = $this->relyingParty->getAssertionValidator()->check(
                $this->credentials->getRecord($entity),
                $response,
                $options,
                $this->relyingParty->getHost(),
                $userId
            );
        } catch (Throwable $th) {
            throw new UnauthorizedException(__('The security key could not be validated.'), null, $th);
        }

        $serverKitKey = $this->credentials->getServerKitKey($entity);
        if ($serverKitKey === null) {
            throw new UnauthorizedException(__('This security key cannot be used for passkey login.'));
        }

        $this->credentials->updateAfterAssertion($entity, $updatedRecord);

        return $serverKitKey;
    }
}
