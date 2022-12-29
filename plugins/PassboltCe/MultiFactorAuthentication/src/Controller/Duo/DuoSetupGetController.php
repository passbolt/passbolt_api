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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Duo;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Form\MfaFormInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoSetupGetController extends MfaSetupController
{
    /**
     * Duo Get Qr Code and provisioning urls
     *
     * @param \Passbolt\MultiFactorAuthentication\Form\MfaFormInterface $setupForm MFA Form
     * @return void
     */
    public function get(MfaFormInterface $setupForm)
    {
        if ($this->request->is('json')) {
            throw new BadRequestException(__('This functionality is not available using AJAX/JSON.'));
        }
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_DUO);
        try {
            $this->_notAlreadySetupOrFail(MfaSettings::PROVIDER_DUO);
            $this->_handleGetNewSettings($setupForm);
        } catch (BadRequestException $exception) {
            $this->_handleGetExistingSettings(MfaSettings::PROVIDER_DUO);
        }
    }

    /**
     * Handle get request when new settings are needed
     *
     * @param \Passbolt\MultiFactorAuthentication\Form\MfaFormInterface $setupForm MFA Form
     * @return void
     */
    protected function _handleGetNewSettings(MfaFormInterface $setupForm)
    {
        /** @var \Passbolt\MultiFactorAuthentication\Form\Duo\DuoSetupForm $setupForm */
        try {
            $this->set('sigRequest', $setupForm->getSigRequest());
            $this->set('hostName', $this->mfaSettings->getOrganizationSettings()->getDuoHostname());
        } catch (RecordNotFoundException $exception) {
            throw new InternalErrorException('MFA Duo organization settings are not complete.');
        }
        $this->set('setupForm', $setupForm);
        $this->set('theme', $this->User->theme());
        $this->viewBuilder()
            ->setLayout('mfa_setup')
            ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_DUO))
            ->setTemplate('setupForm');
    }
}
