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
 * User Entity
 *
 * @property string $id
 * @property string $role_id
 * @property string $username
 * @property bool $active
 * @property bool $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $last_logged_in
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\FileStorage[] $file_storage
 * @property \App\Model\Entity\Gpgkey[] $gpgkeys
 * @property \App\Model\Entity\Profile[] $profiles
 * @property \App\Model\Entity\Profile $profile
 * @property \App\Model\Entity\GroupUser[] $groups_users
 * @property \Passbolt\Log\Model\Entity\EntityHistory[] $entities_history
 */
class User extends Entity
{
    /**
     * last_logged_in virtual field.
     * @var array
     */
    protected $_virtual = ['last_logged_in'];

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
        'id' => false,
        'username' => false,
        'active' => false,
        'deleted' => false,
        'role_id' => false,

        // associated data
        'profile' => false,
    ];
}
