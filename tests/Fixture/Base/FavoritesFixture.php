<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FavoritesFixture
 */
class FavoritesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'foreign_key' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'foreign_model' => ['type' => 'string', 'length' => 36, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'foreign_key' => ['type' => 'index', 'columns' => ['foreign_key', 'user_id'], 'length' => []],
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
                'id' => '56216dba-b6da-592b-87cb-fb5cbbd0a424',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
                'foreign_model' => 'Resource',
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'c0964b40-f5b4-5927-b501-f75998121769',
                'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
                'foreign_model' => 'Resource',
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'f9e22750-3cc2-5bbe-bd66-88f3358aaac3',
                'user_id' => '1e73e104-d53e-579d-a0c4-e9aeaca76c56',
                'foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'foreign_model' => 'Resource',
                'created' => '2019-07-02 18:51:52',
                'modified' => '2019-07-02 18:51:52'
            ],
            [
                'id' => 'fca890dc-c9cb-5f2f-b44e-2588d6ac8b08',
                'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
                'foreign_model' => 'Resource',
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
        ];
        parent::init();
    }
}
