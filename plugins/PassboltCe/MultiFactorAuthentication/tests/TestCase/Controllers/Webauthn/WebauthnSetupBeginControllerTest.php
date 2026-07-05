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
 * @since         5.14.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Webauthn;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnOrganizationOnlyScenario;

/**
 * @group mfa
 * @group mfaSetup
 * @group webauthn
 */
class WebauthnSetupBeginControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnSetupBegin_NotAuthenticated(): void
    {
        $this->postJson('/mfa/setup/webauthn/begin.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnSetupBegin_Success(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->postJson('/mfa/setup/webauthn/begin.json?api-version=v2');

        $this->assertResponseSuccess();
        // A single-use mfa_setup challenge token holding the ceremony options is created.
        $this->assertSame(1, AuthenticationTokenFactory::count());
        $token = AuthenticationTokenFactory::find()->firstOrFail();
        $this->assertSame(AuthenticationToken::TYPE_MFA_SETUP, $token->type);
    }

    public function testWebauthnSetupBegin_OrganizationDoesNotAllowProvider(): void
    {
        $this->logInAsUser();
        // No organization scenario loaded: the provider is not enabled for the organization.
        $this->postJson('/mfa/setup/webauthn/begin.json?api-version=v2');

        $this->assertResponseError('This authentication provider is not enabled for your organization.');
        $this->assertSame(0, AuthenticationTokenFactory::count());
    }

    public function testWebauthnSetupBegin_RequiresJson(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->post('/mfa/setup/webauthn/begin');

        $this->assertResponseError('This functionality is only available using AJAX/JSON.');
    }
}
