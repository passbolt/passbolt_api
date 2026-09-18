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
use ParagonIE\ConstantTime\Base64UrlSafe;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnOrganizationOnlyScenario;

class WebauthnSetupBeginPostControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnSetupBeginPostController_NotAuthenticated(): void
    {
        $this->postJson('/mfa/setup/webauthn/begin.json?api-version=v2', []);
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnSetupBeginPostController_NotJson(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->post('/mfa/setup/webauthn/begin', []);
        $this->assertResponseError();
        $this->assertResponseContains('This functionality is only available using AJAX/JSON.');
    }

    public function testWebauthnSetupBeginPostController_OrgProviderDisabled(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class, false);

        $this->postJson('/mfa/setup/webauthn/begin.json?api-version=v2', []);
        $this->assertResponseError();
        $this->assertResponseContains('not enabled for your organization');
    }

    public function testWebauthnSetupBeginPostController_Success_PersistsHandleAndReturnsOptions(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->postJson('/mfa/setup/webauthn/begin.json?api-version=v2', []);
        $this->assertResponseOk();

        $body = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($body['handle']);
        $options = $body['credentialCreationOptions'];
        $this->assertNotEmpty($options['challenge']);
        $this->assertSame([], $options['excludeCredentials']);
        $this->assertSame('preferred', $options['authenticatorSelection']['userVerification']);
        $this->assertSame('discouraged', $options['authenticatorSelection']['residentKey']);
        $this->assertArrayNotHasKey('authenticatorAttachment', $options['authenticatorSelection']);
        $this->assertSame('none', $options['attestation']);
        $this->assertSame(60_000, $options['timeout']);
        $this->assertSame([], $options['hints']);
        /** @var array $pubKeyCredParams */
        $pubKeyCredParams = $options['pubKeyCredParams'];
        $this->assertSame([-7, -257], array_column($pubKeyCredParams, 'alg'));

        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::find()
            ->where(['token' => $body['handle'], 'user_id' => $user->id])
            ->firstOrFail();
        $this->assertSame(AuthenticationToken::TYPE_MFA_SETUP, $token->type);
        $this->assertTrue($token->active);
    }

    public function testWebauthnSetupBeginPostController_Success_ExcludesAlreadyEnrolledCredentials(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('existing-a')->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('existing-b')->persist();

        $this->postJson('/mfa/setup/webauthn/begin.json?api-version=v2', []);
        $this->assertResponseOk();

        /** @var array $exclude */
        $exclude = $this->getResponseBodyAsArray()['credentialCreationOptions']['excludeCredentials'];
        $this->assertCount(2, $exclude);
        $ids = array_column($exclude, 'id');
        $this->assertContains(Base64UrlSafe::encodeUnpadded('existing-a'), $ids);
        $this->assertContains(Base64UrlSafe::encodeUnpadded('existing-b'), $ids);
    }
}
