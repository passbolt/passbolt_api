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
 * FoldersRelation Entity
 *
 * @property string $id
 * @property string $foreign_id
 * @property string $foreign_model
 * @property string|null $folder_parent_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $user_id
 * @property \App\Model\Entity\Resource $resource
 * @property \Passbolt\Folders\Model\Entity\Folder $folder
 * @property \Passbolt\Folders\Model\Entity\Folder|null $folders_parent
 * @property \App\Model\Entity\User $user
 * @property \Cake\ORM\Entity $folders_relations_history
 */
class FoldersRelation extends Entity
{
    public const FOREIGN_MODEL_FOLDER = 'Folder';
    public const FOREIGN_MODEL_RESOURCE = 'Resource';
    public const ROOT = null;

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
        'foreign_id' => false,
        'foreign_model' => false,
        'user_id' => false,
        'folder_parent_id' => false,
        'created' => false,
        'modified' => false,
    ];
}
