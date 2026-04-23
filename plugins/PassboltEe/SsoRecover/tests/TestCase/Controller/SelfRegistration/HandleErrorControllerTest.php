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
 * @since         4.0.0
 */

namespace Passbolt\SsoRecover\Test\TestCase\Controller\SelfRegistration;

use Passbolt\SsoRecover\Test\Lib\SsoRecoverIntegrationTestCase;

/**
 * @covers \Passbolt\SsoRecover\Controller\SelfRegistration\HandleErrorController
 */
class HandleErrorControllerTest extends SsoRecoverIntegrationTestCase
{
    public function testHandleErrorController_Error_Json(): void
    {
        $this->getJson('/sso/recover/error.json');
        $this->assertError(400, 'not supported');
    }

    public function testHandleErrorController_Error_LoggedIn(): void
    {
        $this->logInAsUser();
        $this->get('/sso/recover/error');

        $this->assertResponseCode(403);

        $this->assertResponseContains('The user should not be logged in.');
    }

    public function testHandleErrorController_Error_EmailInQueryParam(): void
    {
        $this->get('/sso/recover/error');
        $this->assertResponseCode(400);
        $this->assertResponseContains('The email is required in URL parameters.');
    }

    public function testHandleErrorController_Error_InvalidEmail(): void
    {
        $this->get('/sso/recover/error?email=foo');
        $this->assertResponseCode(400);
        $this->assertResponseContains('The email is required in URL parameters.');
    }

    public function testHandleErrorController_Success(): void
    {
        $this->get('/sso/recover/error?email=ada@passbolt.local');
        $this->assertResponseOk();
        $this->assertResponseContains('The user does not exist.');
    }
}
