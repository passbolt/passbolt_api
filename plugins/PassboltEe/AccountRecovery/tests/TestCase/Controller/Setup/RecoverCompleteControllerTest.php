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
 * @since         3.6.0
 */
namespace Passbolt\AccountRecovery\Test\TestCase\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Yubikey\MfaYubikeyOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class RecoverCompleteControllerTest extends AccountRecoveryIntegrationTestCase
{
    use EmailQueueTrait;

    /**
     * @group Requests
     * @group recover
     * @group recoverComplete
     */
    public function testRecoverCompleteSuccess()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();
        $user = UserFactory::make()
            ->active()
            ->withAuthenticationTokens(AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_RECOVER)
                ->active())
            ->with('Gpgkeys', GpgkeyFactory::make()->rsa4096Key())
            ->persist();
        $token = $user->authentication_tokens[0];
        $gpgkey = $user->gpgkey;

        $request = AccountRecoveryRequestFactory::make()
            ->withToken($token->id)
            ->withUser($user->id)
            ->approved()
            ->persist();

        // With multifactor authentication enabled, the MFA token gets cleared
        $isMFAEnabled = $this->isFeaturePluginEnabled('MultiFactorAuthentication');
        $this->enableFeaturePlugin('MultiFactorAuthentication');
        $this->loadFixtureScenario(MfaYubikeyOrganizationOnlyScenario::class);

        $url = '/setup/recover/complete/' . $user->id . '.json';
        $data = [
            'authenticationtoken' => [
                'token' => $token->token,
            ],
            'gpgkey' => [
                'armored_key' => $gpgkey->armored_key,
            ],
            'account_recovery_request_id' => $request->id,
        ];
        $this->postJson($url, $data);
        $this->assertResponseOk();

        // Check that token is now inactive
        $this->assertSame(1, AuthenticationTokenFactory::count());
        $this->assertFalse(AuthenticationTokenFactory::get($token->id)->isActive());
        // Check that the request is now marked as completed
        $updatedRequest = AccountRecoveryRequestFactory::get($request->id);
        $this->assertTrue($updatedRequest->isCompleted());
        $this->assertSame($user->id, $updatedRequest->modified_by);
        $this->assertSame(1, AccountRecoveryRequestFactory::count());

        // Check that the cookie was cleared
        $this->assertCookieExpired(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (!$isMFAEnabled) {
            $this->disableFeaturePlugin('MultiFactorAuthentication');
        }
    }
}
