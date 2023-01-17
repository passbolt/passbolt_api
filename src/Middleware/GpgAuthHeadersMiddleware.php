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
 * @since         2.0.0
 */
namespace App\Middleware;

use App\Authenticator\GpgAuthenticator;
use Passbolt\JwtAuthentication\Service\Middleware\JwtRequestDetectionService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GpgAuthHeadersMiddleware implements MiddlewareInterface
{
    /**
     * Set GPGAuth Headers.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The response.
     * @return \Psr\Http\Message\ResponseInterface A response
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        /** @var \Cake\Http\Response $response */
        $response = $handler->handle($request);
        $allowedHeaders = GpgAuthenticator::HTTP_HEADERS_WHITELIST;

        if ($request->getAttribute(JwtRequestDetectionService::IS_JWT_AUTH_REQUEST)) {
            return $response;
        }

        $response = $response
            ->withHeader('X-GPGAuth-Version', '1.3.0')
            ->withHeader('X-GPGAuth-Login-URL', '/auth/login')
            ->withHeader('X-GPGAuth-Logout-URL', '/auth/logout')
            ->withHeader('X-GPGAuth-Verify-URL', '/auth/verify')
            ->withHeader('X-GPGAuth-Pubkey-URL', '/auth/verify.json')
            ->withAddedHeader('Access-Control-Expose-Headers', $allowedHeaders);

        $authenticationHeaders = $request->getAttribute('authenticationResult')->getErrors() ?? [];
        foreach ($authenticationHeaders as $header => $msg) {
            $response = $response->withHeader($header, $msg);
        }

        return $response;
    }
}
