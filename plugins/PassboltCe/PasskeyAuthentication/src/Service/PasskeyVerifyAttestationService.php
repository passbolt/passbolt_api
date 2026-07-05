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
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential;
use Throwable;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;

/**
 * Verifies the attestation returned by the authenticator on enrollment finish: it consumes the
 * challenge token, validates the attestation against the stored creation options, and stores the
 * credential together with the server half of the passphrase kit computed by the client.
 */
class PasskeyVerifyAttestationService
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService
     */
    protected MfaWebauthnRelyingPartyService $relyingParty;

    /**
     * @var \Passbolt\PasskeyAuthentication\Service\PasskeyChallengeTokenService
     */
    protected PasskeyChallengeTokenService $challengeToken;

    /**
     * @var \Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService
     */
    protected PasskeyCredentialsService $credentials;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService|null $relyingParty Relying party service
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyChallengeTokenService|null $challengeToken Challenge token service
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService|null $credentials Credentials service
     */
    public function __construct(
        ?MfaWebauthnRelyingPartyService $relyingParty = null,
        ?PasskeyChallengeTokenService $challengeToken = null,
        ?PasskeyCredentialsService $credentials = null
    ) {
        $this->relyingParty = $relyingParty ?? new MfaWebauthnRelyingPartyService();
        $this->challengeToken = $challengeToken ?? new PasskeyChallengeTokenService();
        $this->credentials = $credentials ?? new PasskeyCredentialsService($this->relyingParty);
    }

    /**
     * Verify the attestation and register the passkey credential + its kit.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param string $token The challenge token uuid
     * @param string $credentialJson The raw JSON credential returned by the client
     * @param string $serverKitKey The server half of the passphrase kit (base64 JWK)
     * @param string|null $name User provided friendly name
     * @return \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential
     * @throws \Cake\Http\Exception\BadRequestException If the attestation cannot be read or validated
     */
    public function verify(
        UserAccessControl $uac,
        string $token,
        string $credentialJson,
        string $serverKitKey,
        ?string $name = null
    ): PasskeyCredential {
        if ($serverKitKey === '') {
            throw new BadRequestException(__('The passphrase kit is missing.'));
        }

        $optionsJson = $this->challengeToken->consume($uac->getId(), AuthenticationToken::TYPE_PASSKEY_SETUP, $token);
        $serializer = $this->relyingParty->getSerializer();

        /** @var \Webauthn\PublicKeyCredentialCreationOptions $options */
        $options = $serializer->deserialize($optionsJson, PublicKeyCredentialCreationOptions::class, 'json');

        try {
            /** @var \Webauthn\PublicKeyCredential $publicKeyCredential */
            $publicKeyCredential = $serializer->deserialize($credentialJson, PublicKeyCredential::class, 'json');
        } catch (Throwable $th) {
            throw new BadRequestException(__('The enrollment response could not be read.'), null, $th);
        }

        $response = $publicKeyCredential->response;
        if (!$response instanceof AuthenticatorAttestationResponse) {
            throw new BadRequestException(__('The enrollment response is invalid.'));
        }

        try {
            $record = $this->relyingParty->getAttestationValidator()->check(
                $response,
                $options,
                $this->relyingParty->getHost()
            );
        } catch (Throwable $th) {
            throw new BadRequestException(__('The security key could not be validated.'), null, $th);
        }

        return $this->credentials->create($uac->getId(), $record, $serverKitKey, $name);
    }
}
