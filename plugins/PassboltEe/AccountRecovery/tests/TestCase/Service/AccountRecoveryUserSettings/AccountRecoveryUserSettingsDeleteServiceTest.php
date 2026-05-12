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

namespace Passbolt\AccountRecovery\Test\TestCase\Service\AccountRecoveryUserSettings;

use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Service\AccountRecoveryUserSettings\AccountRecoveryUserSettingsDeleteService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryUserSettingsDeleteServiceTest extends AccountRecoveryTestCase
{
    public function testAccountRecoveryUserSettingsDeleteService_Success()
    {
        AccountRecoveryUserSettingFactory::make(2)
            ->approved()
            ->persist();

        AccountRecoveryUserSettingFactory::make(3)
            ->rejected()
            ->persist();

        $service = new AccountRecoveryUserSettingsDeleteService();
        $service->deleteAllRejected();

        $this->assertEquals(0, AccountRecoveryUserSettingFactory::find()->where(['status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED])->count());
        $this->assertEquals(2, AccountRecoveryUserSettingFactory::find()->where(['status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED])->count());
    }

    public function testAccountRecoveryUserSettingsDeleteService_SuccessEmpty()
    {
        $service = new AccountRecoveryUserSettingsDeleteService();
        $service->deleteAllRejected();
        $this->assertEquals(0, AccountRecoveryUserSettingFactory::count());
    }
}
