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
 * AccountRecoveryOrganizationPolicy Entity
 *
 * @property string $id
 * @property string $policy
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 * @property string $created_by
 * @property string $modified_by
 * @property string $public_key_id
 * @property \App\Model\Entity\User $creator
 *
 * @property \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey|null $account_recovery_organization_public_key
 */
class AccountRecoveryOrganizationPolicy extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * For security purposes, it is advised to set '*' to false
     * and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'policy' => false,
        'created' => false,
        'modified' => false,
        'deleted' => false,
        'created_by' => false,
        'modified_by' => false,

        // associated data
        'public_key_id' => false,
        'account_recovery_organization_public_key' => false,
    ];

    public const ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_IN = 'opt-in';
    public const ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_OUT = 'opt-out';
    public const ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY = 'mandatory';
    public const ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED = 'disabled';

    public const SUPPORTED_POLICIES = [
        self::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_IN,
        self::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_OUT,
        self::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY,
        self::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED,
    ];

    /**
     * @return bool true if policy is disabled
     */
    public function isDisabled(): bool
    {
        return $this->policy === self::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED;
    }

    /**
     * @return bool true if policy is set to mandatory
     */
    public function isMandatory(): bool
    {
        return $this->policy === self::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY;
    }
}
