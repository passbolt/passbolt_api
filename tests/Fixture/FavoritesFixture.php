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
            'id' => '80e08b47-07bc-4f7c-b039-e81762d5d66e',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'foreign_id' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
            'foreign_model' => 'Resource',
            'created' => '2017-10-06 12:21:47',
            'modified' => '2017-10-06 12:21:47'
        ],
        [
            'id' => '852fe880-1779-41a7-8ac6-c0af7c4cd7e4',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_id' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
            'foreign_model' => 'Resource',
            'created' => '2017-10-06 12:21:47',
            'modified' => '2017-10-06 12:21:47'
        ],
        [
            'id' => 'd4da856c-4032-4030-af29-c5fabb3d39a2',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_id' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'foreign_model' => 'Resource',
            'created' => '2017-10-06 12:21:47',
            'modified' => '2017-10-06 12:21:47'
        ],
    ];
}
