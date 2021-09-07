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
namespace Passbolt\JwtAuthentication\Test\Utility;

use App\Test\Factory\UserFactory;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Passbolt\JwtAuthentication\Service\Middleware\JwtAuthenticationService;

trait JwtAuthTestTrait
{
    /**
     * Sets the given JWT token in the request header.
     *
     * @param string $token
     * @return void
     */
    public function setJwtTokenInHeader(string $token): void
    {
        $this->configRequest([
            'headers' => [JwtAuthenticationService::JWT_HEADER => 'Bearer ' . $token],
        ]);
    }

    /**
     * Creates a JWT token and sets it in the request header.
     *
     * @param string|null $userId
     * @return string
     */
    public function createJwtTokenAndSetInHeader(?string $userId = null): string
    {
        if ($userId === null) {
            $userId = UserFactory::make()->user()->persist()->id;
        }
        $token = (new JwtTokenCreateService())->createToken($userId);
        $this->setJwtTokenInHeader($token);

        return $userId;
    }
}
