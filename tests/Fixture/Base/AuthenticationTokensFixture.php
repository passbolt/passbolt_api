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
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => '245fd3af-974f-5995-b912-d55b8b0515cf',
                'token' => 'ffa26d70-5a4f-488e-bf8b-bb5ed0e3321a',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'active' => true,
                'created' => '2018-05-01 05:25:32',
                'modified' => '2018-05-01 05:25:32'
            ],
            [
                'id' => '47732566-3dfd-56bf-b83a-2a2e43524e3e',
                'token' => 'd03d1bad-fe1e-4ebe-85bc-bc837bafe6c1',
                'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
                'active' => true,
                'created' => '2018-04-21 05:25:32',
                'modified' => '2018-04-21 05:25:32'
            ],
            [
                'id' => '654340be-c1f6-5c8f-9025-85e425a58316',
                'token' => '7ac250dd-ee49-487f-b1a1-d62d3dd9ae13',
                'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
                'active' => true,
                'created' => '2018-05-01 05:25:32',
                'modified' => '2018-05-01 05:25:32'
            ],
            [
                'id' => '7c57af41-ade1-52d3-8f03-83ca4b0d7176',
                'token' => '906fb19a-bb44-415d-b1e5-e17d4ed38e15',
                'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
                'active' => false,
                'created' => '2018-05-01 05:25:32',
                'modified' => '2018-05-01 05:25:32'
            ],
            [
                'id' => '958d1484-8910-5a5a-8efc-65b967147e70',
                'token' => '144fc8c5-b111-4187-a77b-e37d59d68537',
                'user_id' => '92f42805-bc0f-58fd-9de6-aab13ed0c28d',
                'active' => false,
                'created' => '2018-04-21 05:25:32',
                'modified' => '2018-04-21 05:25:32'
            ],
            [
                'id' => 'e771172b-9a05-5615-b2df-174575f60453',
                'token' => 'd6558076-9e5b-406e-b5d2-ad4f7fc3cab3',
                'user_id' => '610b4c1c-3c08-5451-a163-5b2adba8a5cd',
                'active' => true,
                'created' => '2018-05-01 05:25:32',
                'modified' => '2018-05-01 05:25:32'
            ],
        ];
        parent::init();
    }
}
