<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
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
 * @property string $created_by
 * @property string $modified_by
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\AuthenticationToken[] $authentication_tokens
 * @property \App\Model\Entity\ControllerLog[] $controller_logs
 * @property \App\Model\Entity\Favorite[] $favorites
 * @property \App\Model\Entity\FileStorage[] $file_storage
 * @property \App\Model\Entity\Gpgkey[] $gpgkeys
 * @property \App\Model\Entity\Profile[] $profiles
 * @property \App\Model\Entity\Secret[] $secrets
 * @property \App\Model\Entity\UsersResourcesPermission[] $users_resources_permissions
 * @property \App\Model\Entity\Group[] $groups
 */
class User extends Entity
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
        '*' => true,
        'id' => false
    ];
}
