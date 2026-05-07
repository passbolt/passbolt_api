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
 * @since         3.9.0
 */
namespace Passbolt\Sso\Test\TestCase\Controller\Settings;

use Cake\Core\Configure;
use Cake\Validation\Validation;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Service\Providers\SsoActiveProvidersGetService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

/**
 * @covers \Passbolt\Sso\Controller\Settings\SsoSettingsViewCurrentController
 */
class SsoSettingsViewCurrentControllerTest extends SsoIntegrationTestCase
{
    public function testSsoSettingsViewCurrentController_SuccessAzure(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();
        $this->logInAsAdmin();

        $this->getJson('/sso/settings/current.json');

        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $activeProviders = (new SsoActiveProvidersGetService())->get();
        $this->assertTrue(Validation::uuid($body->id));
        $this->assertEquals(SsoSetting::PROVIDER_AZURE, $body->provider);
        $this->assertEquals($activeProviders, $body->providers);
        $this->assertEquals(SsoSetting::STATUS_ACTIVE, $body->status);
        $this->assertTrue(!isset($body->data));
    }

    public function testSsoSettingsViewCurrentController_SuccessProvidersDisabled(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();
        $this->logInAsAdmin();
        Configure::write('passbolt.plugins.sso.providers', []);

        $this->getJson('/sso/settings/current.json');

        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $activeProviders = (new SsoActiveProvidersGetService())->get();
        $this->assertTrue(Validation::uuid($body->id));
        $this->assertEquals(SsoSetting::PROVIDER_AZURE, $body->provider);
        $this->assertEquals($activeProviders, $body->providers);
        $this->assertEquals(SsoSetting::STATUS_ACTIVE, $body->status);
        $this->assertTrue(!isset($body->data));
    }

    public function testSsoSettingsViewCurrentController_SuccessAzureWithContain(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();
        $this->logInAsAdmin();

        $this->getJson('/sso/settings/current.json?contain[data]=1');

        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $activeProviders = (new SsoActiveProvidersGetService())->get();
        $this->assertTrue(Validation::uuid($body->id));
        $this->assertEquals(SsoSetting::PROVIDER_AZURE, $body->provider);
        $this->assertEquals($activeProviders, $body->providers);
        $this->assertEquals(SsoSetting::STATUS_ACTIVE, $body->status);
        // Assert data properties
        $this->assertEquals('https://login.microsoftonline.com', $body->data->url);
        $this->assertEquals(SsoSettingsAzureDataForm::PROMPT_LOGIN, $body->data->prompt);
        $this->assertTrue(Validation::uuid($body->data->client_id));
        $this->assertTrue(Validation::uuid($body->data->tenant_id));
        $this->assertTrue(is_string($body->data->client_secret));
        $this->assertTrue(is_string($body->data->client_secret_expiry));
        $this->assertObjectHasAttribute('email_claim', $body->data);
        $this->assertSame(SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL, $body->data->email_claim);
        $this->assertSame(SsoSettingsAzureDataForm::AZURE_LOGIN_HINT_ENABLED, $body->data->login_hint);
    }

    public function testSsoSettingsViewCurrentController_SuccessNotLoggedIn(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();
        $this->getJson('/sso/settings/current.json?contain[data]=1');
        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $this->assertEquals(SsoSetting::PROVIDER_AZURE, $body->provider);
        $this->assertTrue(!isset($body->providers));
        $this->assertTrue(!isset($body->status));
        $this->assertTrue(!isset($body->data));
        $this->assertTrue(!isset($body->created));
        $this->assertTrue(!isset($body->created_by));
        $this->assertTrue(!isset($body->modified));
        $this->assertTrue(!isset($body->modified_by));
    }

    public function testSsoSettingsViewCurrentController_SuccessNotAdmin(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();
        $this->logInAsUser();
        $this->getJson('/sso/settings/current.json?contain[data]=1');
        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $this->assertEquals(SsoSetting::PROVIDER_AZURE, $body->provider);
        $this->assertTrue(!isset($body->providers));
        $this->assertTrue(!isset($body->status));
        $this->assertTrue(!isset($body->data));
        $this->assertTrue(!isset($body->created));
        $this->assertTrue(!isset($body->created_by));
        $this->assertTrue(!isset($body->modified));
        $this->assertTrue(!isset($body->modified_by));
    }

    public function testSsoSettingsViewCurrentController_SuccessDraft(): void
    {
        SsoSettingsFactory::make()->azure()->draft()->persist();
        $this->logInAsUser();
        $this->getJson('/sso/settings/current.json?contain[data]=1');
        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $this->assertNull($body->provider);
    }

    public function testSsoSettingsViewCurrentController_SuccessDraft2(): void
    {
        SsoSettingsFactory::make()->azure()->draft()->persist();
        $this->logInAsUser();
        $this->getJson('/sso/settings/current.json?contain[data]=0');
        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $this->assertNull($body->provider);
    }

    public function testSsoSettingsViewCurrentController_SuccesEmptyUser(): void
    {
        $this->logInAsUser();
        $this->getJson('/sso/settings/current.json');
        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $this->assertNull($body->provider);
    }

    public function testSsoSettingsViewCurrentController_SuccessEmptyAdmin(): void
    {
        $this->logInAsAdmin();
        Configure::write(
            'passbolt.plugins.sso.providers',
            [
                SsoSetting::PROVIDER_AZURE => true,
                SsoSetting::PROVIDER_GOOGLE => true,
                SsoSetting::PROVIDER_OAUTH2 => false,
            ]
        );
        $this->getJson('/sso/settings/current.json');

        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $this->assertNull($body->provider);
        $this->assertEqualsCanonicalizing([SsoSetting::PROVIDER_AZURE, SsoSetting::PROVIDER_GOOGLE], $body->providers);
    }

    public function testSsoSettingsViewCurrentController_SuccessPingOne(): void
    {
        SsoSettingsFactory::make()->pingone()->active()->persist();
        $this->logInAsAdmin();

        $this->getJson('/sso/settings/current.json');

        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $activeProviders = (new SsoActiveProvidersGetService())->get();
        $this->assertTrue(Validation::uuid($body->id));
        $this->assertEquals(SsoSetting::PROVIDER_PINGONE, $body->provider);
        $this->assertEquals($activeProviders, $body->providers);
        $this->assertEquals(SsoSetting::STATUS_ACTIVE, $body->status);
        $this->assertTrue(!isset($body->data));
    }

    public function testSsoSettingsViewCurrentController_SuccessPingOneWithContain(): void
    {
        SsoSettingsFactory::make()->pingone()->active()->persist();
        $this->logInAsAdmin();

        $this->getJson('/sso/settings/current.json?contain[data]=1');

        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $activeProviders = (new SsoActiveProvidersGetService())->get();
        $this->assertTrue(Validation::uuid($body->id));
        $this->assertEquals(SsoSetting::PROVIDER_PINGONE, $body->provider);
        $this->assertEquals($activeProviders, $body->providers);
        $this->assertEquals(SsoSetting::STATUS_ACTIVE, $body->status);
        // Assert PingOne data properties
        $this->assertEquals('https://auth.pingone.com', $body->data->url);
        $this->assertEquals('d1b2c3a4-e5f6-7890-abcd-ef1234567890', $body->data->environment_id);
        $this->assertEquals('a1b2c3d4-e5f6-7890-abcd-ef1234567890', $body->data->client_id);
        $this->assertTrue(is_string($body->data->client_secret));
        $this->assertEquals('/.well-known/openid-configuration', $body->data->openid_configuration_path);
        $this->assertEquals('openid email profile', $body->data->scope);
        $this->assertObjectHasAttribute('email_claim', $body->data);
        $this->assertSame(SsoSetting::PINGONE_EMAIL_CLAIM_EMAIL, $body->data->email_claim);
    }

    public function testSsoSettingsViewCurrentController_SuccessPingOneNotAdmin(): void
    {
        SsoSettingsFactory::make()->pingone()->active()->persist();
        $this->logInAsUser();
        $this->getJson('/sso/settings/current.json?contain[data]=1');
        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $this->assertEquals(SsoSetting::PROVIDER_PINGONE, $body->provider);
        $this->assertTrue(!isset($body->providers));
        $this->assertTrue(!isset($body->status));
        $this->assertTrue(!isset($body->data));
        $this->assertTrue(!isset($body->created));
        $this->assertTrue(!isset($body->created_by));
        $this->assertTrue(!isset($body->modified));
        $this->assertTrue(!isset($body->modified_by));
    }

    public function testSsoSettingsViewCurrentController_SuccessEmptyAdminAllProvidersDisabled(): void
    {
        $this->logInAsAdmin();
        Configure::write('passbolt.plugins.sso.providers', []);

        $this->getJson('/sso/settings/current.json');

        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $this->assertNull($body->provider);
        $this->assertEqualsCanonicalizing([], $body->providers);
    }
}
