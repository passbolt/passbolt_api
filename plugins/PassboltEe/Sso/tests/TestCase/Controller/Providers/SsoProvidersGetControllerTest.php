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
namespace Passbolt\Sso\Test\TestCase\Controller\Providers;

use Cake\Core\Configure;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

/**
 * @covers \Passbolt\Sso\Controller\Providers\SsoProvidersGetController
 */
class SsoProvidersGetControllerTest extends SsoIntegrationTestCase
{
    public function testSsoProvidersGetController_Error_NotLoggedIn(): void
    {
        $this->getJson('/sso/providers.json');
        $this->assertAuthenticationError();
    }

    public function testSsoProvidersGetController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->getJson('/sso/providers.json');
        $this->assertError(403);
    }

    public function testSsoProvidersGetController_Success(): void
    {
        $this->logInAsAdmin();

        $this->getJson('/sso/providers.json');

        $this->assertSuccess();
        $this->assertEqualsCanonicalizing(
            [SsoSetting::PROVIDER_AZURE, SsoSetting::PROVIDER_GOOGLE],
            $this->_responseJsonBody
        );
    }

    public function testSsoProvidersGetController_Success_Disabled(): void
    {
        $this->logInAsAdmin();
        Configure::write('passbolt.plugins.sso.providers', []);

        $this->getJson('/sso/providers.json');

        $this->assertSuccess();
        $this->assertEqualsCanonicalizing([], $this->_responseJsonBody);
    }

    public function testSsoProvidersGetController_Success_NotSupportedProviderIsOmitted(): void
    {
        $this->logInAsAdmin();
        Configure::write(
            'passbolt.plugins.sso.providers',
            [SsoSetting::PROVIDER_AZURE => true, 'facebook' => true]
        );

        $this->getJson('/sso/providers.json');

        $this->assertSuccess();
        $this->assertEqualsCanonicalizing([SsoSetting::PROVIDER_AZURE], $this->_responseJsonBody);
    }
}
