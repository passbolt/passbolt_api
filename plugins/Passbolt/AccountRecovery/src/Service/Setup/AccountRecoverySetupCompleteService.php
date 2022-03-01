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

namespace Passbolt\AccountRecovery\Service\Setup;

use App\Error\Exception\ValidationException;
use App\Model\Entity\User;
use App\Service\Setup\SetupCompleteService;
use Cake\Http\ServerRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable $AccountRecoveryUserSettings
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable $AccountRecoveryPrivateKeys
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable $AccountRecoveryPrivateKeyPasswords
 */
class AccountRecoverySetupCompleteService extends SetupCompleteService
{
    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable
     */
    public $AccountRecoveryUserSettings;

    /**
     * @param \Cake\Http\ServerRequest $request Server request
     */
    public function __construct(ServerRequest $request)
    {
        parent::__construct($request);
        $this->loadModel(AccountRecoveryUserSettingsTable::class);
        $this->loadModel(AccountRecoveryPrivateKeysTable::class);
        $this->loadModel(AccountRecoveryPrivateKeyPasswordsTable::class);
    }

    /**
     * @inheritDoc
     */
    public function complete(string $userId): User
    {
        $userSetting = $this->validateAccountRecoveryUserSetting($userId);
        if ($userSetting->isApproved()) {
            $privateKey = $this->validateAccountRecoveryPrivateKey($userId);
        }

        // TODO: add account recovery data to the user entity

        $user = parent::complete($userId);

        $this->saveAccountRecoveryUserSetting($userSetting);
        if (isset($privateKey)) {
            $this->saveAccountRecoveryPrivateKey($privateKey);
        }

        return $user;
    }

    /**
     * @param string $userId User ID
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting
     */
    protected function validateAccountRecoveryUserSetting(string $userId): AccountRecoveryUserSetting
    {
        $status = $this->request->getData('account_recovery_user_setting.status');
        $setting = $this->AccountRecoveryUserSettings->newEntity([
            'status' => $status,
            'user_id' => $userId,
            'created_by' => $userId,
            'modified_by' => $userId,
        ]);

        if ($setting->hasErrors()) {
            if ($setting->getError('status')['inList'] ?? false) {
                $msg = $setting->getError('status')['inList'];
            } else {
                $msg = __('The account recovery user setting is not valid.');
            }
            throw new ValidationException(
                $msg,
                $setting,
                $this->AccountRecoveryUserSettings
            );
        }

        return $setting;
    }

    /**
     * @param string $userId User ID
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey
     */
    protected function validateAccountRecoveryPrivateKey(string $userId): AccountRecoveryPrivateKey
    {
        $data = $this->request->getData('account_recovery_private_key.data');
        $privateKeyPasswords = $this->request->getData('account_recovery_private_key_passwords');
        foreach ($privateKeyPasswords as $i => $pkp) {
            $privateKeyPasswords[$i]['created_by'] = $userId;
            $privateKeyPasswords[$i]['modified_by'] = $userId;
        }

        $privateKey = $this->AccountRecoveryPrivateKeys->newEntity([
            'user_id' => $userId,
            'data' => $data,
            'account_recovery_private_key_passwords' => $privateKeyPasswords,
            'created_by' => $userId,
            'modified_by' => $userId,
        ]);

        if ($privateKey->hasErrors()) {
            throw new ValidationException(
                __('The account recovery private key is not valid.'),
                $privateKey,
                $this->AccountRecoveryPrivateKeys
            );
        }

        return $privateKey;
    }

    /**
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting $userSetting User setting
     * @return void
     * @throws \Cake\ORM\Exception\PersistenceFailedException
     */
    protected function saveAccountRecoveryUserSetting(AccountRecoveryUserSetting $userSetting): void
    {
        $this->AccountRecoveryUserSettings->saveOrFail($userSetting);
    }

    /**
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey $privateKey Private key
     * @return void
     * @throws \Cake\ORM\Exception\PersistenceFailedException
     */
    protected function saveAccountRecoveryPrivateKey(AccountRecoveryPrivateKey $privateKey): void
    {
        $this->AccountRecoveryPrivateKeys->saveOrFail($privateKey);
    }
}
