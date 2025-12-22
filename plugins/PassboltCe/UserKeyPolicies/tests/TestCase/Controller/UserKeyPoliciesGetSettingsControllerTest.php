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
 * @since         5.2.0
 */

namespace Passbolt\UserKeyPolicies\Test\TestCase\Controller;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cake\Routing\Exception\MissingRouteException;
use Passbolt\UserKeyPolicies\Model\Dto\UserKeyPoliciesSettingsDto;
use Passbolt\UserKeyPolicies\UserKeyPoliciesPlugin;

/**
 * @covers \Passbolt\UserKeyPolicies\Controller\UserKeyPoliciesGetSettingsController
 */
class UserKeyPoliciesGetSettingsControllerTest extends AppIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(UserKeyPoliciesPlugin::class);
    }

    public function testUserKeyPoliciesGetSettingsController_Success_GuestSourceDefault(): void
    {
        $user = UserFactory::make()->user()->inactive()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', $user)
            ->persist();

        $queryParams = http_build_query([
            'user_id' => $user->get('id'),
            'token' => $token->get('token'),
        ]);
        $this->getJson('/setup/user-key-policies/settings.json?' . $queryParams);

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $expectedResponseKeys = ['preferred_key_type', 'preferred_key_size', 'preferred_key_curve', 'source'];
        $this->assertArrayHasAttributes($expectedResponseKeys, $response);
        $expectedResponse = UserKeyPoliciesSettingsDto::createFromDefault([])->toArray();
        $this->assertArrayEqualsCanonicalizing($expectedResponse, $response);
    }

    public function testUserKeyPoliciesGetSettingsController_Success_UserSourceEnv(): void
    {
        putenv('PASSBOLT_PLUGINS_USER_KEY_POLICIES_PREFERRED_KEY_TYPE=curve');
        putenv('PASSBOLT_PLUGINS_USER_KEY_POLICIES_PREFERRED_KEY_SIZE=null');
        putenv('PASSBOLT_PLUGINS_USER_KEY_POLICIES_PREFERRED_KEY_CURVE=curve25519_legacy+ed25519_legacy');
        $this->logInAsUser();

        $this->getJson('/user-key-policies/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $expectedResponseKeys = ['preferred_key_type', 'preferred_key_size', 'preferred_key_curve', 'source'];
        $this->assertArrayHasAttributes($expectedResponseKeys, $response);
        $this->assertSame(UserKeyPoliciesSettingsDto::SOURCE_ENV, $response['source']);
        $this->assertSame(UserKeyPoliciesSettingsDto::KEY_TYPE_CURVE, $response['preferred_key_type']);
        $this->assertSame(null, $response['preferred_key_size']);
        $this->assertSame(UserKeyPoliciesSettingsDto::KEY_CURVE_ED25519_LEGACY, $response['preferred_key_curve']);
        // reset env state
        putenv('PASSBOLT_PLUGINS_USER_KEY_POLICIES_PREFERRED_KEY_TYPE');
        putenv('PASSBOLT_PLUGINS_USER_KEY_POLICIES_PREFERRED_KEY_SIZE');
        putenv('PASSBOLT_PLUGINS_USER_KEY_POLICIES_PREFERRED_KEY_CURVE');
    }

    public function testUserKeyPoliciesGetSettingsController_Success_AdminSourceFile(): void
    {
        $this->logInAsAdmin();
        $rootConfigKey = UserKeyPoliciesPlugin::PREFERRED_KEY_TYPE_CONFIG_KEY;
        Configure::write($rootConfigKey . '.' . 'preferred_key_type', 'RSA');
        Configure::write($rootConfigKey . '.' . 'preferred_key_size', UserKeyPoliciesSettingsDto::KEY_SIZE_4096);
        Configure::write($rootConfigKey . '.' . 'preferred_key_curve', 'null');

        $this->getJson('/user-key-policies/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $expectedResponseKeys = ['preferred_key_type', 'preferred_key_size', 'preferred_key_curve', 'source'];
        $this->assertArrayHasAttributes($expectedResponseKeys, $response);
        $this->assertSame(UserKeyPoliciesSettingsDto::SOURCE_FILE, $response['source']);
        $this->assertSame(UserKeyPoliciesSettingsDto::KEY_TYPE_RSA, $response['preferred_key_type']);
        $this->assertSame(UserKeyPoliciesSettingsDto::KEY_SIZE_4096, $response['preferred_key_size']);
        $this->assertSame(null, $response['preferred_key_curve']);
    }

    public function testUserKeyPoliciesGetSettingsController_Success_FallbackToDefaultsIfInvalidValuesSet(): void
    {
        $rootConfigKey = UserKeyPoliciesPlugin::PREFERRED_KEY_TYPE_CONFIG_KEY;
        Configure::write($rootConfigKey . '.' . 'preferred_key_type', 'foo-bar');
        Configure::write($rootConfigKey . '.' . 'preferred_key_size', 123);
        Configure::write($rootConfigKey . '.' . 'preferred_key_curve', 'null');
        $user = UserFactory::make()->user()->inactive()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', $user)
            ->persist();

        $queryParams = http_build_query([
            'user_id' => $user->get('id'),
            'token' => $token->get('token'),
        ]);
        $this->getJson('/setup/user-key-policies/settings.json?' . $queryParams);

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $expectedResponseKeys = ['preferred_key_type', 'preferred_key_size', 'preferred_key_curve', 'source'];
        $this->assertArrayHasAttributes($expectedResponseKeys, $response);
        $this->assertSame(UserKeyPoliciesSettingsDto::SOURCE_DEFAULT, $response['source']);
        $this->assertSame(UserKeyPoliciesSettingsDto::DEFAULT_KEY_TYPE, $response['preferred_key_type']);
        $this->assertSame(UserKeyPoliciesSettingsDto::DEFAULT_KEY_SIZE, $response['preferred_key_size']);
        $this->assertSame(UserKeyPoliciesSettingsDto::DEFAULT_KEY_CURVE, $response['preferred_key_curve']);
    }

    public function testUserKeyPoliciesGetSettingsController_Error_PluginDisabled(): void
    {
        $this->disableErrorHandlerMiddleware();
        $this->disableFeaturePlugin(UserKeyPoliciesPlugin::class);
        $user = UserFactory::make()->user()->inactive()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', $user)
            ->persist();

        $this->expectException(MissingRouteException::class);

        $queryParams = http_build_query([
            'user_id' => $user->get('id'),
            'token' => $token->get('token'),
        ]);
        $this->getJson('/setup/user-key-policies/settings.json?' . $queryParams);
    }

    public function testUserKeyPoliciesGetSettingsController_Error_Unauthorize(): void
    {
        $this->getJson('/user-key-policies/settings.json');
        $this->assertAuthenticationError('You are not authorized to access this location');
    }

    public function testUserKeyPoliciesGetSettingsController_Error_InvalidAuthenticationToken(): void
    {
        $user = UserFactory::make()->user()->inactive()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_MFA_SETUP)
            ->with('Users', $user)
            ->persist();

        $queryParams = http_build_query([
            'user_id' => $user->get('id'),
            'token' => $token->token, // bad
        ]);
        $this->getJson('/setup/user-key-policies/settings.json?' . $queryParams);

        $this->assertBadRequestError('No registration authentication token found for the given user');
    }

    public function testUserKeyPoliciesGetSettingsController_Error_BadAuthenticationToken(): void
    {
        $user = UserFactory::make()->user()->inactive()->persist();
        $queryParams = http_build_query(['user_id' => $user->get('id'), 'token' => 'foo-bar']);
        $this->getJson('/setup/user-key-policies/settings.json?' . $queryParams);
        $this->assertBadRequestError('The authentication token must be a valid UUID');
    }

    public function testUserKeyPoliciesGetSettingsController_Error_InvalidUser(): void
    {
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->persist();
        $queryParams = http_build_query(['user_id' => 'not-valid-uui', 'token' => $token->get('token')]);
        $this->getJson('/setup/user-key-policies/settings.json?' . $queryParams);
        $this->assertBadRequestError('The user ID must be a valid UUID');
    }

    public function testUserKeyPoliciesGetSettingsController_Error_InvalidUserAndToken(): void
    {
        $queryParams = http_build_query(['user_id' => 'not-valid-uui', 'token' => 'i-am-too-not-valid']);
        $this->getJson('/setup/user-key-policies/settings.json?' . $queryParams);
        $this->assertBadRequestError('The user ID must be a valid UUID');
    }

    public function testUserKeyPoliciesGetSettingsController_Error_ExpiredToken(): void
    {
        $user = UserFactory::make()->user()->inactive()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->expired()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', $user)
            ->persist();
        $queryParams = http_build_query([
            'user_id' => $user->get('id'),
            'token' => $token->token,
        ]);
        $this->getJson('/setup/user-key-policies/settings.json?' . $queryParams);
        $this->assertBadRequestError('The registration authentication token is expired');
    }

    public function testUserKeyPoliciesGetSettingsController_Error_SessionConfusion(): void
    {
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->expired()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', $user)
            ->persist();

        $this->logInAs($user);
        $queryParams = http_build_query(['user_id' => $user->get('id'), 'token' => $token->token]);
        $this->getJson('/setup/user-key-policies/settings.json?' . $queryParams);

        $this->assertBadRequestError("Conflicting authentication parameters, provide user_id\/token only when the user is not already signed in");
    }
}
