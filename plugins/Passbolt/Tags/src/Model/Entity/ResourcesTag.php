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
namespace Passbolt\Tags\Model\Entity;

use Cake\ORM\Entity;

/**
 * ResourcesTag Entity
 *
 * @property string $id
 * @property string $resource_id
 * @property string $tag_id
 * @property string $user_id
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Resource $resource
 * @property \App\Model\Entity\Tag $tag
 * @property \App\Model\Entity\User $user
 */
class ResourcesTag extends Entity
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
        '*' => false
    ];
}
