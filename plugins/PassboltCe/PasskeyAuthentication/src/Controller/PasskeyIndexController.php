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

class PasskeyIndexController extends PasskeyController
{
    /**
     * List the current user's passkey credentials (for the management UI).
     *
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService $credentials Credentials service
     * @return void
     */
    public function index(PasskeyCredentialsService $credentials): void
    {
        $userId = $this->User->getAccessControl()->getId();

        $this->success(__('The operation was successful.'), [
            'credentials' => $credentials->listForUser($userId),
        ]);
    }
}
