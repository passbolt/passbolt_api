<?php
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
 * @since         2.0.0
 */

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Resource Entity
 *
 * @property string $id
 * @property string $name
 * @property string $username
 * @property string $uri
 * @property string $description
 * @property bool $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $created_by
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User $creator
 * @property \App\Model\Entity\User $modifier
 * @property \App\Model\Entity\Permission $permission
 * @property \App\Model\Entity\Permission[] $permissions
 * @property \App\Model\Entity\Secret[] $secrets
 */
class Resource extends Entity
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
        'name' => false,
        'username' => false,
        'uri' => false,
        'description' => false,
        'deleted' => false,
        'created' => false,
        'modified' => false,
        'created_by' => false,
        'modified_by' => false,

        // Associated entities
        'creator' => false,
        'modifier' => false,
        'permission' => false,
        'permissions' => false,
        'secrets' => false
    ];
}
