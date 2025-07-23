<?php
declare(strict_types=1);

namespace Passbolt\Scim\Authenticator;

use Authentication\AuthenticationService;
use Authentication\Authenticator\ResultInterface;
use Cake\Error\Debugger;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Psr\Http\Message\ServerRequestInterface;

class ScimAuthenticationService extends AuthenticationService
{

    /**
     * @param ServerRequestInterface $request
     * @return ResultInterface
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        $this->loadAuthenticator('Authentication.Token', [
            'header' => 'Authorization',
            'tokenPrefix' => 'Bearer'
        ]);

        $this->loadIdentifier('Authentication.Token', [
            'resolver' => [
                'className' => 'Passbolt/Scim.Scim',
            ],
            'hashAlgorithm' => 'sha256',
            'tokenField' => 'secret_token',
        ]);

        return parent::authenticate($request);
    }
}
