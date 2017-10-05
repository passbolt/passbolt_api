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
            'id' => '0451c4c5-1ca1-452f-94b9-80fdd26f09c9',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_id' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'foreign_model' => 'Resource',
            'created' => '2017-10-05 12:34:49'
        ],
        [
            'id' => '5e21e696-3fd0-4467-ae77-e887d321ba11',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_id' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
            'foreign_model' => 'Resource',
            'created' => '2017-10-05 12:34:49'
        ],
        [
            'id' => '89a0864a-d1c8-47d6-b7a5-bfed567dbe10',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'foreign_id' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
            'foreign_model' => 'Resource',
            'created' => '2017-10-05 12:34:49'
        ],
    ];
}
