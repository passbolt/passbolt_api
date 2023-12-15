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
 * @since         4.6.0
 */

namespace Passbolt\Sso\Test\TestCase\Controller\Adfs;

use App\Test\Factory\UserFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

/**
 * @see \Passbolt\Sso\Controller\Adfs\SsoAdfsStage1Controller
 * @property \Cake\Http\Response $_response
 */
class SsoAdfsStage1ControllerTest extends SsoIntegrationTestCase
{
    /**
     * 200 returns a URL
     */
    public function testSsoAdfsStage1Controller_Success(): void
    {
        // Requires mocking ADFS service or working ADFS instance
        $this->markTestIncomplete();
    }

    public function testSsoAdfsStage1Controller_SuccessWithSubdir(): void
    {
        // Requires mocking ADFS service or working ADFS instance
        $this->markTestIncomplete();
    }

    /**
     * 403 user is logged in
     */
    public function testSsoAdfsStage1Controller_ErrorLoggedIn(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->adfs()->active()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/adfs/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(403);
    }

    /**
     * 400 user is deleted
     */
    public function testSsoAdfsStage1Controller_ErrorDeletedUser(): void
    {
        $user = UserFactory::make()->admin()->deleted()->persist();
        SsoSettingsFactory::make()->adfs()->active()->persist();

        $this->postJson('/sso/adfs/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(400);
    }

    /**
     * 400 user is not active
     */
    public function testSsoAdfsStage1Controller_ErrorInactiveUser(): void
    {
        $user = UserFactory::make()->admin()->inactive()->persist();
        SsoSettingsFactory::make()->adfs()->active()->persist();

        $this->postJson('/sso/adfs/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(400);
    }

    /**
     * 400 user id is missing
     */
    public function testSsoAdfsStage1Controller_ErrorUserIdMissing(): void
    {
        SsoSettingsFactory::make()->adfs()->active()->persist();
        $this->postJson('/sso/adfs/login.json', ['user_id' => null]);
        $this->assertError(400);
    }

    /**
     * 400 user id is missing too
     */
    public function testSsoAdfsStage1Controller_ErrorUserIdMissing2(): void
    {
        SsoSettingsFactory::make()->adfs()->active()->persist();
        $this->postJson('/sso/adfs/login.json', []);
        $this->assertError(400);
    }

    /**
     * 400 user id is invalid
     */
    public function testSsoAdfsStage1Controller_ErrorUserIdInvalid(): void
    {
        SsoSettingsFactory::make()->adfs()->active()->persist();
        $this->postJson('/sso/adfs/login.json', ['user_id' => 'nope']);
        $this->assertError(400);
    }

    /**
     * 400 no active users
     */
    public function testSsoAdfsStage1Controller_ErrorNoActiveSettings(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->adfs()->draft()->persist();

        $this->postJson('/sso/adfs/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(400);
    }
}
