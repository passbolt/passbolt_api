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

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Middleware\ContainerAwareMiddlewareTrait;
use App\Middleware\UacAwareMiddlewareTrait;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Service\IsMfaAuthenticationRequiredService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MfaRequiredCheckMiddleware implements MiddlewareInterface
{
    use ContainerAwareMiddlewareTrait;
    use UacAwareMiddlewareTrait;

    /**
     * Mfa Required check Middleware
     * Checks if the MFA is required for the user authenticated
     * and if the provided MFA token is valid.
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
        /** @var \Cake\Http\ServerRequest $request */
        if ($this->isMfaCheckRequired($request)) {
            /** @var \Cake\Http\Response $response */
            $response = $handler->handle($request);
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
    public function isMfaCheckRequired(ServerRequest $request): bool
    {
        $uac = $this->getUacInRequest($request);
        // Return false if user is not authenticated
        if (empty($uac)) {
            return false;
        }

        $mfaSettings = MfaSettings::get($uac);

        $isMfaAuthenticationRequiredService = new IsMfaAuthenticationRequiredService();

        return $isMfaAuthenticationRequiredService->isMfaCheckRequired(
            $request,
            $mfaSettings,
            $uac,
            $this->getContainer($request)->get(SessionIdentificationServiceInterface::class)
        );
    }

    /**
     * @param \Cake\Http\ServerRequest $request request
     * @return string
     */
    protected function getVerifyUrl(ServerRequest $request)
    {
        $uac = $this->getUacInRequest($request);
        $mfaSettings = MfaSettings::get($uac);
        if ($request->is('json')) {
            $url = '/mfa/verify/error.json';
        } else {
            $url = $mfaSettings->getDefaultVerifyUrl(false);
            $url .= '?redirect=' . $request->getUri()->getPath();
        }

        return Router::url($url, true);
    }
}
