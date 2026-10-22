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
namespace Passbolt\MultiFactorAuthentication\Model\Dto;

use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface;

readonly class MfaWebauthnSettingsDto
{
    /**
     * @param string|null $authenticatorAttachment Authenticator attachment preference.
     * @param bool $rejectBackupEligible Whether backup-eligible credentials are rejected.
     * @param string $residentKey Resident-key requirement.
     * @param string $userVerification User-verification requirement.
     * @param list<string> $hints Ceremony hints.
     * @param int $timeoutMs Ceremony timeout in milliseconds.
     * @param int $maxCredentialsPerUser Max credentials allowed per user.
     * @param string $attestation Attestation conveyance preference.
     * @param list<string> $aaguidAllowlist Permitted AAGUIDs.
     * @param list<string> $aaguidBlocklist Blocked AAGUIDs.
     */
    final public function __construct(
        public ?string $authenticatorAttachment,
        public bool $rejectBackupEligible,
        public string $residentKey,
        public string $userVerification,
        public array $hints,
        public int $timeoutMs,
        public int $maxCredentialsPerUser,
        public string $attestation,
        public array $aaguidAllowlist,
        public array $aaguidBlocklist,
    ) {
    }

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface $policy Policy.
     * @return static
     */
    public static function fromPolicy(MfaWebauthnPolicyInterface $policy): static
    {
        return new static(
            $policy->getAuthenticatorAttachment(),
            $policy->rejectBackupEligible(),
            $policy->getResidentKey(),
            $policy->getUserVerification(),
            $policy->getHints(),
            $policy->getTimeoutMs(),
            $policy->getMaxCredentialsPerUser(),
            $policy->getAttestationConveyancePreference(),
            $policy->getAaguidAllowlist(),
            $policy->getAaguidBlocklist(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'authenticator_attachment' => $this->authenticatorAttachment,
            'reject_backup_eligible' => $this->rejectBackupEligible,
            'resident_key' => $this->residentKey,
            'user_verification' => $this->userVerification,
            'hints' => $this->hints,
            'timeout_ms' => $this->timeoutMs,
            'max_credentials_per_user' => $this->maxCredentialsPerUser,
            'attestation' => $this->attestation,
            'aaguid_allowlist' => $this->aaguidAllowlist,
            'aaguid_blocklist' => $this->aaguidBlocklist,
        ];
    }
}
