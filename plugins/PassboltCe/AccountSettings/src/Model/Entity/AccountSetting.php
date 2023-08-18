<?php
declare(strict_types=1);

namespace Passbolt\AccountSettings\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsersSetting Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string $property
 * @property string $value
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property string $property_id
 */
class AccountSetting extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'user_id' => true,
        'property_id' => true,
        'property' => true,
        'value' => true,
        'created' => true,
        'modified' => true,
    ];

    public const SUPPORTED_PROPERTIES = [
        'theme', 'mfa', 'locale',
    ];

    public const UUID_NAMESPACE = 'account.settings.property.id.';
}
