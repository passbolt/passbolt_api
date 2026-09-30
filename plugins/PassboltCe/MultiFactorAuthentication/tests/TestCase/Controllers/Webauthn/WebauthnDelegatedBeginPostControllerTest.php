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
use App\Model\Entity\User;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnScenario;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Controller\Webauthn\WebauthnDelegatedBeginPostController
 */
class WebauthnDelegatedBeginPostControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnDelegatedBeginPostController_Success(): void
    {
        $user = $this->persistUserWithCredential();
        $state = $this->persistState($user);

        $this->postJson('/mfa/verify/webauthn/delegated/begin.json', ['mfa_delegated_state' => $state]);

        $this->assertResponseOk();
        $body = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($body['handle']);
        $this->assertNotEmpty($body['credentialRequestOptions']);
        /** @var \App\Model\Entity\AuthenticationToken $handle */
        $handle = AuthenticationTokenFactory::find()
            ->where(['token' => $this->getResponseBodyAsArray()['handle']])
            ->firstOrFail();
        $this->assertSame(AuthenticationToken::TYPE_MFA_VERIFY, $handle->type);
        $this->assertSame($user->id, $handle->user_id);
        $this->assertTrue($handle->active);
    }

    public function testWebauthnDelegatedBeginPostController_Success_IgnoresAUserIdInTheRequestBody(): void
    {
        $owner = $this->persistUserWithCredential();
        $state = $this->persistState($owner);
        /** @var \App\Model\Entity\User $other */
        $other = UserFactory::make()->user()->persist();

        $this->postJson('/mfa/verify/webauthn/delegated/begin.json', [
            'mfa_delegated_state' => $state,
            'user_id' => $other->id,
        ]);

        $this->assertResponseOk();
        /** @var \App\Model\Entity\AuthenticationToken $handle */
        $handle = AuthenticationTokenFactory::find()
            ->where(['token' => $this->getResponseBodyAsArray()['handle']])
            ->firstOrFail();
        $this->assertSame($owner->id, $handle->user_id);
    }

    public function testWebauthnDelegatedBeginPostController_Error_MissingState(): void
    {
        $this->postJson('/mfa/verify/webauthn/delegated/begin.json');
        $this->assertBadRequestError('A delegated state is required.');
    }

    public function testWebauthnDelegatedBeginPostController_Error_UnknownState(): void
    {
        $this->postJson('/mfa/verify/webauthn/delegated/begin.json', ['mfa_delegated_state' => UuidFactory::uuid()]);
        $this->assertBadRequestError('The delegated state is not valid.');
    }

    public function testWebauthnDelegatedBeginPostController_Error_ExpiredState(): void
    {
        $user = $this->persistUserWithCredential();
        $state = $this->persistState($user, ['created' => DateTime::now()->subMinutes(6)]);

        $this->postJson('/mfa/verify/webauthn/delegated/begin.json', ['mfa_delegated_state' => $state]);

        $this->assertBadRequestError('The delegated state is not valid.');
    }

    public function testWebauthnDelegatedBeginPostController_Error_InactiveState(): void
    {
        $user = $this->persistUserWithCredential();
        $state = $this->persistState($user, ['active' => false]);

        $this->postJson('/mfa/verify/webauthn/delegated/begin.json', ['mfa_delegated_state' => $state]);

        $this->assertBadRequestError('The delegated state is not valid.');
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function persistUserWithCredential(): User
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        return $user;
    }

    private function persistState(User $user, array $properties = []): string
    {
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
