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
use App\Model\Table\AuthenticationTokensTable;
use Cake\Http\ServerRequest;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use InvalidArgumentException;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable;

/**
 * Class AccountRecoveryUserDeleteService
 */
class AccountRecoveryUserDeleteService
{
    use LocatorAwareTrait;

    /**
     * @var \Cake\Http\ServerRequest
     */
    protected ServerRequest $request;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable
     */
    protected AccountRecoveryPrivateKeysTable $AccountRecoveryPrivateKeys;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable
     */
    protected AccountRecoveryPrivateKeyPasswordsTable $AccountRecoveryPrivateKeyPasswords;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable
     */
    protected AccountRecoveryRequestsTable $AccountRecoveryRequests;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable
     */
    protected AccountRecoveryUserSettingsTable $AccountRecoveryUserSettings;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected AuthenticationTokensTable $AuthenticationTokens;

    /**
     * AccountRecoveryUserDeleteService constructor.
     */
    public function __construct()
    {
        $this->AccountRecoveryUserSettings = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
        $this->AccountRecoveryPrivateKeys = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryPrivateKeys');
        $this->AccountRecoveryPrivateKeyPasswords = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryPrivateKeyPasswords');
        $this->AccountRecoveryRequests = $this->fetchTable('Passbolt/AccountRecovery.AccountRecoveryRequests');
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
    }

    /**
     * @param string $userId uuid
     * @return void
     */
    public function deleteInfo(string $userId): void
    {
        if (!Validation::uuid($userId)) {
            throw new InvalidArgumentException('The user id must be a uuid.');
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
        $this->AccountRecoveryRequests->updateQuery()
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
        $this->AuthenticationTokens->updateQuery()
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
