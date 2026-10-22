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
namespace Passbolt\MultiFactorAuthentication\Controller\Webauthn;

use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartRegistrationService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnSetupBeginPostController extends MfaSetupController
{
    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartRegistrationService $service Start service.
     * @return void
     */
    public function post(MfaWebauthnStartRegistrationService $service): void
    {
        $this->_assertRequestIsJson();
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_WEBAUTHN);

        $payload = $service->start($this->User->getAccessControl());
        $this->success(__('The operation was successful.'), $payload);
    }
}
