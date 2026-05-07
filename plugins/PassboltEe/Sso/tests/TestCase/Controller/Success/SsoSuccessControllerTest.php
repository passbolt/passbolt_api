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

namespace Passbolt\Sso\Test\TestCase\Controller\Success;

use App\Utility\UuidFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

class SsoSuccessControllerTest extends SsoIntegrationTestCase
{
    public function testSsoSuccessController_ErrorJson(): void
    {
        $this->getJson('/sso/login/success.json');
        $this->assertError(400, 'not supported');
    }

    public function testSsoSuccessController_ErrorLoggedIn(): void
    {
        $this->logInAsUser();

        $this->get('/sso/login/success');
        $this->assertResponseCode(403);
        $this->assertResponseContains('The user should not be logged in.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testSsoSuccessController_ErrorNoToken(): void
    {
        $this->get('/sso/login/success');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The token is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testSsoSuccessController_ErrorInvalidToken(): void
    {
        $this->get('/sso/login/success?token=nope');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The token is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testSsoSuccessController_Succes(): void
    {
        $token = UuidFactory::uuid();
        $this->get("/sso/login/success?token={$token}");

        $this->assertResponseOk();
        $this->assertResponseContains('Single-sign on was a success');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-success"');
    }
}
