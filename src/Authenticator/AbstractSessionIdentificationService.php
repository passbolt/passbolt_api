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
namespace App\Authenticator;

use App\Model\Entity\AuthenticationToken;
use Authentication\AuthenticationService;
use Cake\Http\ServerRequest;

abstract class AbstractSessionIdentificationService implements SessionIdentificationServiceInterface
{
    /**
     * Checks in the request if the user is authenticated.
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return bool
     */
    protected function isAuthenticated(ServerRequest $request): bool
    {
        $authService = $request->getAttribute('authentication');
        if ($authService instanceof AuthenticationService) {
            return $authService->getResult()->isValid();
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function checkSessionId(ServerRequest $request, ?string $hashedSessionIdToCheck): bool
    {
        $hashedSessionId = $this->getHashedSessionId($request);
        if ($hashedSessionIdToCheck === null || $hashedSessionId === null) {
            return false;
        }

        return $hashedSessionIdToCheck === $hashedSessionId;
    }

    /**
     * Hashes the request session ID.
     *
     * To be overwritten for session identifiers handling the case when
     * the request's session ID is already provided hashed.
     *
     * E.g. when requesting a new refresh token, the
     * session ID is the hashed access token associated
     * to the old refresh token.
     *
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return string|null
     */
    protected function getHashedSessionId(ServerRequest $request): ?string
    {
        return hash(AuthenticationToken::HASH_ALGO, $this->getSessionId($request));
    }
}
