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
use App\Error\Exception\CustomValidationException;
use Passbolt\MultiFactorAuthentication\Form\TotpSetupForm;

class TotpSetupPostController extends AppController
{

    public function post()
    {
        $uac = $this->User->getAccessControl();
        $totpSetupForm = new TotpSetupForm($uac);
        try {
            $totpSetupForm->execute($this->request->getData());
        } catch (CustomValidationException $exception) {
            if ($this->request->is('json')) {
                throw $exception;
            } else {
                $this->set('totpSetupForm', $totpSetupForm);
                $this->request = $this->request
                    ->withData('otpQrCodeImage', $this->request->getData('otpQrCodeImage'));
                $this->viewBuilder()
                    ->setLayout('totp')
                    ->setTemplatePath('Totp')
                    ->setTemplate('setupForm');
            }

            return;
        }
        $this->viewBuilder()
            ->setLayout('totp')
            ->setTemplatePath('Totp')
            ->setTemplate('setupSuccess');

        $this->success(__('The TOTP setup is complete!'));
    }
}
