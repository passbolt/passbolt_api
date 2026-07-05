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

use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\Exception\NotFoundException;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupDeleteController;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnCredentialsService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnableService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnSetupDeleteController extends MfaSetupDeleteController
{
    /**
     * Delete either a single security key (when a credential id is provided) or the whole WebAuthn
     * provider. Removing the last remaining key also disables the provider.
     *
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnCredentialsService $credentials Credentials service
     * @param string|null $credentialId The base64url credential id to delete, or null for all
     * @return void
     */
    public function delete(MfaWebauthnCredentialsService $credentials, ?string $credentialId = null): void
    {
        $userId = $this->User->getAccessControl()->getId();

        if ($credentialId !== null) {
            if (!$credentials->deleteOne($userId, $credentialId)) {
                throw new NotFoundException(__('The security key does not exist.'));
            }
            $this->notifyRemoval($userId);
            // Disable the provider only when no security key remains.
            if ($credentials->countForUser($userId) === 0) {
                $this->_handleDelete(MfaSettings::PROVIDER_WEBAUTHN);

                return;
            }
            $this->success(__('The security key was deleted.'));

            return;
        }

        // Delete every security key then disable the provider.
        $credentials->deleteAllForUser($userId);
        $this->notifyRemoval($userId);
        $this->_handleDelete(MfaSettings::PROVIDER_WEBAUTHN);
    }

    /**
     * Dispatch the credential removed event so the user is notified by email.
     *
     * @param string $userId User id
     * @return void
     */
    protected function notifyRemoval(string $userId): void
    {
        EventManager::instance()->dispatch(
            new Event(MfaWebauthnEnableService::WEBAUTHN_CREDENTIAL_REMOVED_EVENT, $this, ['userId' => $userId])
        );
    }
}
