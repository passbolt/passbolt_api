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
 * @since         2.13.0
 */
namespace Passbolt\Folders\Model\Entity;

use Cake\ORM\Entity;

/**
 * Folder Entity
 *
 * @property string $id
 * @property string $name
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $created_by
 * @property string $modified_by
 * @property \App\Model\Entity\User|null $creator
 * @property \App\Model\Entity\User|null $modifier
 * @property \App\Model\Entity\Permission|null $permission
 * @property \App\Model\Entity\Permission[] $permissions
 * @property int|null $folder_parent_id
 * @property \Passbolt\Folders\Model\Entity\FoldersRelation[] $folders_relations
 * @property \Passbolt\Folders\Model\Entity\Folder[] $children_folders
 * @property \App\Model\Entity\Resource[] $children_resources
 * @property \Cake\ORM\Entity $folders_history
 */
class Folder extends Entity
{
    public const ROOT_ID = false;
    public const MAX_NAME_LENGTH = 256;

    /**
     * List of property names that should **not** be included in JSON or Array
     * representations of this Entity.
     *
     * @var string[]
     */
    protected $_hidden = [
        '_joinData',
    ];

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
        'name' => false,
        'created' => false,
        'modified' => false,
        'created_by' => false,
        'modified_by' => false,
    ];
}
