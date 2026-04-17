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

use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

class SsoSuccessDryRunControllerTest extends SsoIntegrationTestCase
{
    public function testSsoSuccessDryRunController_ErrorNoAuth(): void
    {
        $this->get('/sso/login/dry-run/success');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/auth/login');
    }

    public function testSsoSuccessDryRunController_ErrorJson(): void
    {
        $this->logInAsAdmin();

        $this->getJson('/sso/login/dry-run/success.json');
        $this->assertError(400, 'not supported');
    }

    public function testSsoSuccessDryRunController_ErrorNoToken(): void
    {
        $this->logInAsAdmin();

        $this->get('/sso/login/dry-run/success');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The token is required in URL parameters.');
    }

    public function testSsoSuccessDryRunController_ErrorInvalidToken(): void
    {
        $this->logInAsAdmin();

        $this->get('/sso/login/dry-run/success?token=nope');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The token is required in URL parameters.');
    }
}
