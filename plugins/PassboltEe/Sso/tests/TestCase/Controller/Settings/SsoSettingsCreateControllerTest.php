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
use Cake\Validation\Validation;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
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
            ],
        ];

        $this->postJson('/sso/settings.json', $data);

        $this->assertSuccess();
        $body = $this->_responseJsonBody;
        $this->assertTrue(Validation::uuid($body->id));
        $this->assertEquals(SsoSetting::PROVIDER_AZURE, $body->provider);
        $this->assertEquals((new SsoActiveProvidersGetService())->get(), $body->providers);
        $this->assertEquals(SsoSetting::STATUS_DRAFT, $body->status);
        $this->assertEquals($data['data'], (array)$body->data);
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
        // Make sure prompt is optional
        $this->assertObjectNotHasAttribute('email_claim', $body->data);
        $this->assertObjectNotHasAttribute('prompt', $body->data);
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
                'email_claim' => 'upn',
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
}
