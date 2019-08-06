<?php
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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;

use App\Utility\UuidFactory;
use Cake\Http\Cookie\Cookie;
use DateTime;
use DateTimeInterface;

class MfaVerifiedCookie
{
    const MFA_COOKIE_ALIAS = 'passbolt_mfa';
    const MAX_DURATION = self::MAX_DURATION_IN_DAYS . ' days';
    const MAX_DURATION_IN_DAYS = 30;

    /**
     * Get a new mfa verification cookie
     *
     * @param string $token token
     * @param DateTimeInterface|null $expirationDate Expiration date for the token
     * @param bool $ssl if ssl is required
     * @return Cookie|static
     */
    public static function get(string $token, DateTimeInterface $expirationDate = null, bool $ssl = true)
    {
        $mfaCookie = (new Cookie(self::MFA_COOKIE_ALIAS))
            ->withValue($token)
            ->withPath('/')
            ->withHttpOnly(true)
            ->withSecure($ssl);

        if ($expirationDate !== null) {
            $mfaCookie = $mfaCookie
                ->withExpiry($expirationDate);
        }

        return $mfaCookie;
    }

    /**
     * Return an expired cookie to clear it
     *
     * @param bool $ssl if ssl is required
     * @return Cookie
     */
    public static function clearCookie(bool $ssl = true)
    {
        $mfaCookie = (new Cookie(self::MFA_COOKIE_ALIAS))
            ->withValue(UuidFactory::uuid())
            ->withExpiry(new DateTime('yesterday'))
            ->withPath('/')
            ->withHttpOnly(true)
            ->withSecure($ssl);

        return $mfaCookie;
    }
}
