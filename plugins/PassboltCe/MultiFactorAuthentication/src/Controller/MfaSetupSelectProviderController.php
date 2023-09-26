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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller;

use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaSetupSelectProviderController extends MfaController
{
    /**
     * @throw ForbiddenException
     * @return void
     */
    public function get()
    {
        $body = $this->mfaSettings->getProvidersStatuses();
        $isMfaPossible = false;
        foreach ($body[MfaSettings::ORG_SETTINGS] as $provider => $enabled) {
            if ($enabled) {
                $isMfaPossible = true;
                break;
            }
        }

        if (!$this->request->is('json')) {
            $this->set('theme', $this->User->theme());
            $this->viewBuilder()
                ->setLayout('mfa_setup')
                ->setTemplatePath(ucfirst(MfaSettings::MFA))
                ->setTemplate('select');
        }

        $this->set(compact('isMfaPossible'));
        $this->success(__('The operation was successful.'), $body);
    }
}
