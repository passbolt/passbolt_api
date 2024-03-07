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
 * @since         4.6.0
 */

namespace Passbolt\Sso\Test\TestCase\Service\Sso\OAuth2;

use App\Service\Cookie\DefaultSecureCookieService;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Service\Sso\OAuth2\SsoOAuth2Service;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

/**
 * @covers \Passbolt\Sso\Service\Sso\OAuth2\SsoOAuth2Service
 */
class SsoOAuth2ServiceTest extends SsoIntegrationTestCase
{
    public function testSsoOAuth2Service_Success(): void
    {
        $this->markTestIncomplete();
    }

    public function testSsoOAuth2Service_Error_SsoSslCafileConfigInvalid(): void
    {
        SsoSettingsFactory::make()->oauth2()->active()->persist();
        // set invalid value in the config
        Configure::write('passbolt.security.sso.sslCafile', true);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Invalid value provided in `passbolt.security.sso.sslCafile` config');

        (new SsoOAuth2Service(new DefaultSecureCookieService(), null));
    }

    public function testSsoOAuth2Service_Error_SsoSslCafileConfigFileNotExist(): void
    {
        SsoSettingsFactory::make()->oauth2()->active()->persist();
        // set invalid value in the config
        Configure::write('passbolt.security.sso.sslCafile', '/path/to/invalid.crt');

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Provided root CA file does not exist');

        (new SsoOAuth2Service(new DefaultSecureCookieService(), null));
    }
}
