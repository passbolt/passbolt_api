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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Middleware;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\Exception\MissingDatasourceConfigException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Response;
use Cake\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WebInstallerMiddleware implements MiddlewareInterface
{
    /**
     * Webinstaller Middleware.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $uri = $request->getRequestTarget();
        $targetInstallPage = preg_match('/^\/install/', $uri);

        if (!self::isConfigured() && !$targetInstallPage) {
            return (new Response())
                ->withStatus(302)
                ->withLocation(Router::url('/install', true));
        }
        if (self::isConfigured() && $targetInstallPage) {
            throw new ForbiddenException();
        }

        return $handler->handle($request);
    }

    /**
     * Check if passbolt is configured.
     * We consider it as configured if at least one of the default datasource property is not empty.
     *
     * @return bool
     */
    public static function isConfigured()
    {
        if (Configure::read('passbolt.webInstaller.configured') !== null) {
            return Configure::read('passbolt.webInstaller.configured');
        }
        try {
            $connection = ConnectionManager::get('default')->config();

            return !empty($connection) && !empty($connection['database']);
        } catch (MissingDatasourceConfigException $exception) {
            return false;
        }
    }
}
