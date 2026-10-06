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
namespace Passbolt\Scim\Middleware;

use Authentication\Authenticator\ResultInterface;
use Authentication\Authenticator\UnauthenticatedException;
use Cake\Http\Response;
use Passbolt\Scim\Authenticator\ScimTokenAuthenticator;
use Passbolt\Scim\Utility\Object\ScimErrorResponse;
use Passbolt\Scim\Utility\ScimConstants;
use Passbolt\Scim\Utility\ScimTools;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Renders an unauthenticated SCIM request as a SCIM-standard 401 response.
 */
class ScimUnauthenticatedResponseMiddleware implements MiddlewareInterface
{
    public const DEFAULT_DETAIL = 'Authentication failed.';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!ScimTools::isScimApiRequest($request)) {
            return $handler->handle($request);
        }

        try {
            return $handler->handle($request);
        } catch (UnauthenticatedException) {
            return $this->toScimUnauthenticated($request);
        }
    }

    /**
     * Build a 401 response as per the SCIM Error schema.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function toScimUnauthenticated(ServerRequestInterface $request): ResponseInterface
    {
        $exception = new UnauthenticatedException($this->resolveDetail($request));
        $error = new ScimErrorResponse($exception, 401);
        $body = json_encode($error->toSCIM(), JSON_PRETTY_PRINT);

        return (new Response())
            ->withStatus(401)
            ->withType(ScimConstants::CONTENT_TYPE)
            ->withStringBody($body);
    }

    /**
     * Read the failure reason from the authentication result.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @return string
     */
    private function resolveDetail(ServerRequestInterface $request): string
    {
        $result = $request->getAttribute('authenticationResult');
        if (!$result instanceof ResultInterface) {
            return self::DEFAULT_DETAIL;
        }

        $detail = $result->getErrors()[ScimTokenAuthenticator::RESULT_ERROR_DETAIL] ?? null;

        return is_string($detail) ? $detail : self::DEFAULT_DETAIL;
    }
}
