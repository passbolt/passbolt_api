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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Utility;

use Cake\Http\ServerRequest;
use Cake\I18n\FrozenDate;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class MfaVerifiedCookieTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaVerifiedCookie
     */
    public function testMfaVerifiedCookieGet()
    {
        $expiryAt = (new FrozenDate())->addDays(MfaVerifiedCookie::MAX_DURATION_IN_DAYS);
        $cookie = MfaVerifiedCookie::get(new ServerRequest(), 'test', $expiryAt);
        $this->assertTrue($cookie->isSecure());
        $this->assertEquals($cookie->getValue(), 'test');
        $this->assertFalse($cookie->isExpired());

        $cookie = MfaVerifiedCookie::get(new ServerRequest(), 'test', null);
        $this->assertFalse($cookie->isExpired());
        $this->assertEmpty($cookie->getExpiry());
    }

    /**
     * @group mfa
     * @group mfaVerifiedCookie
     */
    public function testMfaVerifiedCookieClearCookie()
    {
        $cookie = MfaVerifiedCookie::clearCookie(new ServerRequest());
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isExpired());
    }
}
