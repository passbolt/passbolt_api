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
namespace Passbolt\MultiFactorAuthentication\Controller\Totp;

use Cake\Http\Exception\BadRequestException;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Form\Totp\TotpSetupForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaOtpFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class TotpSetupGetController extends MfaSetupController
{
    /**
     * Totp Get Qr Code and provisioning urls
     *
     * @return void
     */
    public function get()
    {
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_TOTP);
        try {
            $this->_notAlreadySetupOrFail(MfaSettings::PROVIDER_TOTP);
            $this->_handleGetNewSettings();
        } catch (BadRequestException $exception) {
            $this->_handleGetExistingSettings(MfaSettings::PROVIDER_TOTP);
        }
    }

    /**
     * Display start page (with how to diagram)
     *
     * @return void
     */
    public function start()
    {
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_TOTP);
        try {
            $this->_notAlreadySetupOrFail(MfaSettings::PROVIDER_TOTP);
            $this->_handleGetStart();
        } catch (BadRequestException $exception) {
            $this->_handleGetExistingSettings(MfaSettings::PROVIDER_TOTP);
        }
    }

    /**
     * Display start page
     *
     * @return void
     */
    protected function _handleGetStart()
    {
        if (!$this->request->is('json')) {
            $this->set('theme', $this->User->theme());
            $this->viewBuilder()
                ->setLayout('mfa_setup')
                ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_TOTP))
                ->setTemplate('setupStart');
        }
        $this->success(__('Please setup the TOTP application.'));
    }

    /**
     * Handle get request when new settings are needed
     *
     * @return void
     */
    protected function _handleGetNewSettings()
    {
        // Build and return some URI and QR code to work from
        // even though they can be set manually in the post as well
        $uac = $this->User->getAccessControl();
        $totpSetupForm = new TotpSetupForm($uac);
        $uri = MfaOtpFactory::generateTOTP($uac);
        $qrCode = MfaOtpFactory::getQrCodeInline($uri);

        if (!$this->request->is('json')) {
            $this->set('totpSetupForm', $totpSetupForm);
            $this->set('theme', $this->User->theme());
            $this->request = $this->request
                ->withData('otpQrCodeImage', $qrCode)
                ->withData('otpProvisioningUri', $uri);
            $this->viewBuilder()
                ->setLayout('mfa_setup')
                ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_TOTP))
                ->setTemplate('setupForm');
        } else {
            $data = [
                'otpQrCodeImage' => $qrCode,
                'otpProvisioningUri' => $uri,
            ];
            $this->success(__('Please setup the TOTP application.'), $data);
        }
    }
}
