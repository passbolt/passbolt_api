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
 * @since         5.11.0
 */

namespace Passbolt\Sso\Test\TestCase\Controller\PingOne;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use League\OAuth2\Client\Token\AccessToken;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;
use Passbolt\Sso\Utility\PingOne\Provider\PingOneProvider;
use Passbolt\Sso\Utility\PingOne\ResourceOwner\PingOneResourceOwner;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;

/**
 * @covers \Passbolt\Sso\Controller\PingOne\SsoPingOneStage2Controller
 */
class SsoPingOneStage2ControllerTest extends SsoIntegrationTestCase
{
    public function testSsoPingOneStage2Controller_Success_User(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $settings = SsoSettingsFactory::make()->pingone()->active()->persist();
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
        $mockPingOneProvider = $this->getProviderMockForStage2(PingOneProvider::class);
        $mockPingOneProvider->method('getAccessToken')->willReturn(new AccessToken([
            'access_token' => 'foo',
        ]));
        $mockPingOneProvider->method('getResourceOwner')->willReturn(new PingOneResourceOwner([
            'email' => $user->get('username'),
            'nonce' => $ssoState->get('nonce'),
        ]));
        // Swap actual implementation
        SsoProviderFactory::set($mockPingOneProvider);

        $this->get('/sso/pingone/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        /** @var \Cake\ORM\Entity $token */
        $token = SsoAuthenticationTokenFactory::find()
            ->where(['type' => SsoState::TYPE_SSO_GET_KEY, 'user_id' => $user->get('id')])
            ->firstOrFail();
        $this->assertRedirectContains('/sso/login/success?token=' . $token->get('token'));
    }

    /**
     * PingOne-specific: verifies flow works with a custom email claim field.
     * Unlike other providers, PingOne supports configurable email_claim.
     *
     * @retrun void
     */
    public function testSsoPingOneStage2Controller_Success_User_CustomEmailClaim(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $settings = SsoSettingsFactory::make()->pingone()->active()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoGetKey()
            ->userId($user->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->ip(SsoIntegrationTestCase::IP_ADDRESS)
            ->userAgent(SsoIntegrationTestCase::USER_AGENT)
            ->persist();
        $this->cookie('passbolt_sso_state', $ssoState->state);
        // Mock provider with custom email claim
        $mockPingOneProvider = $this->getProviderMockForStage2(PingOneProvider::class);
        $mockPingOneProvider->method('getAccessToken')->willReturn(new AccessToken([
            'access_token' => 'foo',
        ]));
        $mockPingOneProvider->method('getResourceOwner')->willReturn(new PingOneResourceOwner([
            'preferred_email' => $user->get('username'),
            'nonce' => $ssoState->get('nonce'),
        ], 'preferred_email'));
        SsoProviderFactory::set($mockPingOneProvider);

        $this->get('/sso/pingone/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        /** @var \Cake\ORM\Entity $token */
        $token = SsoAuthenticationTokenFactory::find()
            ->where(['type' => SsoState::TYPE_SSO_GET_KEY, 'user_id' => $user->get('id')])
            ->firstOrFail();
        $this->assertRedirectContains('/sso/login/success?token=' . $token->get('token'));
    }

    public function testSsoPingOneStage2Controller_Success_Admin(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $settings = SsoSettingsFactory::make()->pingone()->draft()->persist();
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
        $mockPingOneProvider = $this->getProviderMockForStage2(PingOneProvider::class);
        $mockPingOneProvider->method('getAccessToken')->willReturn(new AccessToken([
            'access_token' => 'foo',
        ]));
        $mockPingOneProvider->method('getResourceOwner')->willReturn(new PingOneResourceOwner([
            'email' => $admin->get('username'),
            'nonce' => $ssoState->get('nonce'),
        ]));
        // Swap actual implementation
        SsoProviderFactory::set($mockPingOneProvider);

        $this->logInAs($admin);
        $this->get('/sso/pingone/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        /** @var \Cake\ORM\Entity $token */
        $token = SsoAuthenticationTokenFactory::find()
            ->where(['type' => SsoState::TYPE_SSO_SET_SETTINGS, 'user_id' => $admin->get('id')])
            ->firstOrFail();
        $this->assertRedirectContains('/sso/login/dry-run/success?token=' . $token->get('token'));
    }

    /**
     * 400 if state is missing from url
     */
    public function testSsoPingOneStage2Controller_Common_ErrorStateFromUrlMissing(): void
    {
        $this->get('/sso/pingone/redirect');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The state is required in URL parameters.');
    }

    /**
     * 400 if state from url is not valid
     */
    public function testSsoPingOneStage2Controller_Common_ErrorStateFromUrlInvalid(): void
    {
        $this->get('/sso/pingone/redirect?state=nope');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The state is required in URL parameters.');
    }

    /**
     * 400 if state from cookie is not valid
     */
    public function testSsoPingOneStage2Controller_Common_ErrorStateFromCookieInvalid(): void
    {
        $this->cookie('passbolt_sso_state', 'nope');

        $this->get('/sso/pingone/redirect?state=' . SsoState::generate());

        $this->assertResponseCode(400);
        $this->assertResponseContains('The state is required in cookie.');
    }

    /**
     * 400 if state from url and cookie do not match
     */
    public function testSsoPingOneStage2Controller_Common_ErrorStateMismatch(): void
    {
        $this->cookie('passbolt_sso_state', SsoState::generate());

        $this->get('/sso/pingone/redirect?state=' . SsoState::generate());

        $this->assertResponseCode(400);
        $this->assertResponseContains('CSRF issue');
    }

    /**
     * 400 if code is missing from url
     */
    public function testSsoPingOneStage2Controller_Common_ErrorCodeMissing(): void
    {
        $state = SsoState::generate();
        $this->cookie('passbolt_sso_state', $state);

        $this->get('/sso/pingone/redirect?state=' . $state);

        $this->assertResponseCode(400);
        $this->assertResponseContains('The code is required in URL parameters.');
    }

    /**
     * 400 if code is not a string
     */
    public function testSsoPingOneStage2Controller_Common_ErrorCodeInvalid(): void
    {
        $state = SsoState::generate();
        $this->cookie('passbolt_sso_state', $state);

        $this->get('/sso/pingone/redirect?state=' . $state . '&code[not]=string');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The code is required in URL parameters.');
    }

    // ADMIN TESTS

    /**
     * 400 if SSO state not found in DB
     */
    public function testSsoPingOneStage2Controller_Admin_ErrorInvalidToken(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $state = SsoState::generate();
        $this->cookie('passbolt_sso_state', $state);
        $this->logInAs($admin);

        $this->get('/sso/pingone/redirect?state=' . $state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400);
        $this->assertResponseContains('The SSO state does not exist.');
    }

    /**
     * 400 if settings are not in draft state (admin flow)
     */
    public function testSsoPingOneStage2Controller_Admin_ErrorNotDraftSettings(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $settings = SsoSettingsFactory::make()->pingone()->active()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoSetSettings()
            ->userId($admin->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->persist();
        $this->logInAs($admin);
        $this->cookie('passbolt_sso_state', $ssoState->state);

        $this->get('/sso/pingone/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400);
        $this->assertResponseContains('The SSO settings do not exist.');
    }

    /**
     * 400 if user agent in SSO state does not match request
     */
    public function testSsoPingOneStage2Controller_Admin_ErrorInvalidTokenUseragent(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $settings = SsoSettingsFactory::make()->pingone()->draft()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make(['ip' => '127.0.0.1'])
            ->withTypeSsoSetSettings()
            ->userId($admin->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->userAgent('something else')
            ->persist();
        $this->logInAs($admin);
        $this->cookie('passbolt_sso_state', $ssoState->state);

        $this->get('/sso/pingone/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400);
        $this->assertResponseContains('The SSO state is invalid. User agent mismatch.');
    }

    // USER TESTS

    /**
     * 403 if user is already logged in (non-admin)
     */
    public function testSsoPingOneStage2Controller_User_ErrorIsLoggedIn(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $settings = SsoSettingsFactory::make()->pingone()->draft()->persist();
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
        $this->get('/sso/pingone/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(403);
        $this->assertResponseContains('The user should not be logged in.');
    }

    /**
     * 400 if SsoRecover plugin is disabled and state type is recover
     */
    public function testSsoPingOneStage2Controller_User_ErrorSsoRecoverDisabled(): void
    {
        $this->disableFeaturePlugin('SsoRecover');
        $settings = SsoSettingsFactory::make()->pingone()->active()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoRecover()
            ->userId($admin->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->persist();
        $this->cookie('passbolt_sso_state', $ssoState->state);

        $this->get('/sso/pingone/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400, 'SsoRecover plugin is disabled');
    }
}
