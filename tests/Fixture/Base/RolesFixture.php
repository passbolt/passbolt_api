<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RolesFixture
 *
 */
class RolesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'name' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'name' => ['type' => 'unique', 'columns' => ['name'], 'length' => []],
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
                'id' => '0d51c3a8-5e67-5e3d-882f-e1868966d817',
                'name' => 'admin',
                'description' => 'Organization administrator',
                'created' => '2012-07-04 13:39:25',
                'modified' => '2012-07-04 13:39:25'
            ],
            [
                'id' => '6f02b8d2-e24c-51fe-a452-5a027c26dbef',
                'name' => 'guest',
                'description' => 'Non logged in user',
                'created' => '2012-07-04 13:39:25',
                'modified' => '2012-07-04 13:39:25'
            ],
            [
                'id' => 'a58de6d3-f52c-5080-b79b-a601a647ac85',
                'name' => 'user',
                'description' => 'Logged in user',
                'created' => '2012-07-04 13:39:25',
                'modified' => '2012-07-04 13:39:25'
            ],
            [
                'id' => 'eeda6af2-38dc-5e34-b86d-7687878bc38a',
                'name' => 'root',
                'description' => 'Super Administrator',
                'created' => '2012-07-04 13:39:25',
                'modified' => '2012-07-04 13:39:25'
            ],
        ];
        parent::init();
    }
}
