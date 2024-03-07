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
 * @since         3.1.0
 */
namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\Utility\Hash;
use Cake\Utility\Security;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfProtectionMiddleware extends \Cake\Http\Middleware\CsrfProtectionMiddleware
{
    public const PASSBOLT_SECURITY_CSRF_PROTECTION_ACTIVE_CONFIG = 'passbolt.security.csrfProtection.active';

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \Cake\Http\ServerRequest $request */
        $this->makeCsrfCookieSecureIfRequestIsSsl($request);

        return parent::process($request, $handler);
    }

    /**
     * @inheritDoc
     */
    protected function isHexadecimalToken(string $token): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function _verifyToken(string $token): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function createToken(): string
    {
        return hash('sha512', Security::randomBytes(static::TOKEN_VALUE_LENGTH), false);
    }

    /**
     * Skip Csrf protection.
     *
     * @param \Cake\Http\ServerRequest $request The request
     * @return bool result
     */
    public function skipCsrfProtection(ServerRequest $request): bool
    {
        $plugins = Configure::read('passbolt.plugins');
        $controller = $request->getParam('controller', 'Error');

        $unlockedActions = Configure::read("passbolt.security.csrfProtection.unlockedActions.$controller", []);
        foreach ($plugins as $plugin) {
            $pluginsUnlockedActions = Hash::extract($plugin, "security.csrfProtection.unlockedActions.$controller");
            if (!empty($pluginsUnlockedActions)) {
                $unlockedActions = array_merge($unlockedActions, $pluginsUnlockedActions);
            }
        }

        if (!Configure::read(self::PASSBOLT_SECURITY_CSRF_PROTECTION_ACTIVE_CONFIG)) {
            return true;
        }
        if (in_array($request->getParam('action'), $unlockedActions)) {
            return true;
        }

        return false;
    }

    /**
     * Read if ssl is required.
     *
     * @param \Cake\Http\ServerRequest $request Server request
     * @return void
     * @TODO deprecate this method in v5: secure should be isSsl OR PASSBOLT_SECURITY_COOKIES_SECURE_CONFIG true
     * @deprecated since v4.2.0 use isSslOrCookiesSecure() instead, remove this in v5.
     * @see AbstractSecureCookieService::isSslOrCookiesSecure()
     */
    public function makeCsrfCookieSecureIfRequestIsSsl(ServerRequest $request): void
    {
        $this->_config['secure'] = $request->is('https');
    }
}
