<?php
namespace Passbolt\DirectorySync\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;
use App\Utility\UuidFactory;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;

/**
 * DirectoryEntriesFixture
 *
 */
class DirectoryEntriesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'foreign_model' => ['type' => 'string', 'length' => 36, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'foreign_key' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'directory_name' => ['type' => 'string', 'length' => 256, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'directory_created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'directory_modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'status' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'id' => ['type' => 'index', 'columns' => ['id', 'foreign_model', 'foreign_key', 'directory_name'], 'length' => ['directory_name' => '191']],
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
        $this->records = [
            [
                'id' => UuidFactory::uuid('ldap.user.id.ada'),
                'foreign_model' => 'Users',
                'foreign_key' => UuidFactory::uuid('user.id.ada'),
                'directory_name' => 'CN=Ada Lovelace,OU=PassboltUsers,DC=passbolt,DC=local',
                'directory_created' => '2018-07-20 06:31:57',
                'directory_modified' => '2018-07-20 06:31:57',
                'status' => DirectoryEntry::STATUS_SUCCESS,
                'created' => '2018-07-20 06:31:57',
                'modified' => '2018-07-20 06:31:57'
            ],
        ];
        parent::init();
    }
}
