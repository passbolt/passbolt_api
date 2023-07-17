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

namespace Passbolt\Sso\Test\TestCase\Utility\Google\Provider;

use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Passbolt\Sso\Test\Lib\GoogleProviderTestTrait;
use Passbolt\Sso\Utility\Provider\BaseOauth2Provider;

/**
 * @see \Passbolt\Sso\Utility\Google\Provider\GoogleProvider
 */
class GoogleProviderTest extends AppTestCase
{
    use GoogleProviderTestTrait;

    /**
     * @inheritDoc
     */
    public function setup(): void
    {
        $seleniumSsoConfig = Configure::read('passbolt.selenium.sso.active');

        if (!isset($seleniumSsoConfig) || !$seleniumSsoConfig) {
            $this->markTestSkipped('Selenium SSO is set to inactive, skipping tests.');
        }
    }

    public function testSsoGoogleProvider_ExtendsBaseOauth2Provider(): void
    {
        $this->assertInstanceOf(BaseOauth2Provider::class, $this->getDummyGoogleProvider());
    }

    public function testSsoGoogleProvider_getBaseAuthorizationUrl(): void
    {
        $provider = $this->getDummyGoogleProvider();
        $url = $provider->getBaseAuthorizationUrl();
        $this->assertStringContainsString('accounts.google.com', $url);
        $this->assertStringContainsString('oauth2/v2/auth', $url);
    }

    public function testSsoGoogleProvider_getBaseAccessTokenUrl(): void
    {
        $provider = $this->getDummyGoogleProvider();
        $url = $provider->getBaseAccessTokenUrl([]);
        $this->assertStringContainsString('token', $url);
    }

    public function testSsoGoogleProvider_getOpenIdConfigurationUri(): void
    {
        $provider = $this->getDummyGoogleProvider();
        $url = $provider->getOpenIdConfigurationUri();
        $this->assertStringContainsString('accounts.google.com/.well-known/openid-configuration', $url);
    }
}
