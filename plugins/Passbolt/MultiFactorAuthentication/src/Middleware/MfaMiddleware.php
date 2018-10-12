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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Middleware;

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Network\Exception\ForbiddenException;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

class MfaMiddleware
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequest $request, Response $response, $next)
    {
        if ($this->requiredMfaCheck($request)) {
            // Clear any dubious cookie if mfa check required
            if ($request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS)) {
                $response = $response
                    ->withCookie(MfaVerifiedCookie::clearCookie($request->is('ssl')));
            }
            // Exception if ajax or redirect
            return $response
                ->withStatus(302)
                ->withLocation($this->getVerifyUrl($request));
        }
        return $next($request, $response);
    }

    /**
     * @param ServerRequest $request
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
            '/logout'
        ];
        foreach($whitelistedPaths as $path) {
            if (substr($request->getUri()->getPath(), 0, strlen($path)) === $path) {
                return false;
            }
        }

        // Mfa not setup
        $uac = new UserAccessControl($user['role']['name'], $user['id']);
        try {
            $mfaSettings = MfaSettings::get($uac);
            if (!$mfaSettings->isVerified()) {
                return false;
            }
        } catch (RecordNotFoundException $exception) {
            return false;
        }

        // Mfa cookie is set and a valid token
        $mfa = $request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (isset($mfa)) {
            return !MfaVerifiedToken::check($uac, $mfa);
        }

        return true;
    }

    /**
     * @param ServerRequest $request
     * @return string
     */
    protected function getVerifyUrl(ServerRequest $request)
    {
        if (!$request->is('json')) {
            // @todo provider switch
            $url = '/mfa/verify/totp';
            $url .= '?redirect=' . $request->getUri()->getPath();
        } else {
            $url = '/mfa/verify/error.json';
        }
        return Router::url($url, true);
    }
}
