<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AuthenticationTokensFixture
 *
 */
class AuthenticationTokensFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'token' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
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
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => '245fd3af-974f-5995-b912-d55b8b0515cf',
            'token' => '12021c8f-d838-4758-b09c-9ecffe7b96fa',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'active' => true,
            'created' => '2017-11-08 08:39:33',
            'modified' => '2017-11-08 08:39:33'
        ],
        [
            'id' => '47732566-3dfd-56bf-b83a-2a2e43524e3e',
            'token' => 'f133296a-9e10-4eb8-b4b8-56c617e4e5ef',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => true,
            'created' => '2017-10-29 08:39:33',
            'modified' => '2017-10-29 08:39:33'
        ],
        [
            'id' => '654340be-c1f6-5c8f-9025-85e425a58316',
            'token' => '0c8d0e8b-624a-4374-9d3d-66c61ad7a8cf',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => true,
            'created' => '2017-11-08 08:39:33',
            'modified' => '2017-11-08 08:39:33'
        ],
        [
            'id' => '7c57af41-ade1-52d3-8f03-83ca4b0d7176',
            'token' => '58c43b01-6b5f-413b-9c7a-96dae6397520',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => false,
            'created' => '2017-11-08 08:39:33',
            'modified' => '2017-11-08 08:39:33'
        ],
        [
            'id' => '8b36c205-3497-5790-929a-90f118b5bdd1',
            'token' => 'e2019eb3-7f99-4d57-a2d3-6948f30b8c7c',
            'user_id' => '1fc3ca40-3603-5879-8141-089ed7000f41',
            'active' => true,
            'created' => '2017-11-08 08:39:33',
            'modified' => '2017-11-08 08:39:33'
        ],
        [
            'id' => '958d1484-8910-5a5a-8efc-65b967147e70',
            'token' => 'd52d358d-79df-4ddb-a1ec-a2c6164483fb',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => false,
            'created' => '2017-10-29 08:39:33',
            'modified' => '2017-10-29 08:39:33'
        ],
    ];
}
