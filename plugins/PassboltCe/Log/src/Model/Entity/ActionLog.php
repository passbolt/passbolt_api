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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\Log\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property string $id
 * @property string|null $user_id
 * @property string $action_id
 * @property string $context
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \App\Model\Entity\User|null $user
 * @property \Passbolt\Log\Model\Entity\Action $action
 * @property \Passbolt\Log\Model\Entity\EntityHistory[] $entities_history
 */
class ActionLog extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'id' => false,
        'user_id' => false,
        'action_id' => false,
        'context' => false,
        'status' => false,
        'created' => false,
    ];

    /**
     * Returns true if the status is 1
     *
     * @return bool
     */
    public function isStatusSuccess(): bool
    {
        return $this->get('status') == 1;
    }

    /**
     * Returns false if the status is not 1
     *
     * @return bool
     */
    public function isStatusError(): bool
    {
        return !$this->isStatusSuccess();
    }
}
