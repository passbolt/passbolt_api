<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FavoritesFixture
 *
 */
class FavoritesFixture extends TestFixture
{

    /**
     * Import
     *
     * @var array
     */
    public $import = ['table' => 'favorites'];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => '56216dba-b6da-592b-87cb-fb5cbbd0a424',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'foreign_id' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
            'foreign_model' => 'Resource',
            'created' => '2017-10-06 13:39:13',
            'modified' => '2017-10-06 13:39:13'
        ],
        [
            'id' => 'c0964b40-f5b4-5927-b501-f75998121769',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_id' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
            'foreign_model' => 'Resource',
            'created' => '2017-10-06 13:39:13',
            'modified' => '2017-10-06 13:39:13'
        ],
        [
            'id' => 'fca890dc-c9cb-5f2f-b44e-2588d6ac8b08',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_id' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'foreign_model' => 'Resource',
            'created' => '2017-10-06 13:39:13',
            'modified' => '2017-10-06 13:39:13'
        ],
    ];
}
