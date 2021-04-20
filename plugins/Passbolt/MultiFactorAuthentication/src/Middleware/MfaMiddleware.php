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
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MfaMiddleware implements MiddlewareInterface
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Utility\MfaSettings
     */
    private $mfaSettings;

    /**
     * Mfa Middleware.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        /** @var \Cake\Http\ServerRequest $request */
        if ($this->requiredMfaCheck($request)) {
            /** @var \Cake\Http\Response $response */
            $response = $handler->handle($request);
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

        return $handler->handle($request);
    }

    /**
     * @param \Cake\Http\ServerRequest $request request
     * @return bool
     */
    protected function requiredMfaCheck(ServerRequest $request)
    {
        // User is not logged in
        $user = $request->getSession()->read('Auth');
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
