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
 * @since         5.14.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Webauthn;

use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnCredentialsService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnSetupGetController extends MfaSetupController
{
    /**
     * Default location the user is sent back to once the registration ceremony completes.
     *
     * @var string
     */
    public const DEFAULT_REDIRECT = '/app/settings/mfa';

    /**
     * Render the top-level registration ceremony page served from the passbolt origin. This is the
     * page the browser extension navigates to (target="_top") so that navigator.credentials.create()
     * binds to the correct relying party id.
     *
     * As JSON, it returns the list of the user's registered security keys (for the management UI).
     *
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnCredentialsService $credentials Credentials service
     * @return void
     */
    public function get(MfaWebauthnCredentialsService $credentials): void
    {
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_WEBAUTHN);

        if ($this->getRequest()->is('json')) {
            $userId = $this->User->getAccessControl()->getId();
            $this->success(__('The operation was successful.'), [
                'credentials' => $credentials->listForUser($userId),
            ]);

            return;
        }

        $this->set('redirect', self::DEFAULT_REDIRECT);
        $this->set('csrfToken', $this->getRequest()->getAttribute('csrfToken'));
        $this->viewBuilder()
            ->setLayout('mfa_verify')
            ->setTemplatePath('Webauthn')
            ->setTemplate('setup');
    }
}
