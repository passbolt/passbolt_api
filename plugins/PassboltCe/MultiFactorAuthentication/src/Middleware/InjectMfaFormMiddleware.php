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

use App\Middleware\ContainerAwareMiddlewareTrait;
use App\Middleware\UacAwareMiddlewareTrait;
use App\Utility\UserAccessControl;
use Cake\Core\ContainerInterface;
use Cake\Http\ServerRequest;
use Passbolt\MultiFactorAuthentication\Form\Duo\DuoCallbackForm;
use Passbolt\MultiFactorAuthentication\Form\Duo\DuoSetupForm;
use Passbolt\MultiFactorAuthentication\Form\Duo\DuoVerifyForm;
use Passbolt\MultiFactorAuthentication\Form\MfaFormInterface;
use Passbolt\MultiFactorAuthentication\Form\Totp\TotpSetupForm;
use Passbolt\MultiFactorAuthentication\Form\Totp\TotpVerifyForm;
use Passbolt\MultiFactorAuthentication\Form\Yubikey\YubikeySetupForm;
use Passbolt\MultiFactorAuthentication\Form\Yubikey\YubikeyVerifyForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class InjectMfaFormMiddleware implements MiddlewareInterface
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
        $uac = $this->getUacInRequest($request);
        $path = $this->getShortMfaRoute($request);

        // If the url starts with "mfa" and the user is logged in
        // Inject the appropriate MFA Form according to the route.
        if ($path && is_string($uac->getId())) {
            $mfaSettings = MfaSettings::get($uac);
            $this->services($this->getContainer($request), $path, $uac, $mfaSettings);
        }

        return $handler->handle($request);
    }

    /**
     * Inject the appropriate MFA Form according to the route.
     * This enables the mocking of complex MFA external services
     * For integration test purposes.
     *
     * @param \Cake\Core\ContainerInterface $container DIC
     * @param string $path URL path without the mfa prefix
     * @param \App\Utility\UserAccessControl $uac UAC
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaSettings $mfaSettings MFA Settings
     * @return void
     */
    public function services(
        ContainerInterface $container,
        string $path,
        UserAccessControl $uac,
        MfaSettings $mfaSettings
    ): void {
        switch ($path) {
            case 'setup/totp/start':
            case 'setup/totp':
                $concrete = TotpSetupForm::class;
                break;
            case 'verify/totp':
                $concrete = TotpVerifyForm::class;
                break;
            case 'setup/yubikey/start':
            case 'setup/yubikey':
                $concrete = YubikeySetupForm::class;
                break;
            case 'verify/yubikey':
                $concrete = YubikeyVerifyForm::class;
                break;
            case 'setup/duo/start':
            case 'setup/duo/prompt':
            case 'setup/duo':
                $concrete = DuoSetupForm::class;
                break;
            case 'setup/duo/callback':
            case 'verify/duo/callback':
                $concrete = DuoCallbackForm::class;
                break;
            case 'verify/duo':
                $concrete = DuoVerifyForm::class;
                break;
            default:
                $concrete = null;
                break;
        }

        if ($concrete) {
            $container->add(MfaFormInterface::class, $concrete)
                ->addArgument($uac)
                ->addArgument($mfaSettings)
                ->setShared(true);
        }
    }

    /**
     * Checks that the route starts by 'mfa'
     * If so, return the rest of the path, without .json extension
     * Else return null
     *
     * E.g.:
     * /mfa/verify/totp.json becomes verify/totp
     * /hello/world returns
     *
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return string|null
     */
    public function getShortMfaRoute(ServerRequest $request): ?string
    {
        $path = trim(str_replace('.json', '', $request->getUri()->getPath()), '/');
        $path = explode('/', $path);
        $isMfaRoute = (array_shift($path) === 'mfa');
        if (!$isMfaRoute) {
            return null;
        }

        return implode('/', $path);
    }
}
