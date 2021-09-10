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
 * @since         3.3.0
 */
namespace Passbolt\MultiFactorAuthentication\Middleware;

use App\Middleware\UacAwareMiddlewareTrait;
use App\Utility\UserAccessControl;
use Cake\Http\ServerRequest;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SetMfaSettingsInRequestMiddleware implements MiddlewareInterface
{
    use UacAwareMiddlewareTrait;

    public const MFA_SETTINGS_REQUEST_ATTRIBUTE = 'mfa_settings_request_attribute';

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
        $uac = $this->getUacInRequest($request);
        if (isset($uac)) {
            $request = $this->setMfaSettingsInRequestAttribute($request, $uac);
        }

        return $handler->handle($request);
    }

    /**
     * If the user is logged, fetch the MFA Settings
     * and pass them in the request as attribute for
     * other MFA Middlewares and the MFA Controllers
     *
     * @param \Cake\Http\ServerRequest $request Server Request
     * @param \App\Utility\UserAccessControl $uac UAC
     * @return \Cake\Http\ServerRequest
     * @see MfaController::initialize()
     */
    public function setMfaSettingsInRequestAttribute(ServerRequest $request, UserAccessControl $uac): ServerRequest
    {
        return $request->withAttribute(self::MFA_SETTINGS_REQUEST_ATTRIBUTE, MfaSettings::get($uac));
    }
}
