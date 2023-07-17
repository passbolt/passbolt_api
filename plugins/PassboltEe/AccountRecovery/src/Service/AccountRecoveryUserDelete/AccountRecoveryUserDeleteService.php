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
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;

/**
 * Class AccountRecoveryUserDeleteService
 */
class AccountRecoveryUserDeleteService
{
    use LocatorAwareTrait;

    /**
     * @var \Cake\Http\ServerRequest
     */
    protected $request;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable
     */
    protected $AccountRecoveryPrivateKeys;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable
     */
    protected $AccountRecoveryPrivateKeyPasswords;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable
     */
    protected $AccountRecoveryRequests;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable
     */
    protected $AccountRecoveryUserSettings;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    /**
     * AccountRecoveryUserDeleteService constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryUserSettings = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryPrivateKeys = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryPrivateKeys');
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryPrivateKeyPasswords = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryPrivateKeyPasswords');
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryRequests = $this->fetchTable('Passbolt/AccountRecovery.AccountRecoveryRequests');
        /** @phpstan-ignore-next-line */
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
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
