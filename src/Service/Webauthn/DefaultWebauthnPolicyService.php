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

namespace App\Service\Webauthn;

class DefaultWebauthnPolicyService implements WebauthnPolicyInterface
{
    /**
     * @inheritDoc
     */
    public function getAuthenticatorAttachment(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function rejectBackupEligible(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getResidentKey(): string
    {
        return self::RESIDENT_KEY_DISCOURAGED;
    }

    /**
     * @inheritDoc
     */
    public function getUserVerification(): string
    {
        return self::USER_VERIFICATION_PREFERRED;
    }

    /**
     * @inheritDoc
     */
    public function getHints(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getTimeoutMs(): int
    {
        return 60_000;
    }

    /**
     * @inheritDoc
     */
    public function getAttestationConveyancePreference(): string
    {
        return self::ATTESTATION_NONE;
    }

    /**
     * @inheritDoc
     */
    public function getAaguidAllowlist(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAaguidBlocklist(): array
    {
        return [];
    }
}
