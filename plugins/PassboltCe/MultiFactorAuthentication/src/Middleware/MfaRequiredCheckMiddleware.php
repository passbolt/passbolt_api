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
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Event\UpdateMfaTokenSessionIdOnRefreshTokenCreated;
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
     * Documents in the request, if an MFA check is required,
     * according to the user settings and the absence of a
     * valid MFA token in the cookies.
     */
    public const IS_MFA_CHECK_NOT_REQUIRED_ATTRIBUTE = 'is_mfa_check_not_required';
    public const IS_MFA_TOKEN_VALID_ATTRIBUTE = 'is_mfa_token_valid';

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
        if ($this->isMfaCheckRequired($request)) {
            // Exception if ajax or redirect
            return (new Response())
                ->withStatus(302)
                ->withLocation($this->getVerifyUrl($request));
        } else {
            $request = $request->withAttribute(self::IS_MFA_CHECK_NOT_REQUIRED_ATTRIBUTE, true);
        }
        if ($request->getCookieCollection()->has(MfaVerifiedCookie::MFA_COOKIE_ALIAS)) {
            $request = $request->withAttribute(self::IS_MFA_TOKEN_VALID_ATTRIBUTE, true);
        }

        // Listen to the creation of refresh tokens
        // This listener must be activated after the AuthenticationMiddleware
        // And is therefore activated here.
        TableRegistry::getTableLocator()
            ->get('AuthenticationTokens')
            ->getEventManager()
            ->on(new UpdateMfaTokenSessionIdOnRefreshTokenCreated());

        return $handler->handle($request);
    }

    /**
     * @param \Cake\Http\ServerRequest $request request
     * @return bool
     */
    public function isMfaCheckRequired(ServerRequest $request): bool
    {
        if ($this->isRouteWhiteListed($request)) {
            return false;
        }

        $uac = $this->getUacInRequest($request);
        // Return false if user is not authenticated
        if (empty($uac->getId())) {
            return false;
        }

        $isMfaAuthenticationRequiredService = new IsMfaAuthenticationRequiredService();
        /** @var \App\Authenticator\SessionIdentificationServiceInterface $sessionService */
        $sessionService = $this->getContainer($request)->get(SessionIdentificationServiceInterface::class);

        return $isMfaAuthenticationRequiredService->isMfaCheckRequired(
            $request,
            MfaSettings::get($uac),
            $uac,
            $sessionService
        );
    }

    /**
     * @param \Cake\Http\ServerRequest $request request
     * @return string
     */
    protected function getVerifyUrl(ServerRequest $request): string
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

    /**
     * @param \Cake\Http\ServerRequest $request request
     * @return bool
     */
    protected function isRouteWhiteListed(ServerRequest $request): bool
    {
        // Do not redirect on mfa setup or check page
        // same goes for authentication pages
        $whitelistedPaths = [
            '/login',
            '/auth/login',
            '/auth/jwt/login',
            '/mfa/verify',
            '/auth/logout',
            '/logout',
        ];
        foreach ($whitelistedPaths as $path) {
            if (substr($request->getUri()->getPath(), 0, strlen($path)) === $path) {
                return true;
            }
        }

        return false;
    }
}
