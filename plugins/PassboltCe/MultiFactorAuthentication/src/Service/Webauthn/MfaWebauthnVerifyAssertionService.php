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
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Passbolt\MultiFactorAuthentication\Error\Exception\Webauthn\WebauthnAssertionInvalidException;
use Passbolt\MultiFactorAuthentication\Error\Exception\Webauthn\WebauthnCloneDetectedException;
use Passbolt\MultiFactorAuthentication\Error\Exception\Webauthn\WebauthnCredentialNotRecognisedException;
use Throwable;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialRequestOptions;

class MfaWebauthnVerifyAssertionService
{
    use LocatorAwareTrait;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService $rp RP service.
     */
    public function __construct(
        private readonly MfaWebauthnRelyingPartyService $rp,
    ) {
    }

    /**
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param string $handle Ceremony handle.
     * @param array $credential Browser PublicKeyCredential decoded from JSON.
     * @return void
     */
    public function verify(
        UserAccessControl $uac,
        string $handle,
        array $credential,
    ): void {
        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $credentials */
        $credentials = $this->fetchTable('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');

        $optionsJson = (new MfaWebauthnChallengeTokenService())->consume(
            $uac,
            AuthenticationToken::TYPE_MFA_VERIFY,
            $handle,
        );

        try {
            /** @var \Webauthn\PublicKeyCredentialRequestOptions $options */
            $options = $this->rp->getSerializer()
                ->deserialize($optionsJson, PublicKeyCredentialRequestOptions::class, 'json');
            $credential = $this->rp->getDenormalizer()
                ->denormalize($credential, PublicKeyCredential::class, 'json');
        } catch (Throwable) {
            throw new BadRequestException(__('The credential payload is not a valid WebAuthn response.'));
        }

        if (!$credential instanceof PublicKeyCredential) {
            throw new BadRequestException(__('The credential payload is not a valid WebAuthn response.'));
        }
        if (!$credential->response instanceof AuthenticatorAssertionResponse) {
            throw new BadRequestException(__('The credential response is not an assertion.'));
        }

        $credentialId = Base64UrlSafe::encodeUnpadded($credential->rawId);
        /** @var \App\Model\Entity\WebauthnCredential|null $stored */
        $stored = $credentials->findOneByCredentialId($credentialId);
        if ($stored === null || $stored->user_id !== $uac->getId()) {
            throw new WebauthnCredentialNotRecognisedException(__('The WebAuthn credential is not recognised.'));
        }

        if (!$this->isCredentialsAllowed($credential->rawId, $options)) {
            throw new WebauthnCredentialNotRecognisedException(
                __('The WebAuthn credential is not allowed for this ceremony.'),
            );
        }

        try {
            $updated = $this->rp->getAssertionValidator()->check(
                $stored->data,
                $credential->response,
                $options,
                $this->rp->getRpId(),
                $uac->getId(),
            );
        } catch (Throwable $e) {
            $message = 'WebAuthn assertion validation failed.';
            if (Configure::read('debug')) {
                $message .= ' ' . $e->getMessage();
            }
            Log::error($message);

            throw new WebauthnAssertionInvalidException(__('The WebAuthn assertion failed to validate.'), null, $e);
        }

        if (!$credentials->updateAfterAssertion($stored, $updated)) {
            throw new WebauthnCloneDetectedException(__('The WebAuthn assertion failed clone detection.'));
        }
    }

    /**
     * @param string $rawId Raw credential id presented by the authenticator.
     * @param \Webauthn\PublicKeyCredentialRequestOptions $options Options issued for this ceremony.
     * @return bool
     */
    private function isCredentialsAllowed(string $rawId, PublicKeyCredentialRequestOptions $options): bool
    {
        if (empty($options->allowCredentials)) {
            return true;
        }

        foreach ($options->allowCredentials as $allowed) {
            if (hash_equals($allowed->id, $rawId)) {
                return true;
            }
        }

        return false;
    }
}
