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
 * @since         5.11.0
 */
namespace Passbolt\Scim\Utility;

use Cake\Core\Configure;
use Cake\Utility\Security;

/**
 * Class responsible for SCIM token verification.
 *
 * Supports bcrypt (current format) and legacy SHA-256 (when allowed by config).
 */
class ScimTokenVerifier
{
    /**
     * Verify a raw bearer token against the stored hash.
     *
     * @param string $rawToken The plaintext bearer token.
     * @param string $storedHash The hash stored in the encrypted settings (bcrypt or SHA-256).
     * @return bool True when the raw token matches the stored hash, false otherwise.
     */
    public static function verify(string $rawToken, string $storedHash): bool
    {
        if (static::isBcryptHash($storedHash)) {
            return password_verify($rawToken, $storedHash);
        }

        // Legacy SHA-256 path — only if allowed by configuration
        if (!Configure::read('passbolt.plugins.scim.security.secretToken.legacyHashAllowed', true)) {
            return false;
        }

        return hash_equals($storedHash, Security::hash($rawToken, 'sha256'));
    }

    /**
     * Check if a hash is in bcrypt format.
     *
     * @param string $hash The hash to check.
     * @return bool
     */
    public static function isBcryptHash(string $hash): bool
    {
        return str_starts_with($hash, '$2y$') || str_starts_with($hash, '$2b$');
    }
}
