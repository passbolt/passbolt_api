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

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;

trait JwtTestTrait
{
    public function assertAccessTokenIsValid(string $accessToken, string $userId)
    {
        $publicKey = file_get_contents((new JwksGetService())->getKeyPath());
        $res = JWT::decode($accessToken, new Key($publicKey, 'RS256'));
        $this->assertSame($userId, $res->sub);
    }
}
