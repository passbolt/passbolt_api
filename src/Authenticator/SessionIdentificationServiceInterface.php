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
 * @since         3.3.0
 */
namespace App\Authenticator;

use App\Model\Entity\AuthenticationToken;
use Cake\Http\ServerRequest;

/**
 * Interface SessionIdentificationServiceInterface
 *
 * The session ID may be the session ID if loggin from the browser,
 * but also the access token if logged in with JWT. This interface
 * enables the management of session ID in stateless authentication.
 */
interface SessionIdentificationServiceInterface
{
    /**
     * Get the user session identifier.
     *
     * This can be a string, like a session ID or an access token,
     * or an authentication token, e.g. when requesting a new refresh token.
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return \App\Model\Entity\AuthenticationToken|string|null
     */
    public function getSessionIdentifier(ServerRequest $request);

    /**
     * Compares a provided authentication token with the session identification.
     *
     * @param \Cake\Http\ServerRequest $request Server Request
     * @param \App\Model\Entity\AuthenticationToken $tokenToValidate Hashed session, typically found in a token's data
     * @return bool
     */
    public function checkAuthenticationToken(ServerRequest $request, AuthenticationToken $tokenToValidate): bool;
}
