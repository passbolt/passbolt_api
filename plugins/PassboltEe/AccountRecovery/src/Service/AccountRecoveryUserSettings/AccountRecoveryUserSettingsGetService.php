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

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;

/**
 * Class AccountRecoveryUserSettingsGetService
 */
class AccountRecoveryUserSettingsGetService
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable
     */
    protected $AccountRecoveryUserSettings;

    /**
     * @return void
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryUserSettings = $this->fetchTable('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
    }

    /**
     * @param string $userId uuid
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting|null
     */
    public function get(string $userId): ?AccountRecoveryUserSetting
    {
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting|null $setting */
        $setting = $this->query($userId)->first();

        return $setting;
    }

    /**
     * @param string $userId uuid
     * @throws \Cake\Http\Exception\NotFoundException
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting
     */
    public function getOrFail(string $userId): AccountRecoveryUserSetting
    {
        try {
            /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting $setting */
            $setting = $this->query($userId)->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The user is not enrolled in the account recovery program.'));
        }

        return $setting;
    }

    /**
     * @param string $userId uuid
     * @return \Cake\ORM\Query
     */
    protected function query(string $userId): Query
    {
        return $this->AccountRecoveryUserSettings->find()
            ->select('status')
            ->where([$this->AccountRecoveryUserSettings->aliasField('user_id') => $userId]);
    }
}
