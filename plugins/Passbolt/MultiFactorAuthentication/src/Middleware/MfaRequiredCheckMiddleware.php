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
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Service\IsMfaAuthenticationRequiredService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
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
        /** @var \Passbolt\MultiFactorAuthentication\Utility\MfaSettings|null $mfaSettings */
        $mfaSettings = $request->getAttribute(SetMfaSettingsInRequestMiddleware::MFA_SETTINGS_REQUEST_ATTRIBUTE);
        if (!empty($mfaSettings) && $this->isMfaCheckRequired($request, $mfaSettings)) {
            $request = $this->clearAuthenticationOnInvalidMfaCookie($request, $mfaSettings);
        }

        return $handler->handle($request);
    }

    /**
     * @param \Cake\Http\ServerRequest $request request
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaSettings $mfaSettings MFA settings
     * @return bool
     */
    public function isMfaCheckRequired(ServerRequest $request, MfaSettings $mfaSettings): bool
    {
        $uac = $this->getUacInRequest($request);
        // Return false if user is not authenticated
        if (empty($uac)) {
            return false;
        }

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
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaSettings $mfaSettings MFA Settings
     * @return string
     */
    protected function getVerifyUrl(ServerRequest $request, MfaSettings $mfaSettings)
    {
        if ($request->is('json')) {
            $url = '/mfa/verify/error.json';
        } else {
            $url = $mfaSettings->getDefaultVerifyUrl(false);
            $url .= '?redirect=' . $request->getUri()->getPath();
        }

        return Router::url($url, true);
    }

    /**
     * If a user was found authenticated but with invalid MFA cookie
     * the identity is marked as invalid (cleared), the redirect URL are
     *  set according to the MFA provider requested.
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaSettings $mfaSettings MFA settings
     * @return \Cake\Http\ServerRequest Request without authenticated user
     * @see MfaController::_invalidateMfaCookie()
     */
    protected function clearAuthenticationOnInvalidMfaCookie(
        ServerRequest $request,
        MfaSettings $mfaSettings
    ): ServerRequest {
        /** @var \Authentication\AuthenticationService $authService */
        $authService = $request->getAttribute('authentication');

        // Update the unauthenticated redirection url
        $authService->setConfig([
            'unauthenticatedRedirect' => $this->getVerifyUrl($request, $mfaSettings),
            'queryParam' => null,
        ]);

        // Reset the authentication
        $result = $authService->clearIdentity($request, new Response());
        /** @var \Cake\Http\ServerRequest $request */
        $request = $result['request'];

        return $request->withAttribute('authentication', $authService);
    }
}
