<?php
namespace App\Test\Fixture\Alt0;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PermissionsFixture
 *
 */
class PermissionsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'aco' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'aco_foreign_key' => ['type' => 'string', 'length' => 36, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'aro' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'aro_foreign_key' => ['type' => 'string', 'length' => 36, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'type' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'aco_foreign_key' => ['type' => 'index', 'columns' => ['aco_foreign_key'], 'length' => []],
            'aro_foreign_key' => ['type' => 'index', 'columns' => ['aro_foreign_key'], 'length' => []],
            'aco' => ['type' => 'index', 'columns' => ['aco', 'aro'], 'length' => []],
            'type' => ['type' => 'index', 'columns' => ['type'], 'length' => []],
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
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => '036b0533-e353-47e0-ae3e-b7bca15eab73',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '100d1a96-d31c-4bdb-a7a5-3b10607b8e83',
            'aco' => 'Resource',
            'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '16cef445-147e-4616-b62d-898d84f2ae47',
            'aco' => 'Resource',
            'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '19f80a05-1a7c-4a3d-9140-044e0afef0fd',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '23beeba9-c76d-4070-bb25-751185b5555a',
            'aco' => 'Resource',
            'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
            'aro' => 'Group',
            'aro_foreign_key' => '1ab1135b-383e-52fd-977a-704e40522576',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '281a70e6-3e03-441e-891b-30a401ece07f',
            'aco' => 'Resource',
            'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '2be4651d-0d8a-4b0a-afc7-2a63a0a33831',
            'aco' => 'Resource',
            'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
            'aro' => 'Group',
            'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '32ca1d9a-5e36-47ae-b0bc-329167e9d2ca',
            'aco' => 'Resource',
            'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '45832cb8-9fab-4c29-ad16-57e8d9a60eae',
            'aco' => 'Resource',
            'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '47e03ce3-14c9-4324-89e9-2ccdb8129bbb',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '48e11b2c-0078-4b2b-bdfc-a6c2f5fc3015',
            'aco' => 'Resource',
            'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '57724dda-1a59-46f8-96bc-7d88f3456b53',
            'aco' => 'Resource',
            'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '5799b71b-1652-40ca-a2cf-d3bc69df2a63',
            'aco' => 'Resource',
            'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '5e4b8baa-40db-4204-a407-8cd4bb34e9b1',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '5f2348a1-e949-4a9b-9154-af75ebaef4f4',
            'aco' => 'Resource',
            'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '5f2994a9-848d-4fb3-8263-2161e7ce7d73',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '64c18dbf-ba8b-46e0-8702-5d1849aafb23',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '66140467-73e0-42d2-a1cf-abcb80360e7a',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '6994be22-4907-4412-b813-c729ad61e1cf',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '6b5a0fe0-aa7f-4196-b77b-fe40a0d29111',
            'aco' => 'Resource',
            'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '6da07290-2cae-4cee-8407-33f371a24f29',
            'aco' => 'Resource',
            'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '704c1b25-b2b5-4af9-ad74-0137281e1e63',
            'aco' => 'Resource',
            'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '72a6143a-9d7d-4b51-a8c0-8579b1f23b91',
            'aco' => 'Resource',
            'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '756c438e-0a28-46e3-b3a1-d39fa7573e83',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '96a5c6ac-5c41-4892-84d1-8f75ca532d06',
            'aco' => 'Resource',
            'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '99a5b93f-2127-48d5-bbf7-5e8ef2daabe2',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '9d29d9c8-d332-436d-8d89-dc6c00b56cf0',
            'aco' => 'Resource',
            'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'a9dbf5a9-3b16-4455-8482-0bc8ee7603a6',
            'aco' => 'Resource',
            'aco_foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'ac49ff5a-d203-4d73-8fb0-2df04bd1d4e8',
            'aco' => 'Resource',
            'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'b6fe0ef5-c8ca-4f9a-8002-e8864c439d74',
            'aco' => 'Resource',
            'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'bb7f51e4-8089-4231-a0cf-20b2dcb51918',
            'aco' => 'Resource',
            'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'bbfe2ff7-9872-4636-9d22-15afa40b3a83',
            'aco' => 'Resource',
            'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'c16e81f9-4ea4-4b4d-a57d-727f42cbe841',
            'aco' => 'Resource',
            'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
            'aro' => 'Group',
            'aro_foreign_key' => '1ab1135b-383e-52fd-977a-704e40522576',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'd2fa28b6-0b7a-4534-931c-ccd4531a7d3d',
            'aco' => 'Resource',
            'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'd8a3d491-5c4c-4e3a-adf9-b3036cc6ff68',
            'aco' => 'Resource',
            'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'dd7cc138-5a9c-496a-bc9b-ddd0a267c774',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'e5474e22-b73a-4d5c-b522-4da7f5223e27',
            'aco' => 'Resource',
            'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'ea3272c6-51d4-4af3-86e0-2bf065430f46',
            'aco' => 'Resource',
            'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'f27174f8-4463-4663-a951-adb85b939033',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'f5616ee3-5d59-4787-bc68-7b1b0bfe6060',
            'aco' => 'Resource',
            'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
            'aro' => 'Group',
            'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'f7f254ae-6725-4fa0-bcab-80ad9ac42e00',
            'aco' => 'Resource',
            'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
            'aro' => 'Group',
            'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'f7fddd30-3fb6-4ca0-81b7-770f2095fb1c',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
    ];
}
