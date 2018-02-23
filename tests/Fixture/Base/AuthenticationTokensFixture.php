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
            'token' => '1744c474-3335-4923-9205-5854284e5110',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'active' => true,
            'created' => '2018-02-23 08:37:14',
            'modified' => '2018-02-23 08:37:14'
        ],
        [
            'id' => '47732566-3dfd-56bf-b83a-2a2e43524e3e',
            'token' => '2ba66f96-2e8c-41c1-b7b3-f388d3e68562',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => true,
            'created' => '2018-02-13 08:37:14',
            'modified' => '2018-02-13 08:37:14'
        ],
        [
            'id' => '654340be-c1f6-5c8f-9025-85e425a58316',
            'token' => '7bbabd1a-89ba-41f0-a5a0-85be3635bdea',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => true,
            'created' => '2018-02-23 08:37:14',
            'modified' => '2018-02-23 08:37:14'
        ],
        [
            'id' => '7c57af41-ade1-52d3-8f03-83ca4b0d7176',
            'token' => '997567f7-4e6e-4ff3-ba2a-cb0a1f9920ed',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => false,
            'created' => '2018-02-23 08:37:14',
            'modified' => '2018-02-23 08:37:14'
        ],
        [
            'id' => '958d1484-8910-5a5a-8efc-65b967147e70',
            'token' => '5714eadd-e873-4a86-bc1d-789d60de85f2',
            'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
            'active' => false,
            'created' => '2018-02-13 08:37:14',
            'modified' => '2018-02-13 08:37:14'
        ],
        [
            'id' => 'e771172b-9a05-5615-b2df-174575f60453',
            'token' => '48d20477-594c-44fe-825b-9cd69038caf2',
            'user_id' => '610b4c1c-3c08-5451-a163-5b2adba8a5cd',
            'active' => true,
            'created' => '2018-02-23 08:37:14',
            'modified' => '2018-02-23 08:37:14'
        ],
    ];
}
