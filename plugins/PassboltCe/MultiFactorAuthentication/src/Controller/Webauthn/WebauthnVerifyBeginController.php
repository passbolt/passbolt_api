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
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartAssertionService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnVerifyBeginController extends MfaVerifyController
{
    /**
     * Start a WebAuthn authentication ceremony: issue the credential request options (restricted to
     * the user's registered credentials) and a challenge token.
     *
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartAssertionService $service Assertion service
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session id service
     * @return void
     */
    public function post(
        MfaWebauthnStartAssertionService $service,
        SessionIdentificationServiceInterface $sessionIdentificationService
    ): void {
        $this->_assertRequestIsJson();
        $this->_handleVerifiedNotRequired($sessionIdentificationService);
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_WEBAUTHN);

        $result = $service->start($this->User->getAccessControl());

        $this->success(__('The security key authentication has started.'), [
            'token' => $result['token']->token,
            'credentialRequestOptions' => json_decode($result['options'], true),
        ]);
    }
}
