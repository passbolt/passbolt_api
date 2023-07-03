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
 * @since         2.0.0
 */
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Role Entity
 *
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User[] $users
 * @property \Cake\ORM\Entity[] $controller_logs
 */
class Role extends Entity
{
    // Default roles
    public const GUEST = 'guest';
    public const USER = 'user';
    public const ADMIN = 'admin';

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
        'name' => false,
        'description' => false,
        'created' => false,
        'modified' => false,
    ];

    /**
     * @return bool if role name is guest returns true
     */
    public function isGuest(): bool
    {
        return $this->name === self::GUEST;
    }

    /**
     * @return bool if role name is user returns true
     */
    public function isUser(): bool
    {
        return $this->name === self::USER;
    }

    /**
     * @return bool if role name is admin returns true
     */
    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }
}
