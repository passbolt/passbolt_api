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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Duo;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Form\Duo\DuoSetupForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoSetupGetController extends MfaSetupController
{
    /**
     * Duo Get Qr Code and provisioning urls
     *
     * @return void
     */
    public function get()
    {
        if ($this->request->is('json')) {
            throw new BadRequestException(__('This functionality is not available using AJAX/JSON.'));
        }
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_DUO);
        try {
            $this->_notAlreadySetupOrFail(MfaSettings::PROVIDER_DUO);
            $this->_handleGetNewSettings();
        } catch (BadRequestException $exception) {
            $this->_handleGetExistingSettings(MfaSettings::PROVIDER_DUO);
        }
    }

    /**
     * Handle get request when new settings are needed
     *
     * @return void
     */
    protected function _handleGetNewSettings()
    {
        try {
            $setupForm = new DuoSetupForm($this->User->getAccessControl(), $this->mfaSettings);
            $this->set('sigRequest', $setupForm->getSigRequest());
            $this->set('hostName', $this->mfaSettings->getOrganizationSettings()->getDuoHostname());
        } catch (RecordNotFoundException $exception) {
            throw new InternalErrorException(__('MFA Duo organization settings are not complete.'));
        }
        $this->set('setupForm', $setupForm);
        $this->set('theme', $this->User->theme());
        $this->viewBuilder()
            ->setLayout('mfa_setup')
            ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_DUO))
            ->setTemplate('setupForm');
    }
}
