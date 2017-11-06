<?php
namespace App\Test\Fixture;

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
        'name' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'name' => ['type' => 'unique', 'columns' => ['name'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => '23d941d5-3676-3443-afdb-aaf2456f3b49',
            'name' => 'admin',
            'description' => 'Organization administrator',
            'created' => '2012-07-04 13:39:25',
            'modified' => '2012-07-04 13:39:25'
        ],
        [
            'id' => '49aad81e-4f70-3380-a92e-12292597409f',
            'name' => 'guest',
            'description' => 'Non logged in user',
            'created' => '2012-07-04 13:39:25',
            'modified' => '2012-07-04 13:39:25'
        ],
        [
            'id' => '857760a6-4f9d-3f1b-a292-95b630bcf03f',
            'name' => 'root',
            'description' => 'Super Administrator',
            'created' => '2012-07-04 13:39:25',
            'modified' => '2012-07-04 13:39:25'
        ],
        [
            'id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
            'name' => 'user',
            'description' => 'Logged in user',
            'created' => '2012-07-04 13:39:25',
            'modified' => '2012-07-04 13:39:25'
        ],
    ];
}
