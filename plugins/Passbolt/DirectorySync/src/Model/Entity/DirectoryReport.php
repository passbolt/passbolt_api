<?php
namespace Passbolt\DirectorySync\Model\Entity;

use Cake\ORM\Entity;

/**
 * DirectoryReport Entity
 *
 * @property string $id
 * @property string $parent_id
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \Passbolt\DirectorySync\Model\Entity\ParentDirectoryReport $parent_directory_report
 * @property \Passbolt\DirectorySync\Model\Entity\ChildDirectoryReport[] $child_directory_reports
 */
class DirectoryReport extends Entity
{

    const STATUS_RUNNING = 'running';
    const STATUS_DONE = 'done';

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
        'parent_id' => false,
        'status' => false,
        'created' => false,
        'modified' => false,
        'parent_directory_report' => false,
        'child_directory_reports' => false,
    ];
}
