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
namespace Passbolt\MultiFactorAuthentication\Controller\Totp;

use App\Controller\AppController;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Passbolt\MultiFactorAuthentication\Form\TotpSetupForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\OtpFactory;

class TotpSetupGetController extends AppController
{
    /**
     * Totp Get Qr Code and provisioning urls
     *
     * @return void
     */
    public function get()
    {
        $isReadyToUse = false;
        $uac = $this->User->getAccessControl();
        try {
            $mfaSettings = MfaSettings::get($uac);
            $isReadyToUse = $mfaSettings->isReadyToUse(MfaSettings::PROVIDER_OTP);
        } catch(RecordNotFoundException $exception) {
        }

        if (!$isReadyToUse) {
            $this->_handleGetNewSettings($uac);
        } else {
            $this->_handleGetExistingSettings($mfaSettings);
        }
    }

    public function start()
    {
        $isReadyToUse = false;
        $uac = $this->User->getAccessControl();
        try {
            $mfaSettings = MfaSettings::get($uac);
            $isReadyToUse = $mfaSettings->isReadyToUse(MfaSettings::PROVIDER_OTP);
        } catch(RecordNotFoundException $exception) {
        }

        if (!$isReadyToUse) {
            $this->_handleGetStart();
        } else {
            $this->_handleGetExistingSettings($mfaSettings);
        }
    }

    public function _handleGetStart() {
        if (!$this->request->is('json')) {
            $this->set('theme', $this->User->theme());
            $this->viewBuilder()
                ->setLayout('totp_setup')
                ->setTemplatePath('Totp')
                ->setTemplate('setupStart');
        } else {
            $this->success(__('Please setup the TOTP application.'));
        }
    }

    /**
     * Handle get request when ready to use settings are present
     *
     * @param MfaSettings $mfaSettings
     * @return void
     */
    protected function _handleGetExistingSettings(MfaSettings $mfaSettings)
    {
        $this->set('theme', $this->User->theme());
        $this->viewBuilder()
            ->setLayout('totp_setup')
            ->setTemplatePath('Totp')
            ->setTemplate('setupSuccess');

        $this->success(__('Multi Factor Authentication is configured!'), [
            'created' => $mfaSettings->getCreated(),
            'modified' => $mfaSettings->getModified()
        ]);
    }

    /**
     * Handle get request when new settings are needed
     *
     * @param UserAccessControl $uac
     * @return void
     */
    protected function _handleGetNewSettings(UserAccessControl $uac)
    {
        // Build and return some URI and QR code to work from
        // even though they can be set manually in the post as well
        $totpSetupForm = new TotpSetupForm($uac);
        $uri = OtpFactory::generateTOTP($uac);
        $qrCode = OtpFactory::getQrCodeInline($uri);

        if (!$this->request->is('json')) {
            $this->set('totpSetupForm', $totpSetupForm);
            $this->set('theme', $this->User->theme());
            $this->request = $this->request
                ->withData('otpQrCodeImage', $qrCode)
                ->withData('otpProvisioningUri', $uri);
            $this->viewBuilder()
                ->setLayout('totp_setup')
                ->setTemplatePath('Totp')
                ->setTemplate('setupForm');
        } else {
            $data = [
                'otpQrCodeImage' => $qrCode,
                'otpProvisioningUri' => $uri
            ];
            $this->success(__('Please setup the TOTP application.'), $data);
        }
    }
}
