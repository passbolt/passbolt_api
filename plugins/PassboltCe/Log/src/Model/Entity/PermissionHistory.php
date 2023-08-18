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
 * @property string $aco
 * @property string $aco_foreign_key
 * @property string $aro
 * @property string|null $aro_foreign_key
 * @property int $type
 * @property \Passbolt\Log\Model\Entity\EntityHistory|null $entities_history
 * @property \App\Model\Entity\Group|null $group
 * @property \App\Model\Entity\Resource $resource
 * @property \App\Model\Entity\User|null $user
 * @property \App\Model\Entity\Group|null $permissions_history_group
 * @property \App\Model\Entity\User|null $permissions_history_user
 * @property \App\Model\Entity\Resource $permissions_history_resource
 */
class PermissionHistory extends Entity
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
        'aco' => false,
        'aco_foreign_key' => false,
        'aro' => false,
        'aro_foreign_key' => false,
        'type' => false,
    ];
}
