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

use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;

class AccountRecoveryUserSettingsDeleteService
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable $AccountRecoveryUserSettings
     */
    private $AccountRecoveryUserSettings;

    /**
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting|null
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line  */
        $this->AccountRecoveryUserSettings = $this->fetchTable('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
    }

    /**
     * @return \Cake\Datasource\ResultSetInterface|array<\Cake\Datasource\EntityInterface>|false Entities list
     *   on success, false on failure.
     */
    public function deleteAllRejected()
    {
        $entities = $this->AccountRecoveryUserSettings->find()
            ->where(['status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED])
            ->all();

        return $this->AccountRecoveryUserSettings->deleteMany($entities);
    }
}
