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
namespace Passbolt\MultiFactorAuthentication\Controller\Yubikey;

use App\Utility\UserAccessControl;
use Cake\Network\Exception\BadRequestException;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Form\YubikeySetupForm;

class YubikeySetupGetController extends MfaSetupController
{
    /**
     * @var MfaSettings
     */
    protected $mfaSettings;

    /**
     * Totp Get Qr Code and provisioning urls
     *
     * @return void
     */
    public function get()
    {
        $this->orgAllowProviderOrFail(MfaSettings::PROVIDER_YUBIKEY);
        try {
            $this->notAlreadySetupOrFail(MfaSettings::PROVIDER_YUBIKEY);
            $this->_handleGetNewSettings();
        } catch (BadRequestException $exception) {
            $this->_handleGetExistingSettings(MfaSettings::PROVIDER_YUBIKEY);
        }
    }

    /**
     * Handle get request when new settings are needed
     *
     * @param UserAccessControl $uac
     * @return void
     */
    protected function _handleGetNewSettings()
    {
        // Build and return some URI and QR code to work from
        // even though they can be set manually in the post as well
        $uac = $this->User->getAccessControl();
        $totpSetupForm = new YubikeySetupForm($uac);

        if (!$this->request->is('json')) {
            $this->set('yubikeySetupForm', $totpSetupForm);
            $this->set('theme', $this->User->theme());
            $this->viewBuilder()
                ->setLayout('mfa_setup')
                ->setTemplatePath('Yubikey')
                ->setTemplate('setupForm');
        } else {
            $this->success(__('Please setup the Yubikey settings.'));
        }
    }
}
