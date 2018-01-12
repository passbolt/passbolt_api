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
            'token' => 'b31e46a4-2e4b-4054-9609-a8015439567e',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'active' => true,
            'created' => '2018-01-06 21:50:01',
            'modified' => '2018-01-06 21:50:01'
        ],
        [
            'id' => '47732566-3dfd-56bf-b83a-2a2e43524e3e',
            'token' => '4c138dd0-4787-402d-8e1b-7e648532b0fa',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => true,
            'created' => '2017-12-27 21:50:00',
            'modified' => '2017-12-27 21:50:00'
        ],
        [
            'id' => '654340be-c1f6-5c8f-9025-85e425a58316',
            'token' => 'e5129e47-3079-43b2-be82-00cc5115e1ed',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => true,
            'created' => '2018-01-06 21:50:01',
            'modified' => '2018-01-06 21:50:01'
        ],
        [
            'id' => '7c57af41-ade1-52d3-8f03-83ca4b0d7176',
            'token' => '221c0ccd-07c8-4349-a939-ede58ad0e1e6',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => false,
            'created' => '2018-01-06 21:50:01',
            'modified' => '2018-01-06 21:50:01'
        ],
        [
            'id' => '958d1484-8910-5a5a-8efc-65b967147e70',
            'token' => 'f0de9475-51da-43e2-9f9b-7c5f8d6c159d',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => false,
            'created' => '2017-12-27 21:50:00',
            'modified' => '2017-12-27 21:50:00'
        ],
        [
            'id' => 'e771172b-9a05-5615-b2df-174575f60453',
            'token' => 'ec2ab2bb-5f5e-43d2-be5a-9f201da48886',
            'user_id' => '610b4c1c-3c08-5451-a163-5b2adba8a5cd',
            'active' => true,
            'created' => '2018-01-06 21:50:01',
            'modified' => '2018-01-06 21:50:01'
        ],
    ];
}
