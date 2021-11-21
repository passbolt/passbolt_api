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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Auth;

use App\Model\Entity\User;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;

class AuthIsAuthenticatedControllerTest extends AppIntegrationTestCase
{
    public function testIsAuthenticatedNotLoggedIn()
    {
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseError();
        $this->assertTextContains('error', $this->_responseJsonHeader->status);
        $this->assertTextContains('Authentication is required to continue', $this->_responseJsonHeader->message);
    }

    public function testIsAuthenticatedLoggedIn()
    {
        $this->authenticateAs('ada');
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseOk();
        $this->assertInstanceOf(User::class, $this->getSession()->read('Auth.user'));
        $this->assertTextContains('success', $this->_responseJsonHeader->status);
    }

    /**
     * @covers \App\Middleware\SessionAuthPreventDeletedUsersMiddleware::process
     */
    public function testIsAuthenticatedSoftDeletedLoggedUserShouldBeForbiddenToRequestTheApi()
    {
        $user = UserFactory::make()->user()->deleted()->persist();

        $this->loginAs($user);
        $this->getJson('/auth/is-authenticated.json');
        $this->assertEmpty($this->getSession()->read());
        $this->assertAuthenticationError();
    }
}
