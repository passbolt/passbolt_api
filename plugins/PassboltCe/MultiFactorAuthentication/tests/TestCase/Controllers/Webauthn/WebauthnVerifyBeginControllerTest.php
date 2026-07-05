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
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAccountSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnOrganizationOnlyScenario;

/**
 * @group mfa
 * @group mfaVerify
 * @group webauthn
 */
class WebauthnVerifyBeginControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnVerifyBegin_NotAuthenticated(): void
    {
        $this->postJson('/mfa/verify/webauthn/begin.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnVerifyBegin_Success(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);
        MfaAccountSettingFactory::make()->webauthn()->patchData(['user_id' => $user->id])->persist();

        $this->postJson('/mfa/verify/webauthn/begin.json?api-version=v2');

        $this->assertResponseSuccess();
        // A single-use mfa_verify challenge token holding the ceremony options is created.
        $this->assertSame(1, AuthenticationTokenFactory::count());
        $token = AuthenticationTokenFactory::find()->firstOrFail();
        $this->assertSame(AuthenticationToken::TYPE_MFA_VERIFY, $token->type);
    }

    public function testWebauthnVerifyBegin_NoUserSettings(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);
        // The user has not registered any security key: verification is not available.
        $this->postJson('/mfa/verify/webauthn/begin.json?api-version=v2');

        $this->assertResponseError('No valid multi-factor authentication settings found.');
        $this->assertSame(0, AuthenticationTokenFactory::count());
    }
}
