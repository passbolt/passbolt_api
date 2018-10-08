<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;

use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use DateTime;
use Cake\Core\Configure;
use Cake\Http\Cookie\Cookie;

class MfaVerifiedCookie
{
    const MFA_COOKIE_ALIAS = 'passbolt_mfa';
    const MIN_DURATION = '24 hours';
    const MAX_DURATION = '30 days';

    /**
     * @param UserAccessControl $uac
     * @param bool $remember
     * @return Cookie|static
     */
    static public function get(UserAccessControl $uac, string $token, bool $remember = false)
    {
        if ($remember) {
            $expiry = new DateTime(self::MAX_DURATION);
        } else {
            $expiry = new DateTime(self::MIN_DURATION);
        }

        $mfaCookie = (new Cookie(self::MFA_COOKIE_ALIAS))
            ->withValue($token)
            ->withExpiry($expiry)
            ->withPath('/')
            ->withHttpOnly(true)
            ->withSecure(Configure::read('passbolt.ssl.force'));

        return $mfaCookie;
    }

    /**
     * Return an expired cookie to clear things up
     *
     * @return Cookie
     */
    static public function clearCookie()
    {
        $mfaCookie = (new Cookie(self::MFA_COOKIE_ALIAS))
            ->withValue(UuidFactory::uuid())
            ->withExpiry(new DateTime('yesterday'))
            ->withPath('/')
            ->withHttpOnly(true)
            ->withSecure(Configure::read('passbolt.ssl.force'));

        return $mfaCookie;
    }
}