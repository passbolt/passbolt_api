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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;

use App\Service\Cookie\AbstractSecureCookieService;
use App\Utility\UuidFactory;
use Cake\Http\Cookie\Cookie;
use Cake\Http\ServerRequest;
use DateTimeInterface;

class MfaVerifiedCookie
{
    public const MFA_COOKIE_ALIAS = 'passbolt_mfa';
    public const MAX_DURATION = self::MAX_DURATION_IN_DAYS . ' days';
    public const MAX_DURATION_IN_DAYS = 30;

    /**
     * Get a new mfa verification cookie
     *
     * @param \Cake\Http\ServerRequest $request server request
     * @param string $token token
     * @param \DateTimeInterface|null $expirationDate Expiration date for the token
     * @return \Cake\Http\Cookie\Cookie
     */
    public static function get(
        ServerRequest $request,
        string $token,
        ?DateTimeInterface $expirationDate = null
    ): Cookie {
        /** @var \Cake\Http\Cookie\Cookie $mfaCookie */
        $mfaCookie = (new Cookie(self::MFA_COOKIE_ALIAS))
            ->withValue($token)
            ->withPath('/')
            ->withHttpOnly(true)
            ->withSecure(AbstractSecureCookieService::isSslOrCookiesSecure($request));

        if ($expirationDate !== null) {
            $mfaCookie = $mfaCookie ->withExpiry($expirationDate);
        }

        return $mfaCookie;
    }

    /**
     * Return an expired cookie to clear it
     *
     * @param \Cake\Http\ServerRequest $request server request
     * @return \Cake\Http\Cookie\Cookie
     */
    public static function clearCookie(ServerRequest $request): Cookie
    {
        return (new Cookie(self::MFA_COOKIE_ALIAS))
            ->withValue(UuidFactory::uuid())
            ->withExpired()
            ->withPath('/')
            ->withHttpOnly(true)
            ->withSecure(AbstractSecureCookieService::isSslOrCookiesSecure($request));
    }
}
