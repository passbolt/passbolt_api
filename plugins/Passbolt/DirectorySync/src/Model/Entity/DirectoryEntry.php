<?php
declare(strict_types=1);

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
 * @since         2.2.0
 */

namespace Passbolt\DirectorySync\Model\Entity;

use Cake\ORM\Entity;
use Passbolt\DirectorySync\Utility\Alias;

/**
 * DirectoryEntry Entity
 *
 * @property string $id
 * @property string $foreign_model
 * @property string $foreign_key
 * @property string $directory_name distinguished name
 * @property \Cake\I18n\FrozenTime $directory_created
 * @property \Cake\I18n\FrozenTime $directory_modified
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class DirectoryEntry extends Entity
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
        'foreign_model' => false,
        'foreign_key' => false,
        'directory_name' => false,
        'directory_created' => false,
        'directory_modified' => false,
        'created' => false,
        'modified' => false,
    ];

    /**
     * Get associated entity.
     *
     * @return \App\Model\Entity\Group|\Passbolt\DirectorySync\Model\Entity\User|null
     */
    public function getAssociatedEntity()
    {
        if ($this->foreign_model == Alias::MODEL_GROUPS && isset($this->group)) {
            return $this->group;
        }

        if ($this->foreign_model == Alias::MODEL_USERS && isset($this->user)) {
            return $this->user;
        }

        return null;
    }
}
