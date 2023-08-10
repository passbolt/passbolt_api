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

class UsersIndexContainAccountRecoveryUserSettingsControllerTest extends AccountRecoveryIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
    }

    public function testUsersIndexControllerGetSuccess_ContainAccountRecoveryUserSetting()
    {
        $status = 'Foo';
        $nUsers = 5;
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting[] $settings */
        $settings = AccountRecoveryUserSettingFactory::make($nUsers)
            ->withUser(UserFactory::make()->user()->active())
            ->setField('status', $status)
            ->persist();
        $user = $settings[0]->user;

        $this->logInAs($user);
        $this->getJson('/users.json?contain[account_recovery_user_setting]=1&contain[foo]=1');
        $this->assertSuccess();
        $responseUsers = $this->_responseJsonBody;
        foreach ($responseUsers as $responseUser) {
            $this->assertNull($responseUser->account_recovery_user_setting ?? null);
        }

        $admin = $this->logInAsAdmin();
        $this->getJson('/users.json?contain[account_recovery_user_setting]=1&contain[foo]=1');
        $this->assertSuccess();

        $responseUsers = $this->_responseJsonBody;
        foreach ($responseUsers as $responseUser) {
            if ($responseUser->id === $admin->id) {
                // The admin has no settings
                $this->assertNull($responseUser->account_recovery_user_setting ?? null);
            } else {
                $this->assertSame(compact('status'), (array)$responseUser->account_recovery_user_setting);
            }
        }
    }

    public function testUsersIndexControllerGetSuccess_ContainAccountRecoveryUserSetting_Not_logged_In_Should_Not_Throw_500()
    {
        $this->getJson('/users.json?contain[account_recovery_user_setting]=1&contain[foo]=1');
        $this->assertAuthenticationError();
    }
}
