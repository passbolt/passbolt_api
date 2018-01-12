<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Gpgkey Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string $armored_key
 * @property int $bits
 * @property string $uid
 * @property string $key_id
 * @property string $fingerprint
 * @property string $type
 * @property \Cake\I18n\FrozenTime $expires
 * @property \Cake\I18n\FrozenTime $key_created
 * @property bool $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Gpgkey extends Entity
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
        'id' => false
    ];
}
