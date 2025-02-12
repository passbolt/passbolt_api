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
 * @since         4.11.1
 */
namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AssertFullBaseUrlMiddleware implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     * @throws \Cake\Http\Exception\InternalErrorException If config value is invalid
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $enforceFullBaseUrl = Configure::read('passbolt.security.fullBaseUrlEnforce', false);
        $originalFullBaseUrl = Configure::read('passbolt.originalFullBaseUrl', '');
        $isValidFullBaseUrl = is_string($originalFullBaseUrl) && $originalFullBaseUrl !== '';

        if (!$enforceFullBaseUrl) {
            $warnEmptyFullBaseUrl = Configure::read('passbolt.security.emptyFullBaseUrlWarn', true);
            if ($warnEmptyFullBaseUrl && !$isValidFullBaseUrl) {
                Log::warning('Your FullBaseUrl configuration is not safe. See healthcheck for more information.');
            }

            return $handler->handle($request);
        }

        if ($isValidFullBaseUrl) {
            return $handler->handle($request);
        }

        throw new InternalErrorException(__('The `{0}` configuration must be a valid non-empty string.', 'App.fullBaseUrl')); // phpcs:ignore
    }
}
