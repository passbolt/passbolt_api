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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Test\TestCase\Controller\Keys;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\Sso\Test\Factory\SsoKeysFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

class SsoKeysGetControllerTest extends SsoIntegrationTestCase
{
    /**
     * 200 auth token is consumed for Azure
     */
    public function testSsoKeysGetController_Success_Azure(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        $user = UserFactory::make()->active()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();
        $ssoAuthToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_GET_KEY)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => SsoIntegrationTestCase::IP_ADDRESS,
                'user_agent' => SsoIntegrationTestCase::USER_AGENT,
            ])
            ->persist();

        $this->getJson('/sso/keys/' . $key->id . '/' . $user->id . '/' . $ssoAuthToken->token . '.json');

        $this->assertSuccess();
        $result = $this->_responseJson->body;
        $this->assertEquals($key->id, $result->id);
        $this->assertEquals($key->user_id, $result->user_id);
        $this->assertEquals($key->data, $result->data);
        $this->assertEquals($key->created_by, $result->created_by);
        $this->assertEquals($key->modified_by, $result->modified_by);
        $this->assertNotEmpty($result->created);
        $this->assertNotEmpty($result->modified);
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $updateSsoAuthToken */
        $updateSsoAuthToken = SsoAuthenticationTokenFactory::find()->firstOrFail();
        $this->assertFalse($updateSsoAuthToken->active);
    }

    /**
     * 200 auth token is consumed for Google
     */
    public function testSsoKeysGetController_Success_Google(): void
    {
        $settings = SsoSettingsFactory::make()->google()->active()->persist();
        $user = UserFactory::make()->active()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();
        $ssoAuthToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_GET_KEY)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => SsoIntegrationTestCase::IP_ADDRESS,
                'user_agent' => SsoIntegrationTestCase::USER_AGENT,
            ])
            ->persist();

        $this->getJson('/sso/keys/' . $key->id . '/' . $user->id . '/' . $ssoAuthToken->token . '.json');

        $this->assertSuccess();
        $result = $this->_responseJson->body;
        $this->assertEquals($key->id, $result->id);
        $this->assertEquals($key->user_id, $result->user_id);
        $this->assertEquals($key->data, $result->data);
        $this->assertEquals($key->created_by, $result->created_by);
        $this->assertEquals($key->modified_by, $result->modified_by);
        $this->assertNotEmpty($result->created);
        $this->assertNotEmpty($result->modified);
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $updateSsoAuthToken */
        $updateSsoAuthToken = SsoAuthenticationTokenFactory::find()->firstOrFail();
        $this->assertFalse($updateSsoAuthToken->active);
    }

    /**
     * 403 user logged in
     */
    public function testSsoKeysGetController_ErrorUserLoggedIn(): void
    {
        $user = UserFactory::make()->active()->persist();
        $key = $token = UuidFactory::uuid();

        $this->logInAs($user);
        $this->getJson('/sso/keys/' . $key . '/' . $user->id . '/' . $token . '.json');
        $this->assertError(403, 'The user should not be logged in.');
    }

    /**
     * 400 user is deleted
     */
    public function testSsoKeysGetController_ErrorUserDeleted(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();
        $user = UserFactory::make()->active()->deleted()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();
        $token = UuidFactory::uuid();

        $this->getJson('/sso/keys/' . $key->id . '/' . $user->id . '/' . $token . '.json');
        $this->assertError(400, 'The request is invalid.');
    }

    /**
     * 400 token is invalid
     */
    public function testSsoKeysGetController_ErrorInvalidToken(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();
        $user = UserFactory::make()->active()->deleted()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();
        $tokenId = UuidFactory::uuid();

        $this->getJson('/sso/keys/' . $key->id . '/' . $user->id . '/' . $tokenId . '.json');
        $this->assertError(400, 'The request is invalid.');
    }

    /**
     * 404 user and state ok but key was deleted
     */
    public function testSsoKeysGetController_ErrorKeyNotFound(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        $user = UserFactory::make()->active()->persist();
        $keyId = UuidFactory::uuid();
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_GET_KEY)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => SsoIntegrationTestCase::IP_ADDRESS,
                'user_agent' => SsoIntegrationTestCase::USER_AGENT,
            ])
            ->persist();

        $this->getJson('/sso/keys/' . $keyId . '/' . $user->id . '/' . $token->token . '.json');

        $this->assertError(404);
    }
}
