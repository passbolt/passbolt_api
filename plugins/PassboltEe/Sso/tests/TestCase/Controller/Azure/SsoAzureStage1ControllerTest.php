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

namespace Passbolt\Sso\Test\TestCase\Controller\Azure;

use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

/**
 * @property \Cake\Http\Response $_response
 */
class SsoAzureStage1ControllerTest extends SsoIntegrationTestCase
{
    /**
     * 200 returns a URL
     */
    public function testSsoAzureStage1Controller_Success(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $this->createAzureSettingsFromConfig($user);

        $this->postJson('/sso/azure/login.json', ['user_id' => $user->id]);

        $this->assertSuccess();
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('microsoft', $url);
        $this->assertStringContainsString('login_hint', $url);
        // assert sso state cookie
        $this->assertCookieSet(AbstractSsoService::SSO_STATE_COOKIE);
        $cookie = $this->_response->getCookie(AbstractSsoService::SSO_STATE_COOKIE);
        $this->assertEquals('/sso', $cookie['path']);
    }

    public function testSsoAzureStage1Controller_SuccessWithSubdir(): void
    {
        Configure::write('App.base', '/passbolt');
        $user = UserFactory::make()->admin()->persist();
        $this->createAzureSettingsFromConfig($user);

        $this->postJson('/sso/azure/login.json', ['user_id' => $user->id]);

        $this->assertSuccess();
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('microsoft', $url);
        $this->assertStringContainsString('login_hint', $url);
        // assert sso state cookie
        $this->assertCookieSet(AbstractSsoService::SSO_STATE_COOKIE);
        $cookie = $this->_response->getCookie(AbstractSsoService::SSO_STATE_COOKIE);
        $this->assertEquals('/passbolt/sso', $cookie['path']);
    }

    /**
     * 400 user is logged in
     */
    public function testSsoAzureStage1Controller_ErrorLoggedIn(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->azure()->active()->persist();

        $this->logInAs($user);
        $this->postJson('/sso/azure/login.json', ['user_id' => $user->id]);
        $this->assertError(403);
    }

    /**
     * 400 user is deleted
     */
    public function testSsoAzureStage1Controller_ErrorDeletedUser(): void
    {
        $user = UserFactory::make()->admin()->deleted()->persist();
        SsoSettingsFactory::make()->azure()->active()->persist();

        $this->postJson('/sso/azure/login.json', ['user_id' => $user->id]);
        $this->assertError(400);
    }

    /**
     * 400 user is not active
     */
    public function testSsoAzureStage1Controller_ErrorInactiveUser(): void
    {
        $user = UserFactory::make()->admin()->inactive()->persist();
        SsoSettingsFactory::make()->azure()->active()->persist();

        $this->postJson('/sso/azure/login.json', ['user_id' => $user->id]);
        $this->assertError(400);
    }

    /**
     * 400 user id is missing
     */
    public function testSsoAzureStage1Controller_ErrorUserIdMissing(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();

        $this->postJson('/sso/azure/login.json', ['user_id' => null]);
        $this->assertError(400);
    }

    /**
     * 400 user id is missing too
     */
    public function testSsoAzureStage1Controller_ErrorUserIdMissing2(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();

        $this->postJson('/sso/azure/login.json', []);
        $this->assertError(400);
    }

    /**
     * 400 user id is invalid
     */
    public function testSsoAzureStage1Controller_ErrorUserIdInvalid(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();

        $this->postJson('/sso/azure/login.json', ['user_id' => 'nope']);
        $this->assertError(400);
    }

    /**
     * 400 no active users
     */
    public function testSsoAzureStage1Controller_ErrorNoActiveSettings(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->azure()->draft()->persist();

        $this->postJson('/sso/azure/login.json', ['user_id' => $user->id]);
        $this->assertError(400);
    }
}
