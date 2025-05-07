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

namespace Passbolt\Sso\Test\TestCase\Controller\Azure;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use League\OAuth2\Client\Token\AccessToken;
use Passbolt\Sso\Controller\AbstractSso2Stage2Controller;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;
use Passbolt\Sso\Test\Lib\SsoProviderTestTrait;
use Passbolt\Sso\Utility\Azure\Provider\AzureProvider;
use Passbolt\Sso\Utility\Azure\ResourceOwner\AzureResourceOwner;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;

class SsoAzureStage2ControllerTest extends SsoIntegrationTestCase
{
    use SsoProviderTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        EventManager::instance()->setEventList(new EventList());
    }

    public function testSsoAzureStage2Controller_Success_User(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoGetKey()
            ->userId($user->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->ip(SsoIntegrationTestCase::IP_ADDRESS)
            ->userAgent(SsoIntegrationTestCase::USER_AGENT)
            ->persist();
        $this->cookie('passbolt_sso_state', $ssoState->state);
        // Mock provider
        $mockAzureProvider = $this->getProviderMockForStage2(AzureProvider::class);
        $mockAzureProvider->method('getAccessToken')->willReturn(new AccessToken([
            'access_token' => 'foo',
        ]));
        $mockAzureProvider->method('getResourceOwner')->willReturn(new AzureResourceOwner([
            'email' => $user->get('username'),
            'nonce' => $ssoState->get('nonce'),
        ], 'email'));
        // Swap actual implementation
        SsoProviderFactory::set($mockAzureProvider);

        $this->get('/sso/azure/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        /** @var \Cake\ORM\Entity $token */
        $token = SsoAuthenticationTokenFactory::find()->where(['type' => SsoState::TYPE_SSO_GET_KEY, 'user_id' => $user->get('id')])->firstOrFail();
        $this->assertRedirectContains('/sso/login/success?token=' . $token->get('token'));
    }

    public function testSsoAzureStage2Controller_Success_Admin(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $settings = SsoSettingsFactory::make()->azure()->draft()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoGetKey()
            ->userId($admin->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->ip(SsoIntegrationTestCase::IP_ADDRESS)
            ->userAgent(SsoIntegrationTestCase::USER_AGENT)
            ->persist();
        $this->cookie('passbolt_sso_state', $ssoState->state);
        // Mock provider
        $mockAzureProvider = $this->getProviderMockForStage2(AzureProvider::class);
        $mockAzureProvider->method('getAccessToken')->willReturn(new AccessToken([
            'access_token' => 'foo',
        ]));
        $mockAzureProvider->method('getResourceOwner')->willReturn(new AzureResourceOwner([
            'email' => $admin->get('username'),
            'nonce' => $ssoState->get('nonce'),
        ], 'email'));
        // Swap actual implementation
        SsoProviderFactory::set($mockAzureProvider);

        $this->logInAs($admin);
        $this->get('/sso/azure/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        /** @var \Cake\ORM\Entity $token */
        $token = SsoAuthenticationTokenFactory::find()->where(['type' => SsoState::TYPE_SSO_SET_SETTINGS, 'user_id' => $admin->get('id')])->firstOrFail();
        $this->assertRedirectContains('/sso/login/dry-run/success?token=' . $token->get('token'));
    }

    /**
     * 400 if state is missing from url
     */
    public function testSsoAzureStage2Controller_Common_ErrorStateFromUrlMissing(): void
    {
        $this->get('/sso/azure/redirect');
        $this->assertResponseCode(400);
        $this->assertResponseContains('The state is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoAzureStage2Controller_Common_ErrorStateFromUrlInvalid(): void
    {
        $this->get('/sso/azure/redirect?state=nope');
        $this->assertResponseCode(400);
        $this->assertResponseContains('The state is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoAzureStage2Controller_Common_ErrorStateFromCookieInvalid(): void
    {
        $this->cookie('passbolt_sso_state', 'nope');
        $this->get('/sso/azure/redirect?state=' . SsoState::generate());
        $this->assertResponseCode(400);
        $this->assertResponseContains('The state is required in cookie.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoAzureStage2Controller_Common_ErrorStateMismatch(): void
    {
        $this->cookie('passbolt_sso_state', SsoState::generate());
        $this->get('/sso/azure/redirect?state=' . SsoState::generate());
        $this->assertResponseCode(400);
        $this->assertResponseContains('CSRF issue');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoAzureStage2Controller_Common_ErrorCodeMissing(): void
    {
        $state = SsoState::generate();
        $this->cookie('passbolt_sso_state', $state);
        $this->get('/sso/azure/redirect?state=' . $state);
        $this->assertResponseCode(400);
        $this->assertResponseContains('The code is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoAzureStage2Controller_Common_ErrorCodeInvalid(): void
    {
        $state = SsoState::generate();
        $this->cookie('passbolt_sso_state', $state);
        $this->get('/sso/azure/redirect?state=' . $state . '&code[not]=string');
        $this->assertResponseCode(400);
        $this->assertResponseContains('The code is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testSsoAzureStage2Controller_User_EventProviderErrorWhenAssertingResourceOwner(): void
    {
        Configure::write('passbolt.security.userIp', false);
        Configure::write('passbolt.security.userAgent', false);
        $user = UserFactory::make()->active()->persist();
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoGetKey()
            ->userId($user->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->persist();
        $this->cookie('passbolt_sso_state', $ssoState->state);

        $this->get('/sso/azure/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertEventFired(AbstractSso2Stage2Controller::EVENT_PROVIDER_ERROR_RESOURCE_OWNER);
        // More assertions can be added once we are able to mock AzureSsoService
    }

    // ADMIN TESTS

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoAzureStage2Controller_Admin_ErrorInvalidToken(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $this->logInAs($admin);

        $state = SsoState::generate();
        $this->cookie('passbolt_sso_state', $state);
        $this->get('/sso/azure/redirect?state=' . $state . '&code=' . UuidFactory::uuid());
        $this->assertResponseCode(400);
        $this->assertResponseContains('The SSO state does not exist.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if not draft settings
     */
    public function testSsoAzureStage2Controller_Admin_ErrorNotDraftSettings(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoSetSettings()
            ->userId($admin->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->persist();
        $this->logInAs($admin);
        $this->cookie('passbolt_sso_state', $ssoState->state);

        $this->get('/sso/azure/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400);
        $this->assertResponseContains('The SSO settings do not exist.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if token is invalid
     */
    public function testSsoAzureStage2Controller_Admin_ErrorInvalidTokenUseragent(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $settings = SsoSettingsFactory::make()->azure()->draft()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make(['ip' => '127.0.0.1'])
            ->withTypeSsoSetSettings()
            ->userId($admin->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->userAgent('something else')
            ->persist();
        $this->logInAs($admin);
        $this->cookie('passbolt_sso_state', $ssoState->state);

        $this->get('/sso/azure/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400);
        $this->assertResponseContains('The SSO state is invalid. User agent mismatch.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    // USERS TESTS

    /**
     * 403 if
     */
    public function testSsoAzureStage2Controller_User_ErrorIsLoggedIn(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $settings = SsoSettingsFactory::make()->azure()->draft()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoSetSettings()
            ->userId($admin->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->userAgent('something else')
            ->persist();
        $this->logInAs($user);

        $this->cookie('passbolt_sso_state', $ssoState->state);
        $this->get('/sso/azure/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());
        $this->assertResponseCode(403);
        $this->assertResponseContains('The user should not be logged in.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testSsoAzureStage2Controller_User_ErrorSsoRecoverDisabled(): void
    {
        $this->disableFeaturePlugin('SsoRecover');
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoRecover()
            ->userId($admin->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->persist();
        $this->cookie('passbolt_sso_state', $ssoState->state);

        $this->get('/sso/azure/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400, 'SsoRecover plugin is disabled');
    }
}
