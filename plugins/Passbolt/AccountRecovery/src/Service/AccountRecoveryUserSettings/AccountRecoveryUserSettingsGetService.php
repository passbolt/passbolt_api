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

namespace Passbolt\AccountRecovery\Service\AccountRecoveryUserSettings;

use Cake\Datasource\ModelAwareTrait;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable $AccountRecoveryUserSettings
 */
class AccountRecoveryUserSettingsGetService
{
    use ModelAwareTrait;

    /**
     * @param string $userId User ID
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting|null
     */
    public function get(string $userId): ?AccountRecoveryUserSetting
    {
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting|null $setting */
        $setting = $this->AccountRecoveryUserSettings->find()
            ->select('status')
            ->where([$this->AccountRecoveryUserSettings->aliasField('user_id') => $userId])
            ->first();

        return $setting;
    }
}
