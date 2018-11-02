<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GroupsUser Entity
 *
 * @property string $id
 * @property string $group_id
 * @property string $user_id
 * @property bool $is_admin
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\User $user
 */
class GroupsUser extends Entity
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
        'id' => false,
        'group_id' => false,
        'user_id' => false,
        'is_admin' => false,
        'created' => false,
        'created_by' => false,
    ];
}
