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
 * Permission Entity
 *
 * @property string $id
 * @property string $aco
 * @property string $aco_foreign_key
 * @property string $aro
 * @property string $aro_foreign_key
 * @property int $type
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Permission extends Entity
{
    /**
     * The types of permissions.
     */
    public const READ = 1;
    public const UPDATE = 7;
    public const OWNER = 15;

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
        'aco' => false,
        'aco_foreign_key' => false,
        'aro' => false,
        'aro_foreign_key' => false,
        'type' => false,
        'created' => false,
        'modified' => false,

        // Associated entities
        'group' => false,
        'user' => false,
    ];

    /**
     * @param string|null $acoForeignKey ACO foreign key
     * @return string
     */
    protected function _getAcoForeignKey(?string $acoForeignKey = null)
    {
        if ($this->acoObject) {
            return $this->acoObject->getAcoForeignKey();
        }

        return $acoForeignKey;
    }

    /**
     * @param string|null $aco Access Control Object
     * @return string
     */
    protected function _getAco(?string $aco = null)
    {
        if ($this->acoObject) {
            return $this->acoObject->getAcoType();
        }

        return $aco;
    }

    /**
     * @param string|null $aroForeignKey ARO foreign key
     * @return string
     */
    protected function _getAroForeignKey(?string $aroForeignKey = null)
    {
        if ($this->aroObject) {
            return $this->aroObject->getAroForeignKey();
        }

        return $aroForeignKey;
    }

    /**
     * @param string|null $aro Access Request Object
     * @return string
     */
    protected function _getAro(?string $aro = null)
    {
        if ($this->aroObject) {
            return $this->aroObject->getAroType();
        }

        return $aro;
    }
}
