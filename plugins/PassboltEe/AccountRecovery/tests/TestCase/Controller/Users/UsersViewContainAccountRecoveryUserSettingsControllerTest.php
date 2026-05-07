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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\Users;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class UsersViewContainAccountRecoveryUserSettingsControllerTest extends AccountRecoveryIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
    }

    public function testUsersViewControllerGetSuccess_ContainAccountRecoveryUserSetting()
    {
        $status = 'Foo';
        $setting = AccountRecoveryUserSettingFactory::make()
            ->withUser(UserFactory::make()->user())
            ->setField('status', $status)
            ->persist();
        $user = $setting->user;

        $this->logInAs($user);

        $this->getJson('/users/' . $user->id . '.json?contain[account_recovery_user_setting]=1&contain[foo]=1');
        $this->assertSuccess();

        $this->assertSame(compact('status'), (array)$this->_responseJsonBody->account_recovery_user_setting);
    }

    public function testUsersViewControllerGetSuccess_ContainAccountRecoveryUserSetting_Fails_If_Not_LoggedIn_User()
    {
        $status = 'Foo';
        $setting = AccountRecoveryUserSettingFactory::make()
            ->withUser(UserFactory::make()->user())
            ->setField('status', $status)
            ->persist();
        $user = $setting->user;

        $this->logInAsUser();

        $this->getJson('/users/' . $user->id . '.json?contain[account_recovery_user_setting]=1&contain[foo]=1');
        $this->assertSuccess();

        $this->assertNull($this->_responseJsonBody->account_recovery_user_setting ?? null);
    }
}
