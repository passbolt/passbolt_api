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
 * @since         4.0.0
 */
namespace App\Service\Cookie;

use Cake\Core\Configure;
use Cake\Http\Cookie\Cookie;
use Cake\Http\ServerRequest;
use DateTimeInterface;

abstract class AbstractSecureCookieService
{
    public const PASSBOLT_SECURITY_COOKIES_SECURE_CONFIG = 'passbolt.security.cookies.secure';

    /**
     * Read in the config and in the request is ssl is required.
     *
     * Set to true in the configs by default.
     *
     * @param \Cake\Http\ServerRequest $request Server request
     * @return bool
     */
    public static function isSslOrCookiesSecure(ServerRequest $request): bool
    {
        return Configure::read(self::PASSBOLT_SECURITY_COOKIES_SECURE_CONFIG) || $request->is('ssl');
    }

    /**
     * The path may be adjusted, for example to match
     * organization domain
     *
     * @param string|null $path cookie path
     * @return string
     */
    abstract protected function getPath(?string $path): string;

    /**
     * Create secure cookie instance.
     *
     * @param string $name Name of the cookie.
     * @param array|string $value Value of the cookie to set.
     * @param string|null $path Path.
     * @param \DateTime|\DateTimeImmutable|null $expiresAt Expiration time and date
     * @return \Cake\Http\Cookie\Cookie
     */
    public function create(string $name, $value = '', ?string $path = '', ?DateTimeInterface $expiresAt = null): Cookie
    {
        $cookie = (new Cookie($name))
            ->withPath($this->getPath($path))
            ->withValue($value)
            ->withSecure(true)
            ->withHttpOnly(true);

        if (is_null($expiresAt)) {
            return $cookie;
        }

        return $cookie->withExpiry($expiresAt);
    }

    /**
     * Create secure expired cookie instance.
     *
     * @param string $name Name of the cookie.
     * @param array|string $value Value of the cookie to set.
     * @param string|null $path Path.
     * @return \Cake\Http\Cookie\Cookie
     */
    public function createExpired(string $name, $value = '', ?string $path = ''): Cookie
    {
        return $this->create($name, $value, $path)->withExpired();
    }
}
