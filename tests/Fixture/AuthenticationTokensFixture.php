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
            'token' => '9919b443-a8cd-4902-95a2-52ec0d99e385',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'active' => true,
            'created' => '2017-11-27 16:50:16',
            'modified' => '2017-11-27 16:50:16'
        ],
        [
            'id' => '47732566-3dfd-56bf-b83a-2a2e43524e3e',
            'token' => '208cd18d-e3ce-417e-ba48-6235015dfeee',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => true,
            'created' => '2017-11-17 16:50:16',
            'modified' => '2017-11-17 16:50:16'
        ],
        [
            'id' => '654340be-c1f6-5c8f-9025-85e425a58316',
            'token' => '7d87e418-359c-4b5f-a39a-f096fde0ef6d',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => true,
            'created' => '2017-11-27 16:50:16',
            'modified' => '2017-11-27 16:50:16'
        ],
        [
            'id' => '7c57af41-ade1-52d3-8f03-83ca4b0d7176',
            'token' => '49769b0c-b9f3-4bef-b86f-b5a2870297d1',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => false,
            'created' => '2017-11-27 16:50:16',
            'modified' => '2017-11-27 16:50:16'
        ],
        [
            'id' => '958d1484-8910-5a5a-8efc-65b967147e70',
            'token' => 'c034cbd3-bd1a-45e9-8497-1087dd169e30',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => false,
            'created' => '2017-11-17 16:50:16',
            'modified' => '2017-11-17 16:50:16'
        ],
        [
            'id' => 'e771172b-9a05-5615-b2df-174575f60453',
            'token' => 'c8d41f4f-2648-405b-b241-1cc74e3aed6d',
            'user_id' => '610b4c1c-3c08-5451-a163-5b2adba8a5cd',
            'active' => true,
            'created' => '2017-11-27 16:50:16',
            'modified' => '2017-11-27 16:50:16'
        ],
    ];
}
