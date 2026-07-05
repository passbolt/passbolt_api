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

use Cake\Http\Exception\NotFoundException;
use Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService;

class PasskeyDeleteController extends PasskeyController
{
    /**
     * Delete either a single passkey credential (when a credential id is provided) or all of the
     * current user's passkey credentials.
     *
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService $credentials Credentials service
     * @param string|null $credentialId The base64url credential id to delete, or null for all
     * @return void
     */
    public function delete(PasskeyCredentialsService $credentials, ?string $credentialId = null): void
    {
        $userId = $this->User->getAccessControl()->getId();

        if ($credentialId !== null) {
            if (!$credentials->deleteOne($userId, $credentialId)) {
                throw new NotFoundException(__('The security key does not exist.'));
            }
            $this->success(__('The security key has been deleted.'));

            return;
        }

        $credentials->deleteAllForUser($userId);
        $this->success(__('The security keys have been deleted.'));
    }
}
