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
use Passbolt\MultiFactorAuthentication\Model\Dto\MfaWebauthnCredentialDto;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnSetupGetController extends MfaSetupController
{
    /**
     * @return void
     */
    public function get(): void
    {
        $this->_assertRequestIsJson();
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_WEBAUTHN);

        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $credentialsTable */
        $credentialsTable = $this->fetchTable('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');
        $credentials = [];
        foreach ($credentialsTable->listForUser($this->User->id()) as $credential) {
            $credentials[] = MfaWebauthnCredentialDto::fromEntity($credential)->toArray();
        }

        $this->success(__('The operation was successful.'), $credentials);
    }
}
