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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Model\Entity;

use Cake\ORM\Entity;
use Passbolt\Scim\Utility\ScimResources;

/**
 * ScimEntry Entity
 *
 * @property string $id
 * @property string $foreign_key
 * @property string $foreign_model
 * @property string|null $external_identifier
 * @property string|null $scim_name
 * @property \Cake\I18n\FrozenTime|null $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $created_by
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User|null $user
 * @property \Passbolt\Scim\Model\Entity\User|null $group
 */
class ScimEntry extends Entity
{
    public const FOREIGN_MODEL_USERS = 'users';
    public const FOREIGN_MODEL_GROUPS = 'groups';

    /**
     * Map between resource types and foreign models
     */
    public const MODEL_MAP = [
        ScimResources::USERS => self::FOREIGN_MODEL_USERS,
        ScimResources::GROUPS => self::FOREIGN_MODEL_GROUPS,
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
    protected array $_accessible = [
        'foreign_key' => false,
        'foreign_model' => false,
        'external_identifier' => false,
        'scim_name' => false,
        'deleted' => false,
        'created' => false,
        'modified' => false,
    ];
}
