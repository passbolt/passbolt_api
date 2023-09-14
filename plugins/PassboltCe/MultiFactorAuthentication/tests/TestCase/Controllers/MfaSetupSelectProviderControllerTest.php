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
 * @since         4.3.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers;

use Passbolt\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaSetupSelectProviderControllerTest extends MfaIntegrationTestCase
{
    public function testMfaSetupSelectProviderController_MFA_Not_Possible()
    {
        $this->logInAsUser();
        $this->get('https://foo.com/mfa/setup/select');
        $this->assertResponseOk();
        $this->assertResponseContains('Sorry no multi factor authentication is enabled for this organization.');
    }

    public function testMfaSetupSelectProviderController_MFA_Not_On_HTTPS_Is_Allowed()
    {
        $this->logInAsUser();
        $this->get('http://foo.local/mfa/setup/select');
        $this->assertResponseOk();
        $this->assertResponseContains('Sorry no multi factor authentication is enabled for this organization.');
    }

    public function testMfaSetupSelectProviderController_MFA_With_MultipleProviders()
    {
        $this->logInAsUser();
        MfaOrganizationSettingFactory::make()->duoWithTotp()->persist();
        $this->get('https://foo.com/mfa/setup/select');
        $this->assertResponseOk();
        $this->assertResponseContains('start');
        $this->assertResponseContains('Duo MFA');
        $this->assertResponseContains('img/third_party/duo.svg');
        $this->assertResponseNotContains('Yubikey OTP');
    }

    public function testMfaSetupSelectProviderController_MFA_With_MultipleProviders_Json_Https()
    {
        $this->logInAsUser();
        MfaOrganizationSettingFactory::make()->duoWithTotp()->persist();
        $this->getJson('https://foo.local/mfa/setup/select.json');
        $this->assertResponseOk();
        $response = $this->getResponseBodyAsArray();
        $expectedResponse[MfaSettings::ORG_SETTINGS] = [MfaSettings::PROVIDER_TOTP => true, MfaSettings::PROVIDER_DUO => true, MfaSettings::PROVIDER_YUBIKEY => false];
        $expectedResponse[MfaSettings::ACCOUNT_SETTINGS] = [MfaSettings::PROVIDER_TOTP => false, MfaSettings::PROVIDER_DUO => false, MfaSettings::PROVIDER_YUBIKEY => false];
        $this->assertSame($response, $expectedResponse);
    }

    public function testMfaSetupSelectProviderController_MFA_With_MultipleProviders_Json_Http()
    {
        $this->logInAsUser();
        MfaOrganizationSettingFactory::make()->duoWithTotp()->persist();
        $this->getJson('http://foo.local/mfa/setup/select.json');
        $this->assertResponseOk();
        $response = $this->getResponseBodyAsArray();
        $expectedResponse[MfaSettings::ORG_SETTINGS] = [MfaSettings::PROVIDER_TOTP => true, MfaSettings::PROVIDER_DUO => true, MfaSettings::PROVIDER_YUBIKEY => false];
        $expectedResponse[MfaSettings::ACCOUNT_SETTINGS] = [MfaSettings::PROVIDER_TOTP => false, MfaSettings::PROVIDER_DUO => false, MfaSettings::PROVIDER_YUBIKEY => false];
        $this->assertSame($response, $expectedResponse);
    }
}
