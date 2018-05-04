<?php
namespace Passbolt\UsersSettings\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsersSetting Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string $property
 * @property string $value
 *
 * @property \Passbolt\UsersSettings\Model\Entity\User $user
 */
class UsersSetting extends Entity
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
        'user_id' => true,
        'property' => true,
        'value' => true,
        'user' => true
    ];
}
