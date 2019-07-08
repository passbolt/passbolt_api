<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GroupsUsersFixture
 */
class GroupsUsersFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'group_id' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'is_admin' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'user_id' => ['type' => 'index', 'columns' => ['user_id', 'group_id'], 'length' => []],
            'group_id' => ['type' => 'index', 'columns' => ['group_id'], 'length' => []],
            'user_id_2' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
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
                'id' => '03e26ff8-81d2-5b7f-87e4-99bbc40e1f95',
                'group_id' => '428ed4cd-81b1-56af-aa7f-a7cbdbe227e4',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => '15b5e2c6-164a-50e9-a46f-2b4a9ab9345a',
                'group_id' => 'c9c8fd8e-a0fa-53f0-967b-42edca3d91e4',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => '15f486f6-4f5a-53f7-82ca-974e0be74e95',
                'group_id' => '4ff007f6-80ec-5bf7-8f0a-46a17178db6f',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => '2510a118-c838-5470-a0dd-aff268d4a2b6',
                'group_id' => '516c2db6-0aed-52d8-854f-b3f3499995e7',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => '2e450a49-1639-5971-bf82-b945b310cc1d',
                'group_id' => 'de5fcb71-6db2-5fe5-8803-7d3eb4d6ad9c',
                'user_id' => '5302c3cb-5d33-53b1-82cd-57df36e13acc',
                'is_admin' => false,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => '38804173-18aa-5ec1-99b9-354496374816',
                'group_id' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
                'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => '38839a7d-1e33-590e-a675-e24894819cd8',
                'group_id' => 'b8b17d77-51e5-5c99-a0b6-86cf5757a781',
                'user_id' => 'c92a1885-1644-5bdb-8486-12d751b976ff',
                'is_admin' => false,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => '6025a745-34a1-513f-9ecb-205448e10b9b',
                'group_id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'user_id' => '0da907bd-5c57-5acc-ba39-c6ebe091f613',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => '6b8bc98f-a456-56a5-8492-db2faaebe2a2',
                'group_id' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'user_id' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => '6c3a4077-966c-5f08-9ac6-325da1321eb2',
                'group_id' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'user_id' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => '6ccbafad-8590-55bb-b2ce-02746fffcf28',
                'group_id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'user_id' => '1e73e104-d53e-579d-a0c4-e9aeaca76c56',
                'is_admin' => false,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => '860fcc8f-dad6-5a90-ad86-45253c58e642',
                'group_id' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'user_id' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => '8e42567e-6e6e-54bc-b17b-0f5afde5b01c',
                'group_id' => '3feba74f-47da-5146-9d8f-76c7266c60ea',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => '97091e37-dba3-5256-a13b-7549d30db452',
                'group_id' => 'de5fcb71-6db2-5fe5-8803-7d3eb4d6ad9c',
                'user_id' => 'f7e9754a-2f64-5cdd-8ba2-178b33383505',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => '99fabba9-e069-59e6-a3b6-775436322b21',
                'group_id' => 'a89b771e-62ab-5434-b2fa-950827439ac7',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => '9c937007-8d53-532d-b02f-80f100139990',
                'group_id' => 'faa73142-fb5e-5891-8b9f-4a00b3836fad',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => 'a588e9c4-c35c-53f5-9cb1-77a01afd482e',
                'group_id' => 'de5fcb71-6db2-5fe5-8803-7d3eb4d6ad9c',
                'user_id' => 'c92a1885-1644-5bdb-8486-12d751b976ff',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => 'a6753204-eef8-5a57-9a80-019e393626b1',
                'group_id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
                'is_admin' => false,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => 'a932a3ce-82bc-59b6-ac4e-bf325435e534',
                'group_id' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
                'user_id' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'is_admin' => false,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => 'ad80b164-c30f-53e0-aac1-3040fa2f136d',
                'group_id' => 'f16c507f-9105-502e-aa8a-ba24c36dbdcf',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => 'afb94ff6-4bf2-5de3-a944-0074f1d57289',
                'group_id' => 'b8b17d77-51e5-5c99-a0b6-86cf5757a781',
                'user_id' => '5302c3cb-5d33-53b1-82cd-57df36e13acc',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => 'b2e0831f-dd63-54e2-ac50-4af24eaf5e74',
                'group_id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'user_id' => 'e7fa0375-61df-5dbc-9e42-e0d363bd0ecf',
                'is_admin' => false,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => 'b49abe97-b291-5a9e-b80f-e16313c8d6a9',
                'group_id' => 'b8b17d77-51e5-5c99-a0b6-86cf5757a781',
                'user_id' => 'f7e9754a-2f64-5cdd-8ba2-178b33383505',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => 'c8f4bc84-2ea2-5509-8d6a-6b7378b7fffa',
                'group_id' => '5fe7a6af-d97e-54f1-a4fc-b4b8bdb6e2ac',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => 'd100fc5d-6685-50aa-897b-87ac816e28c8',
                'group_id' => 'b7cbce9f-6a20-545b-b20a-fcf4092307df',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:47'
            ],
            [
                'id' => 'e137ab7f-025d-5f4f-98e0-e14769948905',
                'group_id' => 'de5fcb71-6db2-5fe5-8803-7d3eb4d6ad9c',
                'user_id' => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08',
                'is_admin' => false,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => 'eeccd1ac-c99c-594b-8d13-b38f819a5871',
                'group_id' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'user_id' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'is_admin' => true,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => 'fa3fa0b2-cdf4-5c9a-8edf-2892dc35e27a',
                'group_id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'user_id' => 'e1ebc592-b90d-5e22-9f40-50e52911673b',
                'is_admin' => false,
                'created' => '2019-07-02 18:51:46'
            ],
            [
                'id' => 'fcc26e60-1a64-50f5-b7f0-9a8c9ea69208',
                'group_id' => 'b8b17d77-51e5-5c99-a0b6-86cf5757a781',
                'user_id' => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08',
                'is_admin' => false,
                'created' => '2019-07-02 18:51:47'
            ],
        ];
        parent::init();
    }
}
