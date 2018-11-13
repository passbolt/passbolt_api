<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Duo;

use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Form\Duo\DuoVerifyForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoVerifyGetController extends MfaVerifyController
{
    /**
     * Duo Verify Get
     *
     * @throws InternalErrorException if there is no MFA settings for the user
     * @throws BadRequestException if valid Verification token is already present in cookie
     * @throws BadRequestException if there is no MFA settings for this provider
     * @return void
     */
    public function get()
    {
        if ($this->request->is('json')) {
            throw new BadRequestException(__('This functionality is not available using AJAX/JSON.'));
        }
        $this->_handleVerifiedNotRequired();
        $this->_handleInvalidSettings(MfaSettings::PROVIDER_DUO);

        $uac = $this->User->getAccessControl();
        $verifyForm = new DuoVerifyForm($uac, $this->mfaSettings);
        $this->set('sigRequest', $verifyForm->getSigRequest());
        $this->set('hostName', $this->mfaSettings->getOrganizationSettings()->getDuoHostname());
        $this->set('verifyForm', $verifyForm);
        $this->set('providers', $this->mfaSettings->getEnabledProviders());
        $this->viewBuilder()
            ->setLayout('mfa_verify')
            ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_DUO))
            ->setTemplate('verifyForm');
    }
}
