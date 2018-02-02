<?php
namespace Passbolt\Tags\Model\Entity;

use Cake\ORM\Entity;

/**
 * ResourcesTag Entity
 *
 * @property string $id
 * @property string $resource_id
 * @property string $tag_id
 * @property string $user_id
 *
 * @property \App\Model\Entity\Resource $resource
 * @property \App\Model\Entity\Tag $tag
 * @property \App\Model\Entity\User $user
 */
class ResourcesTag extends Entity
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
        '*' => false
    ];
}
