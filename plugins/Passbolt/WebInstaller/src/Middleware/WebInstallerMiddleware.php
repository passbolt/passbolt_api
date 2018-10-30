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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Middleware;

use Cake\Core\Configure;

class WebInstallerMiddleware
{
    /**
     * Redirect to the webinstaller if passbolt is not configured and the user is trying to access any entry points
     * which is not a webinstaller entry point.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return \Psr\Http\Message\ResponseInterface A response
     */
    public function __invoke($request, $response, $next)
    {
        $uri = $request->getRequestTarget();
        $targetInstallPage = preg_match('/^\/install/', $uri);

        if (!PASSBOLT_IS_CONFIGURED && !$targetInstallPage) {
            return $response
                ->withStatus(302)
                ->withLocation('/install');
        } elseif (PASSBOLT_IS_CONFIGURED && $targetInstallPage) {
            return $response
                ->withStatus(302)
                ->withLocation('/');
        }

        return $next($request, $response);
    }

    /**
     * Check if passbolt is configured.
     * We consider it as configured if at least one of the default datasource property is not empty.
     *
     * @return bool
     */
    public static function isConfigured()
    {
        if (defined('TEST_IS_RUNNING') && TEST_IS_RUNNING) {
            return true;
        }

        $datasourceUsername = Configure::read('Datasources.default.username');
        $datasourcePassword = Configure::read('Datasources.default.password');
        $datasourceDatabase = Configure::read('Datasources.default.database');
        if (!empty($datasourceUsername) || !empty($datasourcePassword) || !empty($datasourceDatabase)) {
            return true;
        }

        return false;
    }
}
