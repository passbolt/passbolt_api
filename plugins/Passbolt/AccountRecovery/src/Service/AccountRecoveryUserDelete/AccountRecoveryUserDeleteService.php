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

namespace Passbolt\AccountRecovery\Service\AccountRecoveryUserDelete;

use App\Model\Entity\AuthenticationToken;
use Cake\Datasource\ModelAwareTrait;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable $AccountRecoveryUserSettings
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable $AccountRecoveryPrivateKeyPasswords // phpcs:ignore
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable $AccountRecoveryPrivateKeys
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable $AccountRecoveryRequests
 */
class AccountRecoveryUserDeleteService
{
    use ModelAwareTrait;

    /**
     * @var \Cake\Http\ServerRequest
     */
    protected $request;

    /**
     * AccountRecoveryUserDeleteService constructor.
     */
    public function __construct()
    {
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryPrivateKeys');
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryPrivateKeyPasswords');
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryRequests');
        $this->loadModel('AuthenticationTokens');
    }

    /**
     * @param string $userId uuid
     * @return void
     */
    public function deleteInfo(string $userId): void
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException('The user id must be a uuid.');
        }

        // Delete user settings
        $this->AccountRecoveryUserSettings->deleteAll(['user_id' => $userId]);

        // Check for presence of private keys
        $keys = $this->AccountRecoveryPrivateKeys->find()
            ->where(['user_id' => $userId])
            ->all()
            ->toArray();
        $keyIds = Hash::extract($keys, '{n}.id');

        // Delete private key and passwords if any
        if (count($keyIds)) {
            $this->AccountRecoveryPrivateKeys->deleteAll([
                'user_id' => $userId,
            ]);
            $this->AccountRecoveryPrivateKeyPasswords->deleteAll([
                'private_key_id IN' => $keyIds,
            ]);
        }

        // Update pending requests
        $this->AccountRecoveryRequests->query()
            ->update()
            ->set([
                'status' => AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_REJECTED,
                'modified_by' => $userId, // TODO - Get UAC from event
            ])
            ->where([
                'status IN' => [
                    AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_PENDING,
                    AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_APPROVED,
                ],
                'user_id' => $userId,
            ])
            ->execute();

        // Deactivate all previous active tokens
        $this->AuthenticationTokens->query()
            ->update()
            ->set([
                'active' => false,
            ])
            ->where([
                'active' => true,
                'type' => AuthenticationToken::TYPE_RECOVER,
                'user_id' => $userId,
            ])
            ->execute();
    }
}
