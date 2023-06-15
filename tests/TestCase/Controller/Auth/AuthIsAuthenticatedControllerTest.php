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
use Passbolt\Log\Test\Factory\ActionLogFactory;

class AuthIsAuthenticatedControllerTest extends AppIntegrationTestCase
{
    /**
     * 200 if user is logged in
     * Also check that the action is not logged
     */
    public function testAuthIsAuthenticatedController_Success_LoggedIn(): void
    {
        $isLogEnabled = $this->isFeaturePluginEnabled('Log');
        $this->enableFeaturePlugin('Log');

        $this->logInAsUser();
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseOk();
        $this->assertInstanceOf(User::class, $this->getSession()->read('Auth.user'));
        $this->assertTextContains('success', $this->_responseJsonHeader->status);

        $this->assertSame(0, ActionLogFactory::count());
        if (!$isLogEnabled) {
            $this->disableFeaturePlugin('Log');
        }
    }

    public function testAuthIsAuthenticatedController_Error_NotLoggedIn(): void
    {
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseError();
        $this->assertTextContains('error', $this->_responseJsonHeader->status);
        $this->assertTextContains('Authentication is required to continue', $this->_responseJsonHeader->message);
    }

    /**
     * @covers \App\Middleware\SessionAuthPreventDeletedUsersMiddleware::process
     */
    public function testAuthIsAuthenticatedController_Error_SoftDeletedLoggedUserShouldBeForbiddenToRequestTheApi(): void
    {
        $user = UserFactory::make()->user()->deleted()->persist();

        $this->loginAs($user);
        $this->getJson('/auth/is-authenticated.json');
        $this->assertEmpty($this->getSession()->read());
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testAuthIsAuthenticatedController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->get('/auth/is-authenticated');
        $this->assertResponseCode(404);
    }
}
