<?php
namespace Passbolt\DirectorySync\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;
use App\Utility\UuidFactory;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;

/**
 * DirectoryEntriesFixture
 *
 */
class DirectoryIgnoreFixture extends TestFixture
{

    public $table = 'directory_ignore';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'foreign_model' => ['type' => 'string', 'length' => 36, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'id' => ['type' => 'index', 'columns' => ['id', 'foreign_model']],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        // DO NOT EDIT
        // create entries in tests and not bellow or create another Fixture set
        parent::init();
    }
}
