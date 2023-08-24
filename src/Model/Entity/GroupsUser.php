<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GroupsUser Entity
 *
 * @property string $id
 * @property string|null $group_id
 * @property string|null $user_id
 * @property bool $is_admin
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Group|null $group
 * @property \App\Model\Entity\User|null $user
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
     * @var array<string, bool>
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
