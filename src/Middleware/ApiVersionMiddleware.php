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
 * @since         3.12.0
 */
namespace App\Middleware;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiVersionMiddleware implements MiddlewareInterface
{
    /**
     * Throws a bad request if the version passed in the request is not supported.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     * @throws \Cake\Http\Exception\BadRequestException if the API version provided is deprecated
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \Cake\Http\ServerRequest $request */
        if ($request->is('json')) {
            $version = $this->getApiVersion($request);
            if ($version === 'v1') {
                throw new BadRequestException('API v1 support is deprecated in this version.');
            }
        }

        return $handler->handle($request);
    }

    /**
     * Get the request api version.
     *
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return string
     */
    public function getApiVersion(ServerRequest $request): string
    {
        $apiVersion = $request->getQuery('api-version');
        // Default to v2 in v3
        if (!isset($apiVersion) || !is_string($apiVersion)) {
            return 'v2';
        }

        // Reformat api-version
        if ($apiVersion === '1') {
            return 'v1';
        }
        if ($apiVersion === '2') {
            return 'v2';
        }

        // Return what is given
        return $apiVersion;
    }
}
