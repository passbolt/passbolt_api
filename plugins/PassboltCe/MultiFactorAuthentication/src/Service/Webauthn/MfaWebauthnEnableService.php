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
namespace Passbolt\MultiFactorAuthentication\Service\Webauthn;

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Webauthn\CredentialRecord;

/**
 * Persists a freshly registered credential and enables the WebAuthn provider for the user.
 *
 * The provider is only added to the user's account settings on the first credential (subsequent
 * credentials are just appended to the credentials table), so the providers list never accumulates
 * duplicate "webauthn" entries.
 */
class MfaWebauthnEnableService
{
    /**
     * Event dispatched when a user registers a new security key. Used to notify the user by email.
     *
     * @var string
     */
    public const WEBAUTHN_CREDENTIAL_REGISTERED_EVENT = 'MultiFactorAuthentication.Webauthn.credentialRegistered';

    /**
     * Event dispatched when a user removes one or several security keys.
     *
     * @var string
     */
    public const WEBAUTHN_CREDENTIAL_REMOVED_EVENT = 'MultiFactorAuthentication.Webauthn.credentialRemoved';

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnCredentialsService
     */
    protected MfaWebauthnCredentialsService $credentials;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnCredentialsService|null $credentials Credentials service
     */
    public function __construct(?MfaWebauthnCredentialsService $credentials = null)
    {
        $this->credentials = $credentials ?? new MfaWebauthnCredentialsService();
    }

    /**
     * Store the credential and enable the provider if needed.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param \Webauthn\CredentialRecord $record Validated credential record
     * @param string|null $name User provided friendly name
     * @return \Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential
     */
    public function enable(
        UserAccessControl $uac,
        CredentialRecord $record,
        ?string $name = null
    ): MfaWebauthnCredential {
        $entity = $this->credentials->create($uac->getId(), $record, $name);

        if (!$this->isProviderAlreadyEnabled($uac)) {
            MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_WEBAUTHN);
        }
        MfaSettings::clear();

        EventManager::instance()->dispatch(new Event(self::WEBAUTHN_CREDENTIAL_REGISTERED_EVENT, $this, [
            'userId' => $uac->getId(),
            'name' => $entity->get('name'),
        ]));

        return $entity;
    }

    /**
     * @param \App\Utility\UserAccessControl $uac User access control
     * @return bool
     */
    protected function isProviderAlreadyEnabled(UserAccessControl $uac): bool
    {
        try {
            return MfaAccountSettings::get($uac)->isProviderReady(MfaSettings::PROVIDER_WEBAUTHN);
        } catch (RecordNotFoundException | InternalErrorException $exception) {
            return false;
        }
    }
}
