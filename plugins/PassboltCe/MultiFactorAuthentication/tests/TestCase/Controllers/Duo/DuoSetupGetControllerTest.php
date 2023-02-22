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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Duo;

use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\OrganizationSettingFactory;
use Passbolt\MultiFactorAuthentication\Controller\Duo\DuoSetupGetController;
use Passbolt\MultiFactorAuthentication\Form\Duo\DuoSetupForm;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoSetupGetControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetDuo
     */
    public function testMfaSetupGetDuoNotAuthenticated()
    {
        $this->get('/mfa/setup/duo.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetDuo
     */
    public function testMfaSetupGetDuoJsonNotAllowed()
    {
        $this->logInAsUser();
        $this->get('/mfa/setup/duo.json?api-version=v2');
        $this->assertResponseError('not available');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetDuo
     */
    public function testMfaSetupGetDuoAlreadyConfigured()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_DUO);
        $this->get('/mfa/setup/duo');
        $this->assertResponseOk();
        $this->assertResponseContains('Duo multi-factor authentication is enabled');
        $this->assertSame(1, OrganizationSettingFactory::count());
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetDuo
     */
    public function testMfaSetupGetDuoOrgSettingsNotEnabled()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_TOTP);
        $this->get('/mfa/setup/duo');
        $this->assertResponseError();
        $this->assertResponseContains('This authentication provider is not enabled for your organization.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     * @group mfaSetupGetDuo
     */
    public function testMfaSetupGetDuoAccountSettingsEmpty()
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $this->get('/mfa/setup/duo');
        $this->assertResponseOk();
    }

    public function testMfaSetupGetDuo_Valid_Form()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $this->mockValidMfaFormInterface(DuoSetupForm::class, $this->makeUac($user));
        $this->get('/mfa/setup/duo?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertResponseContains('/mfa/setup/duo/prompt?redirect=' . DuoSetupGetController::DUO_SETUP_REDIRECT_PATH);
        $this->assertResponseContains('How does it work?');
        $this->assertResponseContains('sidebar-help');
        $this->assertResponseContains('Learn more');
        $this->assertSame(0, AuthenticationTokenFactory::count());
    }

    public function testMfaSetupGetDuo_Invalid_Form()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $this->mockInvalidMfaFormInterface(DuoSetupForm::class, $this->makeUac($user));
        $this->get('/mfa/setup/duo?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertSame(0, AuthenticationTokenFactory::count());
    }
}
