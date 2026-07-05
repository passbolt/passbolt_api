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

use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartRegistrationService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnSetupBeginController extends MfaSetupController
{
    /**
     * Start a WebAuthn registration ceremony: issue the credential creation options and a challenge
     * token. A user may register several security keys, so this endpoint does not fail when the
     * provider is already set up.
     *
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartRegistrationService $service Registration service
     * @return void
     */
    public function post(MfaWebauthnStartRegistrationService $service): void
    {
        $this->_assertRequestIsJson();
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_WEBAUTHN);

        $result = $service->start($this->User->getAccessControl());

        $this->success(__('The security key registration has started.'), [
            'token' => $result['token']->token,
            'credentialCreationOptions' => json_decode($result['options'], true),
        ]);
    }
}
