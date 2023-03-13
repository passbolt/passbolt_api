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
namespace Passbolt\MultiFactorAuthentication\Controller\Duo;

use App\Authenticator\SessionIdentificationServiceInterface;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Form\MfaFormInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoVerifyGetController extends MfaVerifyController
{
    /**
     * Duo Verify Get
     *
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService session ID service
     * @param \Passbolt\MultiFactorAuthentication\Form\MfaFormInterface $verifyForm MFA Form
     * @throws \Cake\Http\Exception\InternalErrorException if there is no MFA settings for the user
     * @throws \Cake\Http\Exception\BadRequestException if valid Verification token is already present in cookie
     * @throws \Cake\Http\Exception\BadRequestException if there is no MFA settings for this provider
     * @return \Cake\Http\Response|void
     */
    public function get(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        MfaFormInterface $verifyForm
    ) {
        $this->_assertRequestNotJson();
        $this->_handleVerifiedNotRequired($sessionIdentificationService);
        $redirect = $this->_handleInvalidSettings(MfaSettings::PROVIDER_DUO);
        if ($redirect) {
            return $redirect;
        }

        /** @var \Passbolt\MultiFactorAuthentication\Form\Duo\DuoVerifyForm $verifyForm */
        $this->set('verifyForm', $verifyForm);
        $this->set('formUrl', $this->getFormUrl());
        $this->set('providers', $this->mfaSettings->getEnabledProviders());
        $this->set('theme', $this->User->theme());
        $this->viewBuilder()
            ->setLayout('mfa_verify')
            ->setTemplatePath(ucfirst(MfaSettings::PROVIDER_DUO))
            ->setTemplate('verifyForm');
    }

    /**
     * Get the full Duo verify redirect URL, based on given redirect query parameter.
     *
     * @return string
     */
    protected function getFormUrl(): string
    {
        $redirect = $this->SanitizeUrl->sanitizeRedirect('/mfa/verify');

        return Router::url('/mfa/verify/duo/prompt?redirect=' . $redirect, true);
    }
}
