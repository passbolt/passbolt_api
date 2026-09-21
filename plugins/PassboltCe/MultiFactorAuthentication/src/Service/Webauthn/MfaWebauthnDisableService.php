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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Service\Webauthn;

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaWebauthnDisableService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    public const MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT = 'mfa.webauthn.credential.removed';

    /**
     * @param \App\Utility\UserAccessControl $uac User access control of the caller.
     * @param string $credentialId Credential row uuid.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException When the credential doesn't exist for the caller.
     */
    public function disable(UserAccessControl $uac, string $credentialId): void
    {
        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $credentials */
        $credentials = $this->fetchTable('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');

        $affected = $credentials->deleteAll([
            $credentials->aliasField('id') => $credentialId,
            $credentials->aliasField('user_id') => $uac->getId(),
            $credentials->aliasField('type') => $credentials->getType(),
        ]);
        if ($affected === 0) {
            throw new BadRequestException(__('The credential does not exist.'));
        }

        if ($credentials->countForUser($uac->getId()) === 0) {
            try {
                MfaAccountSettings::get($uac)->disableProvider(MfaSettings::PROVIDER_WEBAUTHN);
            } catch (RecordNotFoundException) {
                // No account settings row — nothing to disable, expected when webauthn was the sole provider.
            }
        }

        $this->dispatchEvent(self::MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT, [
            'uac' => $uac,
            'credentialId' => $credentialId,
        ]);
    }
}
