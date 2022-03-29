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

namespace Passbolt\AccountRecovery\Model\Entity;

use Cake\ORM\Entity;

/**
 * AccountRecoveryRequest Entity
 *
 * @property string $id
 * @property string|null $user_id
 * @property string|null $armored_key
 * @property string|null $fingerprint
 * @property string $authentication_token_id
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $created_by
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\AuthenticationToken $authentication_token
 * @property \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey $account_recovery_private_key
 * @property \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse[] $account_recovery_responses
 */
class AccountRecoveryRequest extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => false,
        'armored_key' => false,
        'fingerprint' => false,
        'authentication_token_id' => false,
        'status' => false,
        'created' => false,
        'modified' => false,
        'created_by' => false,
        'modified_by' => false,

        // Associations
        'user' => false,
        'authentication_token' => false,
    ];

    public const ACCOUNT_RECOVERY_REQUEST_PENDING = 'pending';
    public const ACCOUNT_RECOVERY_REQUEST_REJECTED = 'rejected';
    public const ACCOUNT_RECOVERY_REQUEST_APPROVED = 'approved';
    public const ACCOUNT_RECOVERY_REQUEST_COMPLETED = 'completed';

    public const ACCOUNT_RECOVERY_REQUEST_STATUSES = [
        self::ACCOUNT_RECOVERY_REQUEST_PENDING,
        self::ACCOUNT_RECOVERY_REQUEST_REJECTED,
        self::ACCOUNT_RECOVERY_REQUEST_APPROVED,
        self::ACCOUNT_RECOVERY_REQUEST_COMPLETED,
    ];

    /**
     * @return bool if request status is pending returns true
     */
    public function isPending(): bool
    {
        return $this->status === self::ACCOUNT_RECOVERY_REQUEST_PENDING;
    }

    /**
     * @return bool if request status is approved returns true
     */
    public function isApproved(): bool
    {
        return $this->status === self::ACCOUNT_RECOVERY_REQUEST_APPROVED;
    }

    /**
     * @return bool if request status is completed returns true
     */
    public function isCompleted(): bool
    {
        return $this->status === self::ACCOUNT_RECOVERY_REQUEST_COMPLETED;
    }

    /**
     * @return bool if request status is rejected returns true
     */
    public function isRejected(): bool
    {
        return $this->status === self::ACCOUNT_RECOVERY_REQUEST_REJECTED;
    }
}
