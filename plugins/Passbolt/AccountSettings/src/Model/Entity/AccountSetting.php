<?php
namespace Passbolt\AccountSettings\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsersSetting Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string $property
 * @property string $value
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \Passbolt\AccountSettings\Model\Entity\User $user
 */
class AccountSetting extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'property_id' => true,
        'property' => true,
        'value' => true,
        'created' => true,
        'modified' => true,
    ];

    const SUPPORTED_PROPERTIES = [
        'theme', 'mfa'
    ];

    const UUID_NAMESPACE = 'account.settings.property.id.';
}
