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
 * AccountRecoveryUserSetting Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $created_by
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User $user
 * @property \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey $account_recovery_private_key
 */
class AccountRecoveryUserSetting extends Entity
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
        'status' => false,
        'created' => false,
        'modified' => false,
        'created_by' => false,
        'modified_by' => false,
        'user' => false,
        'account_recovery_private_key' => false,
    ];

    public const ACCOUNT_RECOVERY_USER_SETTING_REJECTED = 'rejected';
    public const ACCOUNT_RECOVERY_USER_SETTING_APPROVED = 'approved';

    public const ACCOUNT_RECOVERY_USER_SETTING_STATUSES = [
        self::ACCOUNT_RECOVERY_USER_SETTING_REJECTED,
        self::ACCOUNT_RECOVERY_USER_SETTING_APPROVED,
    ];

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->status === self::ACCOUNT_RECOVERY_USER_SETTING_APPROVED;
    }

    /**
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->status === self::ACCOUNT_RECOVERY_USER_SETTING_REJECTED;
    }
}
