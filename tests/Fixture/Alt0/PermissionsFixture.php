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
        'aco_foreign_key' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'aro' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'aro_foreign_key' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
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
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => '0179338c-7beb-57df-bf5e-407393fc9b2d',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 15,
                'created' => '2017-11-17 12:37:35',
                'modified' => '2017-11-17 12:37:35'
            ],
            [
                'id' => '07e2ad29-01ef-5ce8-bd5c-d182f3c93020',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'Group',
                'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
                'type' => 15,
                'created' => '2017-11-17 12:37:39',
                'modified' => '2017-11-17 12:37:39'
            ],
            [
                'id' => '08bc85e5-c8ff-5266-a832-f5470922d973',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 15,
                'created' => '2017-11-17 12:37:31',
                'modified' => '2017-11-17 12:37:31'
            ],
            [
                'id' => '0da7de23-325c-5b1e-a1ce-f1b9e20e8aad',
                'aco' => 'Resource',
                'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 15,
                'created' => '2017-11-17 12:37:05',
                'modified' => '2017-11-17 12:37:05'
            ],
            [
                'id' => '0e33e33e-1f8e-5103-9171-4fce18aef034',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2017-11-17 12:37:27',
                'modified' => '2017-11-17 12:37:27'
            ],
            [
                'id' => '1439cb13-34b4-5ce2-8e3b-e6213bb11904',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2017-11-17 12:37:23',
                'modified' => '2017-11-17 12:37:23'
            ],
            [
                'id' => '197034fa-a5de-5552-8c4a-8411d83c9aa4',
                'aco' => 'Resource',
                'aco_foreign_key' => '46c07495-6fa2-5ac7-a315-9b36e3969a21',
                'aro' => 'User',
                'aro_foreign_key' => 'e7fa0375-61df-5dbc-9e42-e0d363bd0ecf',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => '1b22f3d5-9af1-5465-b0d5-7cf3101a8d5f',
                'aco' => 'Resource',
                'aco_foreign_key' => '97fdaf32-27e7-5549-9255-aa928ddd57b0',
                'aro' => 'Group',
                'aro_foreign_key' => 'f16c507f-9105-502e-aa8a-ba24c36dbdcf',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => '1b5f11e2-27bb-5a7b-afb8-d9c0088a105d',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ad0d80e0-0441-5388-b679-13c41a693442',
                'aro' => 'User',
                'aro_foreign_key' => 'e1ebc592-b90d-5e22-9f40-50e52911673b',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => '1edfdfbf-c004-55d0-be4e-94d10c9d7b2b',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 15,
                'created' => '2017-11-17 12:37:18',
                'modified' => '2017-11-17 12:37:18'
            ],
            [
                'id' => '2303ae7b-db66-5874-8f51-54ea11a365a3',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd1d240e1-9809-5ee3-9b59-2e1232d3faf0',
                'aro' => 'Group',
                'aro_foreign_key' => 'b7cbce9f-6a20-545b-b20a-fcf4092307df',
                'type' => 1,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => '23382e9b-d867-5d7e-8c37-e304f5adcb79',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 7,
                'created' => '2017-11-17 12:37:19',
                'modified' => '2017-11-17 12:37:19'
            ],
            [
                'id' => '323ece66-20a9-5997-abb2-d140814c7495',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 15,
                'created' => '2017-11-17 12:37:26',
                'modified' => '2017-11-17 12:37:26'
            ],
            [
                'id' => '34a4fcb6-baea-55ed-93f6-1f4db4c4899d',
                'aco' => 'Resource',
                'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 15,
                'created' => '2017-11-17 12:37:12',
                'modified' => '2017-11-17 12:37:12'
            ],
            [
                'id' => '34de8e7f-5e22-58eb-b9c5-633d801355ed',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 15,
                'created' => '2017-11-17 12:37:22',
                'modified' => '2017-11-17 12:37:22'
            ],
            [
                'id' => '3579f0d6-2cae-5636-ae87-25244f519425',
                'aco' => 'Resource',
                'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 15,
                'created' => '2017-11-17 12:37:10',
                'modified' => '2017-11-17 12:37:10'
            ],
            [
                'id' => '36b9ae25-c3dc-5fda-a41b-a80fa9b14c2f',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'Group',
                'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
                'type' => 15,
                'created' => '2017-11-17 12:37:15',
                'modified' => '2017-11-17 12:37:15'
            ],
            [
                'id' => '46911feb-79e2-5715-854c-113f674a4b02',
                'aco' => 'Resource',
                'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 1,
                'created' => '2017-11-17 12:37:05',
                'modified' => '2017-11-17 12:37:05'
            ],
            [
                'id' => '4791f995-37e4-52f6-aa23-15bf36b3e40e',
                'aco' => 'Resource',
                'aco_foreign_key' => '97fdaf32-27e7-5549-9255-aa928ddd57b0',
                'aro' => 'Group',
                'aro_foreign_key' => '4ff007f6-80ec-5bf7-8f0a-46a17178db6f',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => '4f75598a-3431-5465-9d75-b268a8f7857d',
                'aco' => 'Resource',
                'aco_foreign_key' => '9568770f-59b5-524a-b61b-22526e7ef7c6',
                'aro' => 'User',
                'aro_foreign_key' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => '547c649c-a36c-50fd-bd14-188cbe34111e',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ff3ee3f2-435f-5383-93dc-fea804460936',
                'aro' => 'User',
                'aro_foreign_key' => '742554b6-2940-5b7d-a8e7-b03a19f78b8e',
                'type' => 1,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => '5624e59c-b80f-5196-9545-a94961e825a5',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd5c11891-a5c6-5475-ae14-4d607960d622',
                'aro' => 'Group',
                'aro_foreign_key' => '3feba74f-47da-5146-9d8f-76c7266c60ea',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => '5ef9bb30-e6e7-5412-bba4-c62e1997536b',
                'aco' => 'Resource',
                'aco_foreign_key' => 'fa63515f-453c-522a-bcc6-3bea185638f0',
                'aro' => 'Group',
                'aro_foreign_key' => 'c9c8fd8e-a0fa-53f0-967b-42edca3d91e4',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => '5f31fd70-14d7-577c-ad79-e70f87912516',
                'aco' => 'Resource',
                'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 15,
                'created' => '2017-11-17 12:37:17',
                'modified' => '2017-11-17 12:37:17'
            ],
            [
                'id' => '7516366e-cb92-5bd7-a972-cfd384e26da0',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 1,
                'created' => '2017-11-17 12:37:38',
                'modified' => '2017-11-17 12:37:38'
            ],
            [
                'id' => '7662e67d-ca74-5ba7-9e56-42cde2fbfaa9',
                'aco' => 'Resource',
                'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 15,
                'created' => '2017-11-17 12:37:07',
                'modified' => '2017-11-17 12:37:07'
            ],
            [
                'id' => '857c2cb8-a0c2-570d-86ef-7754e271616f',
                'aco' => 'Resource',
                'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 15,
                'created' => '2017-11-17 12:37:16',
                'modified' => '2017-11-17 12:37:16'
            ],
            [
                'id' => '87c222cd-f6f3-58eb-9c1a-c03afb85d764',
                'aco' => 'Resource',
                'aco_foreign_key' => '2d3958b8-18ba-5d0b-9464-0df0beec1433',
                'aro' => 'Group',
                'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => '8e3d60eb-e221-5478-b1f4-06cd158748c2',
                'aco' => 'Resource',
                'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 15,
                'created' => '2017-11-17 12:37:44',
                'modified' => '2017-11-17 12:37:44'
            ],
            [
                'id' => '8f39076d-1273-5498-97f8-bf32abaa3fff',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 15,
                'created' => '2017-11-17 12:37:34',
                'modified' => '2017-11-17 12:37:34'
            ],
            [
                'id' => '8f6eff01-56c6-5f73-857f-2f85c11ff67f',
                'aco' => 'Resource',
                'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 15,
                'created' => '2017-11-17 12:37:08',
                'modified' => '2017-11-17 12:37:08'
            ],
            [
                'id' => '9014df23-cb71-5a95-9f31-b6ee5f969983',
                'aco' => 'Resource',
                'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 15,
                'created' => '2017-11-17 12:37:11',
                'modified' => '2017-11-17 12:37:11'
            ],
            [
                'id' => '92901de6-b57b-557f-a0d5-f64785f6c4e7',
                'aco' => 'Resource',
                'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 15,
                'created' => '2017-11-17 12:37:13',
                'modified' => '2017-11-17 12:37:13'
            ],
            [
                'id' => '9516b7a9-4f66-5636-9895-479d7e4d7c86',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 7,
                'created' => '2017-11-17 12:37:32',
                'modified' => '2017-11-17 12:37:32'
            ],
            [
                'id' => '9858d6ed-4fd5-59ff-94ad-a3f62fdb2f2a',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'Group',
                'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
                'type' => 15,
                'created' => '2017-11-17 12:37:20',
                'modified' => '2017-11-17 12:37:20'
            ],
            [
                'id' => '9a69a3eb-65d4-55f0-bf99-0d960af9c57d',
                'aco' => 'Resource',
                'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 1,
                'created' => '2017-11-17 12:37:06',
                'modified' => '2017-11-17 12:37:06'
            ],
            [
                'id' => 'a21c59ac-31b0-5fe8-ae68-d4ccb2d7f60f',
                'aco' => 'Resource',
                'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 7,
                'created' => '2017-11-17 12:37:14',
                'modified' => '2017-11-17 12:37:14'
            ],
            [
                'id' => 'a593ec5d-74ee-589f-91a7-b5d740048ccf',
                'aco' => 'Resource',
                'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
                'aro' => 'User',
                'aro_foreign_key' => '887422c0-bef6-59a7-bbda-84c253ee0848',
                'type' => 7,
                'created' => '2017-11-17 12:37:09',
                'modified' => '2017-11-17 12:37:09'
            ],
            [
                'id' => 'a8465cb9-6699-5857-a665-84394815cfd0',
                'aco' => 'Resource',
                'aco_foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => 'af8a4cd6-85f8-5222-a05b-4b0143f5eaa2',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ad0d80e0-0441-5388-b679-13c41a693442',
                'aro' => 'Group',
                'aro_foreign_key' => 'a89b771e-62ab-5434-b2fa-950827439ac7',
                'type' => 1,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => 'b3b3aaee-4ed8-5b55-bc16-289f5b236f8c',
                'aco' => 'Resource',
                'aco_foreign_key' => 'eb3c4800-aa75-5d84-bb88-99247486a8c5',
                'aro' => 'User',
                'aro_foreign_key' => '0da907bd-5c57-5acc-ba39-c6ebe091f613',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => 'b983c698-9c85-5e56-85d5-f207e0deaca9',
                'aco' => 'Resource',
                'aco_foreign_key' => '9568770f-59b5-524a-b61b-22526e7ef7c6',
                'aro' => 'User',
                'aro_foreign_key' => '1e73e104-d53e-579d-a0c4-e9aeaca76c56',
                'type' => 1,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => 'bb9a6378-13c1-5b1f-8556-ed3d5f2f1a3b',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd5c11891-a5c6-5475-ae14-4d607960d622',
                'aro' => 'User',
                'aro_foreign_key' => '8d038399-ecac-55b4-8ad3-b7f0650de2a2',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => 'c02025ec-c2a9-5045-85a1-2ef682fd2daf',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2017-11-17 12:37:25',
                'modified' => '2017-11-17 12:37:25'
            ],
            [
                'id' => 'c1dbdc7d-9c44-5344-947c-b8f76688317f',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'Group',
                'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
                'type' => 1,
                'created' => '2017-11-17 12:37:30',
                'modified' => '2017-11-17 12:37:30'
            ],
            [
                'id' => 'c2d05d45-3702-5e52-a8b8-1f6043c01363',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 15,
                'created' => '2017-11-17 12:37:37',
                'modified' => '2017-11-17 12:37:37'
            ],
            [
                'id' => 'c3809c6e-f13b-5878-a554-93f28bc8ab0c',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 15,
                'created' => '2017-11-17 12:37:15',
                'modified' => '2017-11-17 12:37:15'
            ],
            [
                'id' => 'c6efcc4f-010d-518f-a072-f859c9c46fcc',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2017-11-17 12:37:21',
                'modified' => '2017-11-17 12:37:21'
            ],
            [
                'id' => 'cae958f1-2073-557f-b682-3325e922a172',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 15,
                'created' => '2017-11-17 12:37:28',
                'modified' => '2017-11-17 12:37:28'
            ],
            [
                'id' => 'd3a0feb7-8ac1-5ea1-9372-31323ba08b32',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'Group',
                'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
                'type' => 1,
                'created' => '2017-11-17 12:37:36',
                'modified' => '2017-11-17 12:37:36'
            ],
            [
                'id' => 'd52c9f09-8e30-5d6d-b6e1-56330846ef5b',
                'aco' => 'Resource',
                'aco_foreign_key' => 'fa63515f-453c-522a-bcc6-3bea185638f0',
                'aro' => 'User',
                'aro_foreign_key' => 'c92a1885-1644-5bdb-8486-12d751b976ff',
                'type' => 1,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => 'd997ba58-11ae-5ad7-96d3-90fac40756ae',
                'aco' => 'Resource',
                'aco_foreign_key' => '46c07495-6fa2-5ac7-a315-9b36e3969a21',
                'aro' => 'Group',
                'aro_foreign_key' => '516c2db6-0aed-52d8-854f-b3f3499995e7',
                'type' => 1,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => 'e01aaeb1-e2db-568a-b5eb-49dc020c8838',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 1,
                'created' => '2017-11-17 12:37:29',
                'modified' => '2017-11-17 12:37:29'
            ],
            [
                'id' => 'e86f95ba-b6e7-59e6-94de-9332518c6256',
                'aco' => 'Resource',
                'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 15,
                'created' => '2017-11-17 12:37:43',
                'modified' => '2017-11-17 12:37:43'
            ],
            [
                'id' => 'eacf4099-699e-54ac-a3a5-7f17f60cb08c',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ff3ee3f2-435f-5383-93dc-fea804460936',
                'aro' => 'Group',
                'aro_foreign_key' => 'de5fcb71-6db2-5fe5-8803-7d3eb4d6ad9c',
                'type' => 15,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => 'ef142aca-b8de-56f4-bdcf-8d78c859cb7b',
                'aco' => 'Resource',
                'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 7,
                'created' => '2017-11-17 12:37:42',
                'modified' => '2017-11-17 12:37:42'
            ],
            [
                'id' => 'f3b4efd2-48a7-5722-830e-9c8af0dd6a6c',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 15,
                'created' => '2017-11-17 12:37:24',
                'modified' => '2017-11-17 12:37:24'
            ],
            [
                'id' => 'f9cdddea-c56f-5a42-9393-fecf3c542dcd',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 15,
                'created' => '2017-11-17 12:37:40',
                'modified' => '2017-11-17 12:37:40'
            ],
            [
                'id' => 'faed650c-7944-5bc3-baac-361799b4defb',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 7,
                'created' => '2017-11-17 12:37:41',
                'modified' => '2017-11-17 12:37:41'
            ],
            [
                'id' => 'fc15af5e-dfd1-5cf6-9a97-dc0f72d4a629',
                'aco' => 'Resource',
                'aco_foreign_key' => '2d3958b8-18ba-5d0b-9464-0df0beec1433',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 1,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
        ];
        parent::init();
    }
}
