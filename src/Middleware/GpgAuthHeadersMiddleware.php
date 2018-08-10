<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Middleware;

use App\Auth\GpgAuthenticate;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GpgAuthHeadersMiddleware
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, $next)
    {
        $response = $next($request, $response);
        $allowedHeaders = GpgAuthenticate::HTTP_HEADERS_WHITELIST;

        $response = $response
            ->withHeader('X-GPGAuth-Version', '1.3.0')
            ->withHeader('X-GPGAuth-Login-URL', '/auth/login')
            ->withHeader('X-GPGAuth-Logout-URL', '/auth/logout')
            ->withHeader('X-GPGAuth-Verify-URL', '/auth/verify')
            ->withHeader('X-GPGAuth-Pubkey-URL', '/auth/verify.json')
            ->withHeader('Access-Control-Expose-Headers', $allowedHeaders);

        return $response;
    }
}
