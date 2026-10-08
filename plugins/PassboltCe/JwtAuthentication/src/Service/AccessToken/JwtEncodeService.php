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
 * @since         5.17.0
 */
namespace Passbolt\JwtAuthentication\Service\AccessToken;

use Firebase\JWT\JWT;

class JwtEncodeService extends JwtAbstractService
{
    protected string $keyPath = JwtTokenCreateService::JWT_SECRET_KEY_PATH;

    /**
     * @param array $claims Claims.
     * @return string
     * @throws \Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException When the private key is not readable.
     */
    public function encode(array $claims): string
    {
        return JWT::encode($claims, $this->readKeyFileContent(), JwtTokenCreateService::JWT_ALG);
    }
}
