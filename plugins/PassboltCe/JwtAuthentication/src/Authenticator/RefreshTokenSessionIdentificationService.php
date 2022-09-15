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
namespace Passbolt\JwtAuthentication\Authenticator;

use App\Authenticator\AbstractSessionIdentificationService;
use App\Model\Entity\AuthenticationToken;
use Cake\Http\ServerRequest;

class RefreshTokenSessionIdentificationService extends AbstractSessionIdentificationService
{
    /**
     * @var \App\Model\Entity\AuthenticationToken
     */
    private $refreshToken;

    /**
     * On the refresh token endpoint, the session ID is read as
     * the access token associated to the provided refresh token.
     *
     * @param \App\Model\Entity\AuthenticationToken $refreshToken Refresh Token
     */
    public function __construct(AuthenticationToken $refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * @inheritDoc
     */
    public function getSessionIdentifier(ServerRequest $request): ?AuthenticationToken
    {
        if (!$this->isAuthenticated($request)) {
            return null;
        }

        return $this->refreshToken;
    }
}
