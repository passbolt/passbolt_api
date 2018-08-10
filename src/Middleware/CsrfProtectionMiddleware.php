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

use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Utility\Hash;

class CsrfProtectionMiddleware extends \Cake\Http\Middleware\CsrfProtectionMiddleware
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequest $request, Response $response, $next)
    {
        $plugins = Configure::read('passbolt.plugins');
        $controller = $request->getParam('controller');
        $action = $request->getParam('action');

        $unlockedActions = Configure::read("passbolt.security.csrfProtection.unlockedActions.$controller", []);
        foreach ($plugins as $plugin) {
            $pluginsUnlockedActions = Hash::extract($plugin, "security.csrfProtection.unlockedActions.$controller", []);
            if (!empty($pluginsUnlockedActions)) {
                $unlockedActions = array_merge($unlockedActions, $pluginsUnlockedActions);
            }
        }

        if (Configure::read('passbolt.security.csrfProtection.active') && !in_array($action, $unlockedActions)) {
            return parent::__invoke($request, $response, $next);
        }

        return $next($request, $response);
    }
}
