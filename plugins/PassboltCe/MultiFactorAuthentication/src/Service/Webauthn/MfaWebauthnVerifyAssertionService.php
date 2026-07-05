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
namespace Passbolt\MultiFactorAuthentication\Service\Webauthn;

use App\Model\Entity\AuthenticationToken;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\UnauthorizedException;
use Throwable;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialRequestOptions;

/**
 * Verifies the assertion returned by the authenticator on verification finish: it consumes the
 * challenge token, resolves the credential used, validates the assertion (signature, origin, rp id
 * hash and signature counter), and persists the updated signature counter.
 */
class MfaWebauthnVerifyAssertionService
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService
     */
    protected MfaWebauthnRelyingPartyService $relyingParty;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnCredentialsService
     */
    protected MfaWebauthnCredentialsService $credentials;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService
     */
    protected MfaWebauthnChallengeTokenService $challengeToken;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService|null $relyingParty Relying party service
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnCredentialsService|null $credentials Credentials service
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService|null $challengeToken Challenge token service
     */
    public function __construct(
        ?MfaWebauthnRelyingPartyService $relyingParty = null,
        ?MfaWebauthnCredentialsService $credentials = null,
        ?MfaWebauthnChallengeTokenService $challengeToken = null
    ) {
        $this->relyingParty = $relyingParty ?? new MfaWebauthnRelyingPartyService();
        $this->credentials = $credentials ?? new MfaWebauthnCredentialsService($this->relyingParty);
        $this->challengeToken = $challengeToken ?? new MfaWebauthnChallengeTokenService();
    }

    /**
     * Verify the assertion.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param string $token The challenge token uuid
     * @param string $credentialJson The raw JSON credential returned by the client
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If the assertion response cannot be read
     * @throws \Cake\Http\Exception\UnauthorizedException If the assertion cannot be validated
     */
    public function verify(UserAccessControl $uac, string $token, string $credentialJson): void
    {
        $optionsJson = $this->challengeToken->consume($uac, AuthenticationToken::TYPE_MFA_VERIFY, $token);
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
        $entity = $this->credentials->findEntityByCredentialId($uac->getId(), $credentialId);
        if ($entity === null) {
            throw new UnauthorizedException(__('This security key is not registered.'));
        }

        try {
            $updatedRecord = $this->relyingParty->getAssertionValidator()->check(
                $this->credentials->getRecord($entity),
                $response,
                $options,
                $this->relyingParty->getHost(),
                $uac->getId()
            );
        } catch (Throwable $th) {
            throw new UnauthorizedException(__('The security key could not be validated.'), null, $th);
        }

        $this->credentials->updateAfterAssertion($entity, $updatedRecord);
    }
}
