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
 * @since         3.4.0
 */
namespace Passbolt\JwtAuthentication\Test\TestCase\Authenticator;

use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Test\Utility\JwtAuthenticationIntegrationTestCase;

class JwtAuthenticatorTest extends JwtAuthenticationIntegrationTestCase
{
    /**
     * This is a CakePHP related issue reported by Cure53
     * The HS256 algorithm is enabled by default by Cake's JwtAuthenticator
     * The attacker can change the algorithm field of the JWT header from RS256
     * to HS256 and misuse the RSA public key as HMAC secret key.
     * With the knowledge of another user's id, the attacker can
     * issue arbitrary valid tokens and authenticate as other users.
     *
     * @see JwtAuthenticator::__construct()
     * @see JwtAuthenticationService::authenticate()
     */
    public function testJwtAuthenticatorTest_Cure53_Hack_Using_HS256_Algo()
    {
        $attackedUser = UserFactory::make()->persist();

        $url = Configure::read('App.fullBaseUrl');
        $userId = $attackedUser->id;
        $publicKey = (new JwksGetService())->getRawPublicKey();

        $head = '{"typ":"JWT","alg":"HS256"}';
        $t = FrozenTime::now()->addHours(3)->toUnixString();
        $body = '{"iss":"' . $url . '","sub":"' . $userId . '","exp":' . $t . '}';
        $msg = $this->urlsafeB64Encode($head) . '.' . $this->urlsafeB64Encode($body);
        $hash = \hash_hmac('SHA256', $msg, $publicKey, true);
        $hackedToken = $msg . '.' . $this->urlsafeB64Encode($hash) . "\n";

        $this->setJwtTokenInHeader($hackedToken);
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseError();
    }

    public function urlsafeB64Encode($input)
    {
        return str_replace('=', '', \strtr(\base64_encode($input), '+/', '-_'));
    }
}
