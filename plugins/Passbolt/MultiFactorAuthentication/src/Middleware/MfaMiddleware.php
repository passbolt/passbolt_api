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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Middleware;

use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

class MfaMiddleware
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Utility\MfaSettings
     */
    private $mfaSettings;

    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequest $request, Response $response, $next)
    {
        if ($this->requiredMfaCheck($request)) {
            // Clear any dubious cookie if mfa check required
            if ($request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS)) {
                $secure = Configure::read('passbolt.security.cookies.secure') || $request->is('ssl');
                $response = $response
                    ->withCookie(MfaVerifiedCookie::clearCookie($secure));
            }
            // Exception if ajax or redirect
            return $response
                ->withStatus(302)
                ->withLocation($this->getVerifyUrl($request));
        }

        return $next($request, $response);
    }

    /**
     * @param \Cake\Http\ServerRequest $request request
     * @return bool
     */
    protected function requiredMfaCheck(ServerRequest $request)
    {
        // User is not logged in
        $user = $request->getSession()->read('Auth.User');
        if (!isset($user)) {
            return false;
        }

        // Do not redirect on mfa setup or check page
        // same goes for authentication pages
        $whitelistedPaths = [
            '/mfa/verify',
            '/auth/logout',
            '/logout',
        ];
        foreach ($whitelistedPaths as $path) {
            if (substr($request->getUri()->getPath(), 0, strlen($path)) === $path) {
                return false;
            }
        }

        // Mfa not enabled for org or user
        $uac = new UserAccessControl($user['role']['name'], $user['id']);
        $this->mfaSettings = MfaSettings::get($uac);
        $providers = $this->mfaSettings->getEnabledProviders();
        if (!count($providers)) {
            return false;
        }

        // Mfa cookie is set and a valid token
        $mfa = $request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (isset($mfa)) {
            $sessionId = $request->getSession()->id();

            return !MfaVerifiedToken::check($uac, $mfa, $sessionId);
        }

        return true;
    }

    /**
     * @param \Cake\Http\ServerRequest $request request
     * @return string
     */
    protected function getVerifyUrl(ServerRequest $request)
    {
        if (!$request->is('json')) {
            $url = $this->mfaSettings
                ->getDefaultVerifyUrl(false);
            $url .= '?redirect=' . $request->getUri()->getPath();
        } else {
            $url = '/mfa/verify/error.json';
        }

        return Router::url($url, true);
    }
}
