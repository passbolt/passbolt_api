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
namespace Passbolt\JwtAuthentication\Service\Middleware;

use Authentication\AuthenticationService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;

class JwtAuthenticationService extends AuthenticationService
{
    public const JWT_HEADER = 'Authorization';

    /**
     * @inheritDoc
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->loadIdentifier('Authentication.JwtSubject', [
            'resolver' => [
                'className' => 'Authentication.Orm',
                'finder' => 'activeNotDeletedContainRole',
            ],
        ]);
        $this->loadAuthenticator('Authentication.Jwt', [
            'header' => self::JWT_HEADER,
            'secretKey' => file_get_contents(JwksGetService::PUBLIC_KEY_PATH),
            'algorithms' => [JwtTokenCreateService::JWT_ALG],
            'returnPayload' => false,
        ]);
        $this->loadAuthenticator('Passbolt/JwtAuthentication.GpgJwt');
    }
}
