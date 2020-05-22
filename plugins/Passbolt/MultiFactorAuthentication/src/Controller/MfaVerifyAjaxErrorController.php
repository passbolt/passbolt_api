<?php
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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller;

class MfaVerifyAjaxErrorController extends MfaController
{
    /**
     * @throw ForbiddenException
     * @return void
     */
    public function get()
    {
        // Use AppController:error instead of exception to avoid logging the error
        $this->error(__('MFA authentication is required.'), [
            'providers' => $this->mfaSettings->getProvidersVerifyUrls(),
        ], 403);
    }
}
