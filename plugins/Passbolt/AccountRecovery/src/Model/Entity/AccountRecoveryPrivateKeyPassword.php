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
 * AccountRecoveryPrivateKeyPassword Entity
 *
 * @property string $id
 * @property string $recipient_fingerprint
 * @property string $recipient_foreign_model
 * @property string $private_key_id
 * @property string $data
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $created_by
 * @property string $modified_by
 *
 * @property \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey $account_recovery_private_key
 */
class AccountRecoveryPrivateKeyPassword extends Entity
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
        'recipient_fingerprint' => false,
        'recipient_foreign_model' => false,
        'private_key_id' => false,
        'data' => false,
        'created' => false,
        'modified' => false,
        'created_by' => false,
        'modified_by' => false,
        'account_recovery_private_key' => false,
    ];

    public const RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY = 'AccountRecoveryOrganizationKey';
    //public const RECIPIENT_FOREIGN_MODEL_RECOVERY_CONTACT = 'AccountRecoveryContact';
    public const ALLOWED_RECIPIENT_FOREIGN_MODELS = [
        self::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
        //self::RECIPIENT_FOREIGN_MODEL_RECOVERY_CONTACT
    ];
}
