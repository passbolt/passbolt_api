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
use Passbolt\Sso\Test\Factory\SsoKeysFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

class SsoKeysCreateControllerTest extends SsoIntegrationTestCase
{
    /**
     * 200 valid ASCII key
     */
    public function testSsoKeysCreateControllerTest_Success(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->logInAs($user);
        $this->postJson('/sso/keys.json', ['data' => SsoKeysFactory::DATA]);
        $this->assertSuccess();
        $result = $this->_responseJsonBody;

        $this->assertEquals(SsoKeysFactory::DATA, $result->data);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertEquals($user->id, $result->created_by);
        $this->assertEquals($user->id, $result->modified_by);
        $this->assertEquals($user->id, $result->modified_by);
    }

    /**
     * 403 user not logged in
     */
    public function testSsoKeysCreateControllerTest_ErrorNotLoggedIn(): void
    {
        $this->postJson('/sso/keys.json', []);
        $this->assertAuthenticationError();
    }

    /**
     * 400 no dat provided
     */
    public function testSsoKeysCreateControllerTest_ErrorNotData(): void
    {
        $this->logInAsUser();
        $this->postJson('/sso/keys.json', []);
        $this->assertError(400, 'Information about the key is required.');
    }

    /**
     * 400 invalid data provided
     */
    public function testSsoKeysCreateControllerTest_ErrorValidation(): void
    {
        $this->logInAsUser();
        $this->postJson('/sso/keys.json', ['data' => '🔥']);
        $this->assertError(400, 'The SSO key could not be saved.');
    }
}
