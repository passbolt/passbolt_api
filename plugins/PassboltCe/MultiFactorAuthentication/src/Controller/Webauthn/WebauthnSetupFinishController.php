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
namespace Passbolt\MultiFactorAuthentication\Controller\Webauthn;

use App\Authenticator\SessionIdentificationServiceInterface;
use Cake\Http\Exception\BadRequestException;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAttestationService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnSetupFinishController extends MfaSetupController
{
    /**
     * Finish a WebAuthn registration ceremony: validate the attestation returned by the authenticator,
     * store the credential and enable the provider. A verified MFA cookie is issued so the freshly
     * registered key satisfies the current MFA session.
     *
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAttestationService $service Attestation verification service
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session id service
     * @return void
     */
    public function post(
        MfaWebauthnVerifyAttestationService $service,
        SessionIdentificationServiceInterface $sessionIdentificationService
    ): void {
        $this->_assertRequestIsJson();
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_WEBAUTHN);

        $token = $this->request->getData('token');
        if (!is_string($token) || $token === '') {
            throw new BadRequestException(__('A challenge token is required.'));
        }
        $name = $this->request->getData('name');
        $name = is_string($name) && trim($name) !== '' ? trim($name) : null;

        $service->verify(
            $this->User->getAccessControl(),
            $token,
            $this->extractCredentialJson(),
            $name
        );

        $this->_handlePostSuccess(MfaSettings::PROVIDER_WEBAUTHN, $sessionIdentificationService);
    }

    /**
     * Extract the raw JSON credential from the request. The client may send it either as a JSON
     * encoded string or as an already decoded object; the webauthn-lib serializer expects a string.
     *
     * @return string
     * @throws \Cake\Http\Exception\BadRequestException If the credential is missing
     */
    protected function extractCredentialJson(): string
    {
        $credential = $this->request->getData('credential');
        if (is_string($credential) && $credential !== '') {
            return $credential;
        }
        if (is_array($credential)) {
            return (string)json_encode($credential);
        }

        throw new BadRequestException(__('A registration response is required.'));
    }
}
