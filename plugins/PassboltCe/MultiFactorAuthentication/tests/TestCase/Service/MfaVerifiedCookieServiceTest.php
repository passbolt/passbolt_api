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
 * @since         3.10.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service;

use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\JwtAuthentication\Authenticator\JwtSessionIdentificationService;
use Passbolt\MultiFactorAuthentication\Service\MfaVerifiedCookieService;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class MfaVerifiedCookieServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public function testMfaVerifiedCookieService_createDuoMfaVerifiedCookie()
    {
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl($user->get('role')->name, $user->get('id'), $user->get('username'));
        $request = new ServerRequest();
        $sessionService = new JwtSessionIdentificationService('accessToken');
        $service = new MfaVerifiedCookieService();
        $cookie = $service->createDuoMfaVerifiedCookie($uac, $sessionService, $request);

        $this->assertEquals(AuthenticationTokenFactory::count(), 1);
        $this->assertEquals($cookie->getName(), MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }
}
