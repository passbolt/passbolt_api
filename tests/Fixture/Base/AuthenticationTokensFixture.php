<?php
namespace App\Test\Fixture\Base;

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
            'token' => 'cfb22b5c-4c6f-428a-a429-e284a6d47247',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'active' => true,
            'created' => '2018-03-05 09:59:36',
            'modified' => '2018-03-05 09:59:36'
        ],
        [
            'id' => '47732566-3dfd-56bf-b83a-2a2e43524e3e',
            'token' => '419db4b4-edf1-41d6-9457-c2c80229fc9e',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => true,
            'created' => '2018-02-23 09:59:35',
            'modified' => '2018-02-23 09:59:35'
        ],
        [
            'id' => '654340be-c1f6-5c8f-9025-85e425a58316',
            'token' => '038ae917-39a9-4e69-ac3d-5dd668bddb7c',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => true,
            'created' => '2018-03-05 09:59:35',
            'modified' => '2018-03-05 09:59:35'
        ],
        [
            'id' => '7c57af41-ade1-52d3-8f03-83ca4b0d7176',
            'token' => '73f051af-bd07-442d-a007-e1ea7279d47f',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => false,
            'created' => '2018-03-05 09:59:35',
            'modified' => '2018-03-05 09:59:35'
        ],
        [
            'id' => '958d1484-8910-5a5a-8efc-65b967147e70',
            'token' => '2106ecd8-0f9e-4c6d-9b43-783bc8df2874',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => false,
            'created' => '2018-02-23 09:59:35',
            'modified' => '2018-02-23 09:59:35'
        ],
        [
            'id' => 'e771172b-9a05-5615-b2df-174575f60453',
            'token' => '2468999c-359c-4d39-aee4-85287518460b',
            'user_id' => '610b4c1c-3c08-5451-a163-5b2adba8a5cd',
            'active' => true,
            'created' => '2018-03-05 09:59:36',
            'modified' => '2018-03-05 09:59:36'
        ],
    ];
}
