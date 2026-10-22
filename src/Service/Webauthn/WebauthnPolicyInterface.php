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

interface WebauthnPolicyInterface
{
    public const ATTACHMENT_PLATFORM = 'platform';
    public const ATTACHMENT_CROSS_PLATFORM = 'cross-platform';
    public const ATTACHMENT_ALLOWED = [
        self::ATTACHMENT_PLATFORM,
        self::ATTACHMENT_CROSS_PLATFORM,
    ];

    public const RESIDENT_KEY_DISCOURAGED = 'discouraged';
    public const RESIDENT_KEY_PREFERRED = 'preferred';
    public const RESIDENT_KEY_REQUIRED = 'required';
    public const RESIDENT_KEY_ALLOWED = [
        self::RESIDENT_KEY_DISCOURAGED,
        self::RESIDENT_KEY_PREFERRED,
        self::RESIDENT_KEY_REQUIRED,
    ];

    public const USER_VERIFICATION_DISCOURAGED = 'discouraged';
    public const USER_VERIFICATION_PREFERRED = 'preferred';
    public const USER_VERIFICATION_REQUIRED = 'required';
    public const USER_VERIFICATION_ALLOWED = [
        self::USER_VERIFICATION_DISCOURAGED,
        self::USER_VERIFICATION_PREFERRED,
        self::USER_VERIFICATION_REQUIRED,
    ];

    public const HINT_SECURITY_KEY = 'security-key';
    public const HINT_CLIENT_DEVICE = 'client-device';
    public const HINT_HYBRID = 'hybrid';
    public const HINT_ALLOWED = [
        self::HINT_SECURITY_KEY,
        self::HINT_CLIENT_DEVICE,
        self::HINT_HYBRID,
    ];

    public const TIMEOUT_MS_MIN = 30_000;
    public const TIMEOUT_MS_MAX = 300_000;

    public const ATTESTATION_NONE = 'none';
    public const ATTESTATION_DIRECT = 'direct';

    /**
     * @return string|null
     */
    public function getAuthenticatorAttachment(): ?string;

    /**
     * @return bool
     */
    public function rejectBackupEligible(): bool;

    /**
     * @return string
     */
    public function getResidentKey(): string;

    /**
     * @return string
     */
    public function getUserVerification(): string;

    /**
     * @return list<string>
     */
    public function getHints(): array;

    /**
     * @return int
     */
    public function getTimeoutMs(): int;

    /**
     * @return string
     */
    public function getAttestationConveyancePreference(): string;

    /**
     * @return list<string>
     */
    public function getAaguidAllowlist(): array;

    /**
     * @return list<string>
     */
    public function getAaguidBlocklist(): array;
}
