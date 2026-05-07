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

use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use Cake\Validation\Validation;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Middleware\SsoEndpointsSecurityMiddleware;
use Passbolt\Sso\Model\Dto\SsoSettingsPingOneDataDto;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Service\Providers\SsoActiveProvidersGetService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

/**
 * @covers \Passbolt\Sso\Controller\Settings\SsoSettingsCreateController
 */
class SsoSettingsCreateControllerTest extends SsoIntegrationTestCase
{
    /**
     * Azure provider
     */
    public function testSsoSettingsCreateController_Success_Azure(): void
    {
        $this->logInAsAdmin();
        $data = [
            'provider' => SsoSetting::PROVIDER_AZURE,
            'data' => [
                'url' => 'https://login.microsoftonline.com',
                'client_id' => UuidFactory::uuid(),
                'tenant_id' => UuidFactory::uuid(),
                'client_secret' => UuidFactory::uuid(),
                'client_secret_expiry' => Chronos::now()->addDays(365),
                'prompt' => SsoSettingsAzureDataForm::PROMPT_LOGIN,
                'email_claim' => SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL,
                'login_hint' => SsoSettingsAzureDataForm::AZURE_LOGIN_HINT_DISABLED,
            ],
        ];

        $this->postJson('/sso/settings.json', $data);

        $this->assertSuccess();
        $body = $this->getResponseBodyAsArray();
        $this->assertTrue(Validation::uuid($body['id']));
        $this->assertEquals(SsoSetting::PROVIDER_AZURE, $body['provider']);
        $this->assertEquals((new SsoActiveProvidersGetService())->get(), $body['providers']);
        $this->assertEquals(SsoSetting::STATUS_DRAFT, $body['status']);
        $data['data']['client_secret_expiry'] = $data['data']['client_secret_expiry']->toDateTimeString();
        $this->assertEquals(json_decode(json_encode($data['data']), true), $body['data']);
    }

    public function testSsoSettingsCreateController_ErrorNotLoggedIn(): void
    {
        $this->postJson('/sso/settings.json', []);
        $this->assertAuthenticationError();
    }

    public function testSsoSettingsCreateController_ErrorNotAdmin(): void
    {
        $this->logInAsUser();
        $this->postJson('/sso/settings.json', []);
        $this->assertError(403);
    }

    public function testSsoSettingsCreateController_ErrorValidation(): void
    {
        $this->logInAsAdmin();
        $data = [
            'provider' => '🔥',
        ];
        $this->postJson('/sso/settings.json', $data);
        $this->assertError(400);
    }

    public function testSsoSettingsCreateController_ErrorValidationData_Azure(): void
    {
        $this->logInAsAdmin();
        $data = [
            'provider' => SsoSetting::PROVIDER_AZURE,
            'data' => [
                'url' => '🔥',
                'client_id' => '🔥',
                'tenant_id' => '🔥',
                'client_secret_expiry' => '🔥',
            ],
        ];

        $this->postJson('/sso/settings.json', $data);

        $this->assertError(400);
        $body = $this->_responseJsonBody;
        $this->assertObjectHasAttribute('url', $body->data);
        $this->assertObjectHasAttribute('tenant_id', $body->data);
        $this->assertObjectHasAttribute('client_id', $body->data);
        $this->assertObjectHasAttribute('client_secret', $body->data);
        $this->assertObjectHasAttribute('client_secret_expiry', $body->data);
        // Make sure "advanced settings" fields are optional
        $this->assertObjectNotHasAttribute('email_claim', $body->data);
        $this->assertObjectNotHasAttribute('prompt', $body->data);
        $this->assertObjectNotHasAttribute('login_hint', $body->data);
    }

    public function testSsoSettingsCreateController_ErrorValidationData_AzureInvalidValues(): void
    {
        $this->logInAsAdmin();
        $data = [
            'provider' => SsoSetting::PROVIDER_AZURE,
            'data' => [
                'url' => 'https://login.microsoftonline.com',
                'client_id' => UuidFactory::uuid(),
                'tenant_id' => UuidFactory::uuid(),
                'client_secret' => UuidFactory::uuid(),
                'client_secret_expiry' => Chronos::now()->addDays(365),
                'prompt' => 'foo',
                'email_claim' => 'bar',
                'login_hint' => 'baz',
            ],
        ];

        $this->postJson('/sso/settings.json', $data);

        $this->assertError(400);
        $body = $this->_responseJsonBody;
        $this->assertTrue(isset($body->data->prompt));
        $this->assertEquals(
            'The prompt should be one of the following: login, none.',
            $body->data->prompt->inList
        );
        $this->assertEquals(
            'The email claim should be one of the following: email, preferred_username, upn.',
            $body->data->email_claim->inList
        );
    }

    /**
     * Google provider
     */
    public function testSsoSettingsCreateController_SuccessGoogle(): void
    {
        $this->logInAsAdmin();
        $googleCreds = SsoSettingsFactory::getGoogleCredentials();
        $data = [
            'provider' => SsoSetting::PROVIDER_GOOGLE,
            'data' => [
                'client_id' => $googleCreds['client_id'],
                'client_secret' => $googleCreds['client_secret'],
            ],
        ];

        $this->postJson('/sso/settings.json', $data);

        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $this->assertTrue(Validation::uuid($body->id));
        $this->assertEquals(SsoSetting::PROVIDER_GOOGLE, $body->provider);
        $this->assertEquals((new SsoActiveProvidersGetService())->get(), $body->providers);
        $this->assertEquals(SsoSetting::STATUS_DRAFT, $body->status);
        $this->assertEquals($data['data'], (array)$body->data);
    }

    /**
     * AD FS provider
     */
    public function testSsoSettingsCreateController_Success_ADFS(): void
    {
        $this->logInAsAdmin();
        $data = [
            'provider' => SsoSetting::PROVIDER_ADFS,
            'data' => [
                'url' => 'https://sso.passbolt.test',
                'client_id' => UuidFactory::uuid(),
                'client_secret' => UuidFactory::uuid(),
                'openid_configuration_path' => '/.well-known/openid-configuration',
                'scope' => 'openid email profile',
                'email_claim' => SsoSetting::ADFS_EMAIL_CLAIM_UPN,
            ],
        ];

        $this->postJson('/sso/settings.json', $data);

        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $this->assertTrue(Validation::uuid($body->id));
        $this->assertEquals(SsoSetting::PROVIDER_ADFS, $body->provider);
        $this->assertEquals((new SsoActiveProvidersGetService())->get(), $body->providers);
        $this->assertEquals(SsoSetting::STATUS_DRAFT, $body->status);
        $this->assertEquals($data['data'], (array)$body->data);
    }

    public function testSsoSettingsCreateController_Error_EndpointsDisabled(): void
    {
        Configure::write(SsoEndpointsSecurityMiddleware::SECURITY_CONFIG_KEY, true);
        $this->logInAsAdmin();
        $data = [
            'provider' => SsoSetting::PROVIDER_ADFS,
            'data' => [
                'url' => 'https://sso.passbolt.test',
                'client_id' => UuidFactory::uuid(),
                'client_secret' => UuidFactory::uuid(),
                'openid_configuration_path' => '/.well-known/openid-configuration',
                'scope' => 'openid email profile',
                'email_claim' => SsoSetting::ADFS_EMAIL_CLAIM_UPN,
            ],
        ];

        $this->postJson('/sso/settings.json', $data);

        $this->assertForbiddenError('SSO settings edit endpoints are disabled');
    }

    /**
     * PingOne provider
     */
    public function testSsoSettingsCreateController_Success_PingOne(): void
    {
        $this->logInAsAdmin();
        $data = [
            'provider' => SsoSetting::PROVIDER_PINGONE,
            'data' => [
                'url' => 'https://auth.pingone.com',
                'environment_id' => UuidFactory::uuid(),
                'client_id' => UuidFactory::uuid(),
                'client_secret' => UuidFactory::uuid(),
                'email_claim' => SsoSetting::PINGONE_EMAIL_CLAIM_EMAIL,
            ],
        ];

        $this->postJson('/sso/settings.json', $data);

        $this->assertSuccess();
        $body = $this->getResponseBodyAsArray();
        $this->assertTrue(Validation::uuid($body['id']));
        $this->assertEquals(SsoSetting::PROVIDER_PINGONE, $body['provider']);
        $this->assertEquals((new SsoActiveProvidersGetService())->get(), $body['providers']);
        $this->assertEquals(SsoSetting::STATUS_DRAFT, $body['status']);
        // The DTO adds default openid_configuration_path and scope
        $expectedData = $data['data'];
        $expectedData['openid_configuration_path'] = SsoSettingsPingOneDataDto::DEFAULT_OPENID_CONFIGURATION_PATH;
        $expectedData['scope'] = SsoSettingsPingOneDataDto::DEFAULT_SCOPE;
        $this->assertEquals($expectedData, $body['data']);
    }

    public function testSsoSettingsCreateController_ErrorValidationData_PingOne(): void
    {
        $this->logInAsAdmin();
        $data = [
            'provider' => SsoSetting::PROVIDER_PINGONE,
            'data' => [
                'url' => '🔥',
            ],
        ];

        $this->postJson('/sso/settings.json', $data);

        $this->assertError(400);
        $body = $this->_responseJsonBody;
        $this->assertObjectHasAttribute('url', $body->data);
        $this->assertObjectHasAttribute('environment_id', $body->data);
        $this->assertObjectHasAttribute('client_id', $body->data);
        $this->assertObjectHasAttribute('client_secret', $body->data);
        // scope and openid_configuration_path are not validated (removed from form)
        $this->assertObjectNotHasAttribute('scope', $body->data);
        $this->assertObjectNotHasAttribute('openid_configuration_path', $body->data);
    }

    public function testSsoSettingsCreateController_ErrorValidationData_PingOneInvalidUrl(): void
    {
        $this->logInAsAdmin();
        $data = [
            'provider' => SsoSetting::PROVIDER_PINGONE,
            'data' => [
                'url' => 'https://evil.com',
                'environment_id' => UuidFactory::uuid(),
                'client_id' => UuidFactory::uuid(),
                'client_secret' => UuidFactory::uuid(),
                'email_claim' => SsoSetting::PINGONE_EMAIL_CLAIM_EMAIL,
            ],
        ];

        $this->postJson('/sso/settings.json', $data);

        $this->assertError(400);
        $body = $this->_responseJsonBody;
        $this->assertObjectHasAttribute('url', $body->data);
        $this->assertEquals(
            'The URL must be a valid PingOne authentication domain.',
            $body->data->url->isPingOneUrl
        );
    }
}
