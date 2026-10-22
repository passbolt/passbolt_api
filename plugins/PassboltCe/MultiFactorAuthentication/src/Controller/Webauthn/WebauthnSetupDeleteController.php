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

use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupDeleteController;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnDisableService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnSetupDeleteController extends MfaSetupDeleteController
{
    /**
     * @param string $credentialId Credential row uuid.
     * @return void
     */
    public function delete(string $credentialId): void
    {
        $this->_assertRequestIsJson();
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_WEBAUTHN);

        if (!Validation::uuid($credentialId)) {
            throw new BadRequestException(__('The credential identifier should be a valid UUID.'));
        }

        (new MfaWebauthnDisableService())->disable($this->User->getAccessControl(), $credentialId);

        $this->success(__('The operation was successful.'));
    }
}
