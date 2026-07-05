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
namespace Passbolt\PasskeyAuthentication\Controller;

use Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService;

class PasskeySetupGetController extends PasskeyController
{
    /**
     * As JSON: return the list of the user's passkey credentials (management UI).
     *
     * As HTML: render the top-level enrollment ceremony page served from the passbolt origin. This is
     * the page navigator.credentials.create() runs in (top-level) so it binds to the correct relying
     * party id; the raw WebAuthn result is bridged back to the browser extension, which owns the
     * passphrase and builds the kit.
     *
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService $credentials Credentials service
     * @return void
     */
    public function get(PasskeyCredentialsService $credentials): void
    {
        if ($this->getRequest()->is('json')) {
            $this->success(__('The operation was successful.'), [
                'credentials' => $credentials->listForUser($this->User->getAccessControl()->getId()),
            ]);

            return;
        }

        $this->set('mode', 'setup');
        $this->set('userId', $this->User->getAccessControl()->getId());
        $this->set('csrfToken', $this->getRequest()->getAttribute('csrfToken'));
        $this->viewBuilder()
            ->setLayout('passkey_ceremony')
            ->setTemplatePath('Passkey')
            ->setTemplate('ceremony');
    }
}
