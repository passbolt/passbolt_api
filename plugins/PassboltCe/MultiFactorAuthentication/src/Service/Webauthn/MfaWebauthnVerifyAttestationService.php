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
namespace Passbolt\MultiFactorAuthentication\Service\Webauthn;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\WebauthnCredential;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Throwable;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;

readonly class MfaWebauthnVerifyAttestationService
{
    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService $rp Relying party service.
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnrollCredentialService $enrollService Credential enroll service.
     */
    public function __construct(
        private MfaWebauthnRelyingPartyService $rp,
        private MfaWebauthnEnrollCredentialService $enrollService,
    ) {
    }

    /**
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @param string $handle Ceremony handle.
     * @param array $credential Browser's PublicKeyCredential as a decoded array.
     * @param string $name Friendly credential name.
     * @return \App\Model\Entity\WebauthnCredential
     */
    public function verify(
        UserAccessControl $uac,
        string $handle,
        array $credential,
        string $name,
    ): WebauthnCredential {
        $this->enrollService->assertNameAvailable($uac, $name);

        $optionsJson = (new MfaWebauthnChallengeTokenService())->consume(
            $uac,
            AuthenticationToken::TYPE_MFA_SETUP,
            $handle,
        );

        try {
            /** @var \Webauthn\PublicKeyCredentialCreationOptions $options */
            $options = $this->rp->getSerializer()
                ->deserialize($optionsJson, PublicKeyCredentialCreationOptions::class, 'json');
            $credential = $this->rp->getDenormalizer()
                ->denormalize($credential, PublicKeyCredential::class, 'json');
        } catch (Throwable) {
            throw new BadRequestException(__('The credential payload is not a valid WebAuthn response.'));
        }

        // PublicKeyCredentialDenormalizer silently returns the raw array on missing `id`.
        if (!$credential instanceof PublicKeyCredential) {
            throw new BadRequestException(__('The credential payload is not a valid WebAuthn response.'));
        }
        if (!$credential->response instanceof AuthenticatorAttestationResponse) {
            throw new BadRequestException(__('The credential response is not an attestation.'));
        }

        try {
            $source = $this->rp->getAttestationValidator()->check(
                $credential->response,
                $options,
                $this->rp->getRpId(),
            );
        } catch (Throwable) {
            throw new BadRequestException(__('The WebAuthn attestation failed to validate.'));
        }

        return $this->enrollService->enroll($uac, $source, $name);
    }
}
