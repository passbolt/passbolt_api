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
 * @since         3.2.0
 */
namespace Passbolt\Locale\Middleware;

use Passbolt\Locale\Service\GetRequestLocaleService;
use Passbolt\Locale\Utility\LocaleUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LocaleMiddleware implements MiddlewareInterface
{
    /**
     * Locale Middleware.
     *
     * @param \Cake\Http\ServerRequest $request The request.
     * @param \Cake\Http\Response $handler The handler.
     * @return \Cake\Http\Response The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $this->setLocale($request);

        return $handler->handle($request);
    }

    /**
     * Set the locale based on the locale found in the
     * 1. request locale query
     * 2. account setting
     * 3. organization setting
     * 4. Default locale
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The ongoing request.
     * @return void
     */
    public function setLocale(ServerRequestInterface $request): void
    {
        $service = new GetRequestLocaleService($request);
        LocaleUtility::setLocaleIfValid($service->getLocale());
    }
}
