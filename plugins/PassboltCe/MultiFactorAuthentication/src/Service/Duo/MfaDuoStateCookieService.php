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

namespace Passbolt\MultiFactorAuthentication\Service\Duo;

use Cake\Http\Cookie\Cookie;
use Cake\Http\ServerRequest;
use Cake\I18n\FrozenTime;
use Cake\Validation\Validation;

/**
 * Class MfaDuoStateCookieService
 */
class MfaDuoStateCookieService
{
    /**
     * Passbolt temporary cookie to verify Duo authentication authenticity
     *
     * @var string
     */
    public const MFA_COOKIE_DUO_STATE = 'passbolt_duo_state';

    /**
     * Passbolt temporary cookie expiry in minutes
     *
     * @var int
     */
    public const MFA_COOKIE_DUO_STATE_EXPIRY_IN_MINUTES = 10;

    /**
     * Create a Duo state cookie.
     *
     * @param string $token Authentication token's token
     * @param bool $secure Whether to set the cookie as secure
     * @return \Cake\Http\Cookie\Cookie The created cookie containing the Duo state value
     */
    public function createDuoStateCookie(string $token, bool $secure): Cookie
    {
        if (!Validation::uuid($token)) {
            throw new \InvalidArgumentException('The authentication token should be a valid UUID.');
        }

        return (new Cookie(self::MFA_COOKIE_DUO_STATE))
            ->withValue($token)
            ->withPath('/')
            ->withHttpOnly(true)
            ->withSecure($secure)
            ->withExpiry((new FrozenTime())->addMinutes(self::MFA_COOKIE_DUO_STATE_EXPIRY_IN_MINUTES));
    }

    /**
     * Read the Duo state cookie.
     *
     * @param \Cake\Http\ServerRequest $request Server request
     * @return array|string|null The cookie value
     */
    public function readDuoStateCookieValue(ServerRequest $request)
    {
        return $request->getCookie(self::MFA_COOKIE_DUO_STATE);
    }
}
