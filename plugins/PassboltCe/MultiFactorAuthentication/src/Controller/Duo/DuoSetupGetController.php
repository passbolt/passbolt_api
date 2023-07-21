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
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Form\MfaFormInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoSetupGetController extends MfaSetupController
{
    public const PROVIDER = MfaSettings::PROVIDER_DUO;
    public const DUO_SETUP_REDIRECT_PATH = '/app/settings/mfa/duo';

    /**
     * Duo Get Qr Code and provisioning urls
     *
     * @param \Passbolt\MultiFactorAuthentication\Form\MfaFormInterface $setupForm MFA Form
     * @return void
     */
    public function get(MfaFormInterface $setupForm)
    {
        $this->_assertRequestNotJson();
        $this->_orgAllowProviderOrFail(self::PROVIDER);

        $error = $this->_consumeError();
        if (!empty($error)) {
            $this->_handleError($setupForm, $error);

            return;
        }

        try {
            $this->_notAlreadySetupOrFail(self::PROVIDER);
            $this->_handleGetNewSettings($setupForm);
        } catch (BadRequestException $exception) {
            $this->_handleGetExistingSettings(self::PROVIDER);
        }
    }

    /**
     * Get the full Duo setup redirect URL.
     *
     * @return string
     */
    public static function getFormUrl(): string
    {
        return Router::url('/mfa/setup/duo/prompt?redirect=' . self::DUO_SETUP_REDIRECT_PATH, true);
    }

    /**
     * Handle get request when new settings are needed
     *
     * @param \Passbolt\MultiFactorAuthentication\Form\MfaFormInterface $setupForm MFA Form
     * @return void
     */
    protected function _handleGetNewSettings(MfaFormInterface $setupForm)
    {
        /** @var \Passbolt\MultiFactorAuthentication\Form\Duo\DuoCallbackForm $setupForm */
        try {
            $this->mfaSettings->getOrganizationSettings()->getDuoOrgSettings();
        } catch (RecordNotFoundException $exception) {
            throw new InternalErrorException('MFA Duo organization settings are not complete.', 500, $exception);
        }
        $this->set('setupForm', $setupForm);
        $this->set('formUrl', self::getFormUrl());
        $this->set('theme', $this->User->theme());
        $this->viewBuilder()
            ->setLayout('mfa_setup')
            ->setTemplatePath(ucfirst(self::PROVIDER))
            ->setTemplate('setupForm');
    }

    /**
     * Handle get request when a Duo error is present, by displaying an error form.
     *
     * @param \Passbolt\MultiFactorAuthentication\Form\MfaFormInterface $setupForm MFA Form
     * @param string $errorMsg Error message to display
     * @return void
     */
    protected function _handleError(MfaFormInterface $setupForm, string $errorMsg): void
    {
        $this->getRequest()->getFlash()->set($errorMsg, [
            'element' => 'raw',
            'key' => 'duo_auth_error',
            'clear' => true,
        ]);
        $this->set('setupForm', $setupForm);
        $this->set('formUrl', self::getFormUrl());
        $this->set('theme', $this->User->theme());
        $this->viewBuilder()
            ->setLayout('mfa_setup')
            ->setTemplatePath(ucfirst(self::PROVIDER))
            ->setTemplate('setupError');
    }

    /**
     * Get the Duo error if present.
     *
     * @return string
     */
    protected function _consumeError(): string
    {
        $errorMsg = $this->getRequest()->getFlash()->consume('flash');
        if ($errorMsg !== null && isset($errorMsg[0]['message'])) {
            return $errorMsg[0]['message'];
        }

        return '';
    }
}
