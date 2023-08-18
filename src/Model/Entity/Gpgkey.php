<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Gpgkey Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string $armored_key
 * @property int|null $bits
 * @property string $uid
 * @property string $key_id
 * @property string $fingerprint
 * @property string|null $type
 * @property \Cake\I18n\FrozenTime|null $expires
 * @property \Cake\I18n\FrozenTime|null $key_created
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
     * @var array<string, bool>
     */
    protected $_accessible = [
        'id' => false,
        'user_id' => false,
        'armored_key' => false,
        'bits' => false,
        'uid' => false,
        'key_id' => false,
        'fingerprint' => false,
        'type' => false,
        'expires' => false,
        'key_created' => false,
        'deleted' => false,
        'created' => false,
        'modified' => false,

        // associations
        'user' => false,
    ];

    /**
     * Returns true if expired is set and in the past.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        if (!isset($this->expires)) {
            return false;
        }

        return $this->expires->isPast();
    }
}
