<?php
declare(strict_types=1);

namespace Passbolt\DirectorySync\Model\Entity;

use Cake\ORM\Entity;

/**
 * DirectoryReportsItem Entity
 *
 * @property string $report_id
 * @property string $id
 * @property string $status
 * @property string $model
 * @property string $action
 * @property string $data
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \Passbolt\DirectorySync\Model\Entity\Report $report
 */
class DirectoryReportsItem extends Entity
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
        'report_id' => false,
        'status' => false,
        'model' => false,
        'action' => false,
        'data' => false,
        'created' => false,
        'report' => false,
    ];
}
