<?php
declare(strict_types=1);

namespace Passbolt\Scim\Model\Entity;

use Cake\ORM\Entity;
use Passbolt\Scim\Utility\Resources;

/**
 * ScimEntry Entity
 *
 * @property string $id
 * @property string $foreign_key
 * @property string $foreign_model
 * @property string|null $external_identifier
 * @property string|null $scim_name
 * @property \Cake\I18n\FrozenTime null $deleted
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
        Resources::USERS => self::FOREIGN_MODEL_USERS,
        Resources::GROUPS => self::FOREIGN_MODEL_GROUPS,
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
