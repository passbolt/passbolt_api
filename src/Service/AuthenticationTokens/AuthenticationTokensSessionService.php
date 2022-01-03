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
 * @since         3.4.0
 */
namespace App\Service\AuthenticationTokens;

use App\Model\Entity\AuthenticationToken;

class AuthenticationTokensSessionService
{
    public const HASH_ALGO = 'sha256';

    /**
     * @param \App\Model\Entity\AuthenticationToken $tokenToCheck Authentication token that should match the request.
     * @param \App\Model\Entity\AuthenticationToken|string|null $sessionIdentifier The string or authentication token to match.
     * @return bool
     */
    public function checkSession(AuthenticationToken $tokenToCheck, $sessionIdentifier): bool
    {
        // The hashed session that generated the authentication token being checked
        $hashedSessionIdToCheck = $tokenToCheck->getHashedSessionId();

        if (is_string($sessionIdentifier)) {
            $hashedSessionId = $this->hash($sessionIdentifier);
        } elseif ($sessionIdentifier instanceof AuthenticationToken) {
            $hashedSessionId = $sessionIdentifier->getHashedSessionId();
        } else {
            return false;
        }

        if (empty($hashedSessionIdToCheck) || empty($hashedSessionId)) {
            return false;
        }

        return $hashedSessionIdToCheck === $hashedSessionId;
    }

    /**
     * @param string $string String to hash
     * @return false|string
     */
    public function hash(string $string)
    {
        return hash(self::HASH_ALGO, $string);
    }
}
