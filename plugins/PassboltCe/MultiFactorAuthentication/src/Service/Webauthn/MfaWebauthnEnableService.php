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

use App\Model\Entity\WebauthnCredential;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Locator\LocatorAwareTrait;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Webauthn\CredentialRecord;

class MfaWebauthnEnableService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    public const MFA_WEBAUTHN_CREDENTIAL_ENABLED_EVENT = 'mfa.webauthn.credential.enabled';

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface $policy WebAuthn policy.
     */
    public function __construct(private readonly MfaWebauthnPolicyInterface $policy)
    {
    }

    /**
     * Persist a freshly-attested credential and, on the first credential only, flip the account setting.
     *
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @param \Webauthn\CredentialRecord $source Freshly-attested credential record.
     * @param string|null $name Friendly name.
     * @return \App\Model\Entity\WebauthnCredential
     */
    public function enable(
        UserAccessControl $uac,
        CredentialRecord $source,
        ?string $name = null,
    ): WebauthnCredential {
        if ($this->policy->rejectBackupEligible() && $source->backupEligible === true) {
            throw new BadRequestException(
                __('The organization does not allow enrolling backup-eligible (synced) credentials.'),
            );
        }

        $aaguid = $source->aaguid->toRfc4122();
        $allowlist = $this->policy->getAaguidAllowlist();
        if (!empty($allowlist) && !in_array($aaguid, $allowlist, true)) {
            throw new BadRequestException(
                __('This authenticator is not permitted by the organization policy.'),
            );
        }
        if (in_array($aaguid, $this->policy->getAaguidBlocklist(), true)) {
            throw new BadRequestException(
                __('This authenticator has been blocked by the organization policy.'),
            );
        }

        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $credentials */
        $credentials = $this->fetchTable('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');

        $existingCount = $credentials->countForUser($uac->getId());
        $isFirstCredential = $existingCount === 0;
        if ($existingCount >= $this->policy->getMaxCredentialsPerUser()) {
            throw new BadRequestException(__(
                'You have reached the maximum number of WebAuthn credentials ({0}) allowed.',
                $this->policy->getMaxCredentialsPerUser(),
            ));
        }

        $entity = $credentials->newEntity(
            [
                'user_id' => $uac->getId(),
                'type' => MfaWebauthnCredentialsTable::TYPE,
                'credential_id' => Base64UrlSafe::encodeUnpadded($source->publicKeyCredentialId),
                'data' => $source,
                'sign_count' => $source->counter,
                'name' => $name,
            ],
            ['accessibleFields' => [
                'user_id' => true,
                'type' => true,
                'credential_id' => true,
                'data' => true,
                'sign_count' => true,
                'name' => true,
            ]],
        );

        /** @var \App\Model\Entity\WebauthnCredential $saved */
        $saved = $credentials->saveOrFail($entity);

        if ($isFirstCredential) {
            MfaAccountSettings::enableProvider(
                $uac,
                MfaSettings::PROVIDER_WEBAUTHN,
                [],
            );
        }

        $this->dispatchEvent(self::MFA_WEBAUTHN_CREDENTIAL_ENABLED_EVENT, [
            'uac' => $uac,
            'credential' => $saved,
        ]);

        return $saved;
    }
}
