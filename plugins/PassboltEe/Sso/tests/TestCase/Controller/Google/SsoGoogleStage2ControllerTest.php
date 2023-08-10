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

namespace Passbolt\Sso\Test\TestCase\Controller\Google;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

/**
 * @see \Passbolt\Sso\Controller\Google\SsoGoogleStage2Controller
 */
class SsoGoogleStage2ControllerTest extends SsoIntegrationTestCase
{
    public function testSsoGoogleStage2Controller_Success(): void
    {
        // Requires mocking GoogleService - not implemented
        $this->markTestIncomplete();
    }

    /**
     * 400 if state is missing from url
     */
    public function testSsoGoogleStage2Controller_Common_ErrorStateFromUrlMissing(): void
    {
        $this->get('/sso/google/redirect');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The state is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoGoogleStage2Controller_Common_ErrorStateFromUrlInvalid(): void
    {
        $this->get('/sso/google/redirect?state=nope');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The state is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoGoogleStage2Controller_Common_ErrorStateFromCookieInvalid(): void
    {
        $this->cookie('passbolt_sso_state', 'nope');

        $this->get('/sso/google/redirect?state=' . SsoState::generate());

        $this->assertResponseCode(400);
        $this->assertResponseContains('The state is required in cookie.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoGoogleStage2Controller_Common_ErrorStateMismatch(): void
    {
        $this->cookie('passbolt_sso_state', SsoState::generate());

        $this->get('/sso/google/redirect?state=' . SsoState::generate());

        $this->assertResponseCode(400);
        $this->assertResponseContains('CSRF issue');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoGoogleStage2Controller_Common_ErrorCodeMissing(): void
    {
        $state = SsoState::generate();
        $this->cookie('passbolt_sso_state', $state);

        $this->get('/sso/google/redirect?state=' . $state);

        $this->assertResponseCode(400);
        $this->assertResponseContains('The code is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoGoogleStage2Controller_Common_ErrorCodeInvalid(): void
    {
        $state = SsoState::generate();
        $this->cookie('passbolt_sso_state', $state);

        $this->get('/sso/google/redirect?state=' . $state . '&code[not]=string');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The code is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    // ADMIN TESTS

    /**
     * 400 if state from url is not UUID
     */
    public function testSsoGoogleStage2Controller_Admin_ErrorInvalidToken(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $state = SsoState::generate();
        $this->cookie('passbolt_sso_state', $state);
        $this->logInAs($admin);

        $this->get('/sso/google/redirect?state=' . $state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400);
        $this->assertResponseContains('The SSO state does not exist.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if not draft settings
     */
    public function testSsoGoogleStage2Controller_Admin_ErrorNotDraftSettings(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $settings = SsoSettingsFactory::make()->google()->active()->persist();
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoSetSettings()
            ->userId($admin->id)
            ->ssoSettingsId($settings->id)
            ->persist();
        $this->logInAs($admin);
        $this->cookie('passbolt_sso_state', $ssoState->state);

        $this->get('/sso/google/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400);
        $this->assertResponseContains('The SSO settings do not exist.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    /**
     * 400 if token is invalid
     */
    public function testSsoGoogleStage2Controller_Admin_ErrorInvalidTokenUseragent(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $settings = SsoSettingsFactory::make()->google()->draft()->persist();
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoSetSettings()
            ->userId($admin->id)
            ->ssoSettingsId($settings->id)
            ->userAgent('something else')
            ->persist();
        /** Note: Can't use `mockUserIp()` because this test doesn't extend AppIntegrationTestCase. */
        $this->configRequest([
            'environment' => ['REMOTE_ADDR' => $ssoState->ip],
        ]);
        $this->logInAs($admin);
        $this->cookie('passbolt_sso_state', $ssoState->state);

        $this->get('/sso/google/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400);
        $this->assertResponseContains('The SSO state is invalid. User agent mismatch.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    // USERS TESTS

    /**
     * 403 if
     */
    public function testSsoGoogleStage2Controller_User_ErrorIsLoggedIn(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $settings = SsoSettingsFactory::make()->google()->draft()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoSetSettings()
            ->userId($admin->id)
            ->ssoSettingsId($settings->id)
            ->userAgent('something else')
            ->persist();
        $this->logInAs($user);

        $this->cookie('passbolt_sso_state', $ssoState->state);
        $this->get('/sso/google/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());
        $this->assertResponseCode(403);
        $this->assertResponseContains('The user should not be logged in.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testSsoGoogleStage2Controller_User_ErrorSsoRecoverDisabled(): void
    {
        $this->disableFeaturePlugin('SsoRecover');
        $settings = SsoSettingsFactory::make()->google()->active()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoRecover()
            ->userId($admin->id)
            ->ssoSettingsId($settings->id)
            ->persist();
        $this->cookie('passbolt_sso_state', $ssoState->state);

        $this->get('/sso/google/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400, 'SsoRecover plugin is disabled');
    }
}
