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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Model\Entity;

use Cake\ORM\Entity;

/**
 * MetadataKey Entity
 *
 * @property string $id
 * @property string $fingerprint
 * @property string $armored_key
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 * @property string $created_by
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User|null $creator
 * @property \App\Model\Entity\User|null $modifier
 * @property \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[] $metadata_private_keys
 */
class MetadataKey extends Entity
{
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
        'fingerprint' => false,
        'armored_key' => false,
        'created' => false,
        'modified' => false,
        'deleted' => false,
        'created_by' => false,
        'modified_by' => false,
        'metadata_private_keys' => false,
    ];
}
