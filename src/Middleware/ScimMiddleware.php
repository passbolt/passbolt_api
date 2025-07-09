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
 * @since         4.1.0
 */
namespace App\Middleware;

use Cake\Utility\Text;
use Passbolt\Scim\Log\ScimLog;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * ScimMiddleware class
 */
class ScimMiddleware implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $isEnabled = true; // @todo: add configuration to enable/disable api logs
        $controller = $request->getAttribute('params')['controller'] ?? '';
        $isScim = strtolower($controller) === 'scim';
        $requestId = Text::uuid();
        if ($isEnabled && $isScim) {
            ScimLog::info(sprintf('`%s` Request uri (%s): %s', $requestId, $request->getEnv('REQUEST_METHOD'), $request->getUri()));
            if ($request->is(['post', 'put', 'patch'])) {
                ScimLog::info(sprintf("`%s` Request body: \n%s", $requestId, json_encode($request->getParsedBody())));
            }
        }

        /** @var \Cake\Http\Response $response */
        $response = $handler->handle($request);
        if ($isEnabled && $isScim) {
            ScimLog::info(sprintf("`%s` Response body: \n%s", $requestId, $response->getBody()));
        }

        return $response;
    }
}
