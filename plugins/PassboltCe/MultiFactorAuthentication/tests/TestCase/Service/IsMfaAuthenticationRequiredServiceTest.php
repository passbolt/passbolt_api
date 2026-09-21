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
 * @since         5.6.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Cookie\CookieCollection;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Service\IsMfaAuthenticationRequiredService;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\IsMfaAuthenticationRequiredService
 */
class IsMfaAuthenticationRequiredServiceTest extends MfaIntegrationTestCase
{
    public function testIsMfaCheckRequired_RememberTrueRow_PolicyDisabled_RequiresMfaAndInvalidatesRow(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($user);
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);

        $authTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $tokenString = UuidFactory::uuid();
        $tokenRow = $authTokens->newEntity(
            [
                'user_id' => $user->id,
                'token' => $tokenString,
                'active' => true,
                'type' => AuthenticationToken::TYPE_MFA,
                'data' => json_encode([
                    'provider' => MfaSettings::PROVIDER_TOTP,
                    'user_agent' => null,
                    'remember' => true,
                ]),
            ],
            ['accessibleFields' => [
                'user_id' => true, 'token' => true, 'active' => true, 'type' => true, 'data' => true,
            ]],
        );
        $authTokens->saveOrFail($tokenRow);
        MfaAuthenticationTokenFactory::make()->active()->data(['remember' => true])->persist();

        $cookie = (new CookieCollection())->add(new Cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, $tokenString));
        $request = (new ServerRequest())->withCookieCollection($cookie);

        $policy = $this->createMock(RememberAMonthSettingInterface::class);
        $policy->method('isEnabled')->willReturn(false);

        $isRequired = (new IsMfaAuthenticationRequiredService())->isMfaCheckRequired(
            $request,
            MfaSettings::get($uac),
            $uac,
            null,
            $policy,
        );

        $this->assertTrue($isRequired);
        $refreshed = $authTokens->find()->where(['token' => $tokenString])->firstOrFail();
        $this->assertFalse($refreshed->get('active'));
    }
}
