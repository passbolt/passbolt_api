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

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnSetupDeleteControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnSetupDeleteController_NotAuthenticated(): void
    {
        $this->delete('/mfa/setup/webauthn/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnSetupDeleteController_NotJson(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_WEBAUTHN);

        $this->delete('/mfa/setup/webauthn/' . UuidFactory::uuid());
        $this->assertResponseError();
        $this->assertResponseContains('This functionality is only available using AJAX/JSON.');
    }

    public function testWebauthnSetupDeleteController_MfaRequired_WhenNoValidCookie(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->delete('/mfa/setup/webauthn/aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa.json?api-version=v2');
        $this->assertRedirect('/mfa/verify/error.json');
    }

    public function testWebauthnSetupDeleteController_DeleteOne_LastCredential_DisablesProvider(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_WEBAUTHN);
        MfaSettings::clear();

        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $Credentials */
        $Credentials = TableRegistry::getTableLocator()
            ->get('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');
        /** @var \App\Model\Entity\WebauthnCredential $only */
        $only = $Credentials->findByUserId($user->id)->firstOrFail();

        $this->delete('/mfa/setup/webauthn/' . $only->id . '.json?api-version=v2');
        $this->assertResponseOk();
        $this->assertSame(0, $Credentials->countForUser($user->id));

        $AccountSettings = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings');
        $this->assertSame(0, $AccountSettings->find()
            ->where(['user_id' => $user->id, 'property' => MfaSettings::MFA])
            ->count());
    }

    public function testWebauthnSetupDeleteController_DeleteOne_KeepsProviderEnabledWhenOthersRemain(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        /** @var \App\Model\Entity\WebauthnCredential $extra */
        $extra = MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('extra')->persist();
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_WEBAUTHN);
        MfaSettings::clear();

        $this->delete('/mfa/setup/webauthn/' . $extra->id . '.json?api-version=v2');
        $this->assertResponseOk();

        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $Credentials */
        $Credentials = TableRegistry::getTableLocator()
            ->get('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');
        $this->assertSame(1, $Credentials->countForUser($user->id));

        MfaSettings::clear();
        $settings = MfaAccountSettings::get($this->makeUac($user));
        $this->assertContains(MfaSettings::PROVIDER_WEBAUTHN, $settings->getEnabledProviders());
    }

    public function testWebauthnSetupDeleteController_DeleteOne_OtherUsersCredential_ReturnsBadRequest(): void
    {
        $me = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);
        /** @var \App\Model\Entity\User $other */
        $other = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $theirs */
        $theirs = MfaWebauthnCredentialFactory::make()->withUser($other)->credentialId('theirs')->persist();

        $this->deleteJson('/mfa/setup/webauthn/' . $theirs->id . '.json?api-version=v2');
        $this->assertBadRequestError('The credential does not exist.');
        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $Credentials */
        $Credentials = TableRegistry::getTableLocator()
            ->get('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');
        $this->assertSame(1, $Credentials->countForUser($other->id));
        $this->assertSame(0, $Credentials->countForUser($me->id));
    }

    public function testWebauthnSetupDeleteController_OrgProviderDisabled_ReturnsBadRequest(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class, false);

        $this->deleteJson('/mfa/setup/webauthn/' . UuidFactory::uuid() . '.json?api-version=v2');
        $this->assertResponseError();
        $this->assertResponseContains('not enabled for your organization');
    }

    public function testWebauthnSetupDeleteController_InvalidUuid_ReturnsBadRequest(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->deleteJson('/mfa/setup/webauthn/not-a-uuid.json?api-version=v2');
        $this->assertBadRequestError('The credential identifier should be a valid UUID.');
    }

    public function testWebauthnSetupDeleteController_CredentialNotFoundForUser_ReturnsBadRequest(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->deleteJson('/mfa/setup/webauthn/' . UuidFactory::uuid() . '.json?api-version=v2');
        $this->assertBadRequestError('The credential does not exist.');
    }
}
