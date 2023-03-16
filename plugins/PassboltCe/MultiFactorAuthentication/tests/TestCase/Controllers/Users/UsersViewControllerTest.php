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
 * @since         3.12.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Users;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;

class UsersViewControllerTest extends MfaIntegrationTestCase
{
    /**
     * @return void
     */
    public function testMfaUsersView_AssertThatUserCanSeeIsMfaEnabled()
    {
        RoleFactory::make()->guest()->persist();
        $this->logInAsUser();
        $this->getJson('/users/me.json');
        $this->assertSuccess();
        $this->assertObjectHasAttribute('is_mfa_enabled', $this->_responseJsonBody);
        $this->assertFalse($this->_responseJsonBody->is_mfa_enabled);
    }

    /**
     * @return void
     */
    public function testMfaUsersView_AssertThatAdminCanSeeOtherUserIsMfaEnabled()
    {
        RoleFactory::make()->guest()->persist();
        $this->logInAsAdmin();
        $user = UserFactory::make()->user()->persist();
        $this->getJson("/users/{$user->get('id')}.json");
        $this->assertSuccess();
        $this->assertObjectHasAttribute('is_mfa_enabled', $this->_responseJsonBody);
        $this->assertFalse($this->_responseJsonBody->is_mfa_enabled);
    }

    /**
     * @return void
     */
    public function testMfaUsersView_AssertThatUserCannotSeeOtherIsMfaEnabled()
    {
        RoleFactory::make()->guest()->persist();
        $this->logInAsUser();
        $user = UserFactory::make()->user()->persist();
        $this->getJson("/users/{$user->get('id')}.json");
        $this->assertSuccess();
        $this->assertObjectNotHasAttribute('is_mfa_enabled', $this->_responseJsonBody);
    }
}
