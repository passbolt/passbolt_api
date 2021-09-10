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
namespace Passbolt\JwtAuthentication\Authenticator;

use App\Authenticator\SessionIdentificationServiceInterface;
use Authentication\AuthenticationService;
use Passbolt\JwtAuthentication\Service\Middleware\JwtAuthenticationService;
use Psr\Http\Message\ServerRequestInterface;

class JwtSessionIdentificationService implements SessionIdentificationServiceInterface
{
    /**
     * @inheritDoc
     */
    public function getSessionId(ServerRequestInterface $request): ?string
    {
        if (!$this->isAuthenticated($request)) {
            return null;
        }

        return $request->getHeaderLine(JwtAuthenticationService::JWT_HEADER);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return bool
     */
    protected function isAuthenticated(ServerRequestInterface $request): bool
    {
        $authService = $request->getAttribute('authentication');
        if ($authService instanceof AuthenticationService) {
            return $authService->getResult()->isValid();
        }

        return false;
    }
}
