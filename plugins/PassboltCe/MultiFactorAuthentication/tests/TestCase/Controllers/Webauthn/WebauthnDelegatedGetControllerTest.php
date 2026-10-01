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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Webauthn;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Passbolt\Locale\Service\GetOrgLocaleService;
use Passbolt\Locale\Test\Factory\LocaleSettingFactory;
use Passbolt\MultiFactorAuthentication\Controller\Webauthn\WebauthnDelegatedGetController;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;

class WebauthnDelegatedGetControllerTest extends MfaIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        GetOrgLocaleService::clearOrganisationLocale();
    }

    public function tearDown(): void
    {
        GetOrgLocaleService::clearOrganisationLocale();
        parent::tearDown();
    }

    public function testWebauthnDelegatedGetController_Success(): void
    {
        $state = $this->persistState();

        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . $state);

        $this->assertResponseOk();
        $this->assertResponseContains('js/app/api-vendors.js');
        $this->assertResponseContains('js/app/api-mfa-verify-delegated.js');
        $this->assertResponseContains('data-file="api_authentication.min.css"');
        $this->assertResponseContains('id="api-mfa-verify-delegated"');
        $this->assertResponseContains('data-state="' . $state . '"');
    }

    public function testWebauthnDelegatedGetController_Success_PassesTheDefaultLocale(): void
    {
        $state = $this->persistState();

        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . $state);

        $this->assertResponseOk();
        $this->assertResponseContains('data-locale="en-UK"');
    }

    public function testWebauthnDelegatedGetController_Success_PassesTheLocaleOfTheApplication(): void
    {
        $state = $this->persistState();

        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . $state . '&locale=fr-FR');

        $this->assertResponseOk();
        $this->assertResponseContains('data-locale="fr-FR"');
    }

    public function testWebauthnDelegatedGetController_Success_PassesTheOrganizationLocaleIfTheApplicationLocaleIsNotSupported(): void
    {
        LocaleSettingFactory::make()->locale('de_DE')->persist();
        $state = $this->persistState();

        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . $state . '&locale=xx-YY');

        $this->assertResponseOk();
        $this->assertResponseContains('data-locale="de-DE"');
    }

    public function testWebauthnDelegatedGetController_Success_SendsNoReferrerPolicyHeader(): void
    {
        $state = $this->persistState();

        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . $state);

        $this->assertResponseOk();
        $this->assertHeader('Referrer-Policy', 'no-referrer');
    }

    public function testWebauthnDelegatedGetController_Success_SendsAContentSecurityPolicyWithNoThirdPartyOrigin(): void
    {
        $state = $this->persistState();

        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . $state);

        $this->assertResponseOk();
        $csp = $this->_response->getHeaderLine('Content-Security-Policy');
        $this->assertStringContainsString("default-src 'none'", $csp);
        $this->assertStringContainsString("script-src 'self'", $csp);
        $this->assertStringContainsString("style-src 'self'", $csp);
        $this->assertStringContainsString("img-src 'self'", $csp);
    }

    public function testWebauthnDelegatedGetController_Success_CarriesTheHardcodedRedirectUri(): void
    {
        $state = $this->persistState();

        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . $state);

        $this->assertResponseOk();
        $this->assertResponseContains('data-redirect-uri="' . WebauthnDelegatedGetController::REDIRECT_URI . '"');
    }

    public function testWebauthnDelegatedPage_Success_TokenIsNotRenderedInMarkup(): void
    {
        $state = $this->persistState();

        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . $state);

        $this->assertResponseOk();
        $this->assertResponseContains('js/app/api-mfa-verify-delegated.js');
        $this->assertResponseNotContains('mfa_delegated_token');
        $this->assertResponseNotContains('data-token');
        $this->assertResponseNotContains('<input type="hidden"');
        $this->assertResponseNotContains('href="' . WebauthnDelegatedGetController::REDIRECT_URI);
    }

    public function testWebauthnDelegatedPage_Success_ExpiredScreenCarriesOneGenericErrorCode(): void
    {
        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . UuidFactory::uuid());

        $this->assertResponseOk();
        $this->assertResponseContains(
            WebauthnDelegatedGetController::REDIRECT_URI . '?error=state_invalid',
        );
    }

    public function testWebauthnDelegatedGetController_Success_RendersExpiredScreenWhenStateIsUnknown(): void
    {
        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . UuidFactory::uuid());

        $this->assertResponseOk();
        $this->assertResponseContains('id="mfa-webauthn-delegated-expired"');
        $this->assertResponseNotContains('id="api-mfa-verify-delegated"');
        $this->assertResponseContains(WebauthnDelegatedGetController::REDIRECT_URI . '?error=state_invalid');
    }

    public function testWebauthnDelegatedGetController_Success_RendersExpiredScreenWhenStateIsNotAUuid(): void
    {
        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=not-a-uuid');
        $this->assertResponseOk();
        $this->assertResponseContains('id="mfa-webauthn-delegated-expired"');
    }

    public function testWebauthnDelegatedGetController_Success_RendersExpiredScreenWhenStateIsMissing(): void
    {
        $this->get('/mfa/verify/webauthn/delegated');
        $this->assertResponseOk();
        $this->assertResponseContains('id="mfa-webauthn-delegated-expired"');
    }

    public function testWebauthnDelegatedGetController_Success_RendersExpiredScreenWhenStateIsAlreadySigned(): void
    {
        $state = $this->persistState(['active' => false]);

        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . $state);

        $this->assertResponseOk();
        $this->assertResponseContains('id="mfa-webauthn-delegated-expired"');
    }

    public function testWebauthnDelegatedGetController_Success_RendersExpiredScreenWhenStateIsExpired(): void
    {
        $state = $this->persistState(['created' => DateTime::now()->subMinutes(6)]);

        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . $state);

        $this->assertResponseOk();
        $this->assertResponseContains('id="mfa-webauthn-delegated-expired"');
    }

    public function testWebauthnDelegatedGetController_Success_RendersExpiredScreenWhenUserIsDeleted(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->deleted()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make([
            'user_id' => $user->id,
            'token' => UuidFactory::uuid(),
            'type' => AuthenticationToken::TYPE_MFA_DELEGATED_STATE,
            'active' => true,
        ])->persist();

        $this->get('/mfa/verify/webauthn/delegated?mfa_delegated_state=' . $row->token);

        $this->assertResponseOk();
        $this->assertResponseContains('id="mfa-webauthn-delegated-expired"');
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    /**
     * @param array $properties Properties to override.
     * @return string The delegated state value.
     */
    private function persistState(array $properties = []): string
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make($properties + [
                'user_id' => $user->id,
                'token' => UuidFactory::uuid(),
                'type' => AuthenticationToken::TYPE_MFA_DELEGATED_STATE,
                'active' => true,
                'data' => json_encode([MfaWebauthnDelegatedStateService::SECRET_KEY => UuidFactory::uuid()]),
            ])->persist();

        return $row->token;
    }
}
