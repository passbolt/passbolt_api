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
 * @since         5.1.0
 */

namespace Passbolt\Sso\Utility\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;

class SsoProviderFactory
{
    private static ?AbstractOauth2Provider $provider = null;

    /**
     * Build an instance of SSO provider with given options.
     *
     * @param string $provider Provider class name.
     * @param array $options Options.
     * @param array $collaborators An array of collaborators that may be used to override this provider's default behavior.
     * @return \League\OAuth2\Client\Provider\AbstractProvider Returns an instance of SSO provider.
     */
    public static function create(string $provider, array $options = [], array $collaborators = []): AbstractProvider
    {
        if (self::$provider !== null) {
            return self::$provider;
        }

        return new $provider($options, $collaborators);
    }

    /**
     * Set SSO Provider in the factory.
     *
     * @param \Passbolt\Sso\Utility\Provider\AbstractOauth2Provider $provider SSO Provider.
     * @return void
     */
    public static function set(AbstractOauth2Provider $provider): void
    {
        self::$provider = $provider;
    }

    /**
     * Clear/reset internal state of the factory.
     *
     * @return void
     */
    public static function clear(): void
    {
        self::$provider = null;
    }
}
