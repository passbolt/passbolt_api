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

use Authentication\IdentityInterface;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property string $id
 * @property string $role_id
 * @property string $username
 * @property bool $active
 * @property bool $deleted
 * @property \Cake\I18n\FrozenTime|null $disabled
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $last_logged_in
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\Gpgkey[] $gpgkeys
 * @property \App\Model\Entity\Profile[] $profiles
 * @property \App\Model\Entity\Profile|null $profile
 * @property \App\Model\Entity\GroupsUser[] $groups_users
 * @property \Passbolt\Log\Model\Entity\EntityHistory[] $entities_history
 * @property \App\Model\Entity\AuthenticationToken[] $authentication_tokens
 * @property \App\Model\Entity\Gpgkey|null $gpgkey
 * @property \App\Model\Entity\Group[] $groups
 * @property \App\Model\Entity\Permission[] $permissions
 * @property \Passbolt\Log\Model\Entity\ActionLog[] $action_logs
 * @property \Passbolt\AccountSettings\Model\Entity\AccountSetting|string|null $locale
 */
class User extends Entity implements IdentityInterface
{
    /**
     * last_logged_in virtual field.
     *
     * @var array<string>
     */
    protected $_virtual = ['last_logged_in'];

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
        'username' => false,
        'active' => false,
        'deleted' => false,
        'disabled' => false,
        'role_id' => false,

        // associated data
        'profile' => false,
    ];

    /**
     * Authentication\IdentityInterface method
     *
     * @return string|null
     */
    public function getIdentifier(): ?string
    {
        return $this->id;
    }

    /**
     * Authentication\IdentityInterface method
     *
     * @return self
     */
    public function getOriginalData(): self
    {
        return $this;
    }

    /**
     * @return bool if user disabled datetime field is set and in the past
     */
    public function isDisabled(): bool
    {
        if (!isset($this->disabled)) {
            return false;
        } else {
            return $this->disabled->isPast();
        }
    }

    /**
     * In future deleted may become a date, so accessing this function instead of the props is better
     *
     * @return bool if user is softdeleted
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * In future active may be deprecated for activated that will become a date
     * So accessing this function instead of the props is better
     *
     * @return bool if user is active
     */
    public function isActive(): bool
    {
        return $this->active;
    }
}
