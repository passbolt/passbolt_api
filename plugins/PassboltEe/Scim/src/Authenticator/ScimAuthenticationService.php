<?php
declare(strict_types=1);

namespace Passbolt\Scim\Authenticator;

use Authentication\AuthenticationService;
use Authentication\Authenticator\ResultInterface;
use Psr\Http\Message\ServerRequestInterface;

class ScimAuthenticationService extends AuthenticationService
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Authentication\Authenticator\ResultInterface
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        $this->loadAuthenticator('Authentication.Token', [
            'header' => 'Authorization',
            'tokenPrefix' => 'Bearer',
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
