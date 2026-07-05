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

use Passbolt\PasskeyAuthentication\Service\PasskeyStartRegistrationService;

class PasskeySetupBeginController extends PasskeyController
{
    /**
     * Start a passkey enrollment ceremony: issue the credential creation options and a challenge
     * token. A user may register several security keys, so this endpoint does not fail when the user
     * already has passkey credentials.
     *
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyStartRegistrationService $service Registration service
     * @return void
     */
    public function post(PasskeyStartRegistrationService $service): void
    {
        $this->assertJson();

        $result = $service->start($this->User->getAccessControl());

        $this->success(__('The passkey enrollment has started.'), [
            'token' => $result['token']->token,
            'credentialCreationOptions' => json_decode($result['options'], true),
        ]);
    }
}
