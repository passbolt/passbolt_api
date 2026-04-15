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
use Passbolt\Sso\Test\Factory\SsoKeysFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

class SsoKeysDeleteControllerTest extends SsoIntegrationTestCase
{
    /**
     * 200 valid ASCII key
     */
    public function testSsoKeysDeleteControllerTest_Success(): void
    {
        $user = UserFactory::make()->active()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();

        $this->logInAs($user);
        $this->deleteJson('/sso/keys/' . $key->id . '.json');
        $this->assertSuccess();
        $this->assertEquals(0, SsoKeysFactory::count());
    }

    /**
     * 403 user not logged in
     */
    public function testSsoKeysDeleteControllerTest_ErrorNotLoggedIn(): void
    {
        $this->deleteJson('/sso/keys/' . UuidFactory::uuid() . '.json');
        $this->assertAuthenticationError();
    }

    /**
     * 400 invalid id
     */
    public function testSsoKeysDeleteControllerTest_ErrorNotUuid(): void
    {
        $this->logInAsUser();
        $this->deleteJson('/sso/keys/nope.json');
        $this->assertError(400);
    }

    /**
     * 404 key not found
     */
    public function testSsoKeysDeleteControllerTest_ErrorValidation(): void
    {
        $this->logInAsUser();
        $this->deleteJson('/sso/keys/' . UuidFactory::uuid() . '.json');
        $this->assertError(404);
    }
}
