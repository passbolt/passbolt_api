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
use Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential;
use Throwable;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;

/**
 * Verifies the attestation returned by the authenticator on registration finish: it consumes the
 * challenge token, validates the attestation against the stored creation options, and enables the
 * provider / stores the credential.
 */
class MfaWebauthnVerifyAttestationService
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService
     */
    protected MfaWebauthnRelyingPartyService $relyingParty;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService
     */
    protected MfaWebauthnChallengeTokenService $challengeToken;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnableService
     */
    protected MfaWebauthnEnableService $enableService;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService|null $relyingParty Relying party service
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService|null $challengeToken Challenge token service
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnableService|null $enableService Enable service
     */
    public function __construct(
        ?MfaWebauthnRelyingPartyService $relyingParty = null,
        ?MfaWebauthnChallengeTokenService $challengeToken = null,
        ?MfaWebauthnEnableService $enableService = null
    ) {
        $this->relyingParty = $relyingParty ?? new MfaWebauthnRelyingPartyService();
        $this->challengeToken = $challengeToken ?? new MfaWebauthnChallengeTokenService();
        $this->enableService = $enableService ?? new MfaWebauthnEnableService(
            new MfaWebauthnCredentialsService($this->relyingParty)
        );
    }

    /**
     * Verify the attestation and register the credential.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param string $token The challenge token uuid
     * @param string $credentialJson The raw JSON credential returned by the client
     * @param string|null $name User provided friendly name
     * @return \Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential
     * @throws \Cake\Http\Exception\BadRequestException If the attestation cannot be validated
     */
    public function verify(
        UserAccessControl $uac,
        string $token,
        string $credentialJson,
        ?string $name = null
    ): MfaWebauthnCredential {
        $optionsJson = $this->challengeToken->consume($uac, AuthenticationToken::TYPE_MFA_SETUP, $token);
        $serializer = $this->relyingParty->getSerializer();

        /** @var \Webauthn\PublicKeyCredentialCreationOptions $options */
        $options = $serializer->deserialize($optionsJson, PublicKeyCredentialCreationOptions::class, 'json');

        try {
            /** @var \Webauthn\PublicKeyCredential $publicKeyCredential */
            $publicKeyCredential = $serializer->deserialize($credentialJson, PublicKeyCredential::class, 'json');
        } catch (Throwable $th) {
            throw new BadRequestException(__('The registration response could not be read.'), null, $th);
        }

        $response = $publicKeyCredential->response;
        if (!$response instanceof AuthenticatorAttestationResponse) {
            throw new BadRequestException(__('The registration response is invalid.'));
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

        return $this->enableService->enable($uac, $record, $name);
    }
}
