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
            'id' => '03ed3120-a12c-4db1-8556-e00a799a315b',
            'aco' => 'Resource',
            'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:18',
            'modified' => '2017-11-17 12:37:18'
        ],
        [
            'id' => '0644b167-682c-446f-84c7-f635b4f53f86',
            'aco' => 'Resource',
            'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 1,
            'created' => '2017-11-17 12:37:29',
            'modified' => '2017-11-17 12:37:29'
        ],
        [
            'id' => '07421e6e-acd0-4caa-84bb-cfd8834626ee',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:39',
            'modified' => '2017-11-17 12:37:39'
        ],
        [
            'id' => '0e62b4f5-aff6-42b9-be3a-bf21a1f5e854',
            'aco' => 'Resource',
            'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:11',
            'modified' => '2017-11-17 12:37:11'
        ],
        [
            'id' => '15075ed2-978e-4e7a-8a7a-93ac4c3f23e7',
            'aco' => 'Resource',
            'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:22',
            'modified' => '2017-11-17 12:37:22'
        ],
        [
            'id' => '21625856-efa4-4d8a-acc6-98b023e2f392',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:42',
            'modified' => '2017-11-17 12:37:42'
        ],
        [
            'id' => '235e4bf2-0b67-4a02-89c9-f37ad1e732ee',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:44',
            'modified' => '2017-11-17 12:37:44'
        ],
        [
            'id' => '28c406be-76b2-44df-bcad-d64a15b58749',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:43',
            'modified' => '2017-11-17 12:37:43'
        ],
        [
            'id' => '37a59982-bb43-4b54-9cd1-e6df48b2f8e4',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:40',
            'modified' => '2017-11-17 12:37:40'
        ],
        [
            'id' => '4015c89b-4ed3-413a-aad4-7110e49a3ea3',
            'aco' => 'Resource',
            'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
            'aro' => 'Group',
            'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'type' => 15,
            'created' => '2017-11-17 12:37:26',
            'modified' => '2017-11-17 12:37:26'
        ],
        [
            'id' => '407bb199-81af-403a-8b44-b7433206de41',
            'aco' => 'Resource',
            'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
            'aro' => 'Group',
            'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'type' => 15,
            'created' => '2017-11-17 12:37:17',
            'modified' => '2017-11-17 12:37:17'
        ],
        [
            'id' => '4252ba53-db0e-4168-8e0b-faea043e215c',
            'aco' => 'Resource',
            'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 1,
            'created' => '2017-11-17 12:37:30',
            'modified' => '2017-11-17 12:37:30'
        ],
        [
            'id' => '45cd04d1-efec-49f5-9e55-3893976dca1a',
            'aco' => 'Resource',
            'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:15',
            'modified' => '2017-11-17 12:37:15'
        ],
        [
            'id' => '4b830a32-e365-4d9d-b7a7-109d75eb23e4',
            'aco' => 'Resource',
            'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 7,
            'created' => '2017-11-17 12:37:32',
            'modified' => '2017-11-17 12:37:32'
        ],
        [
            'id' => '58739636-530d-4659-93b8-7653e61b1f75',
            'aco' => 'Resource',
            'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:10',
            'modified' => '2017-11-17 12:37:10'
        ],
        [
            'id' => '6254d75f-d8a7-44ab-a1ee-84823bccdc65',
            'aco' => 'Resource',
            'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:15',
            'modified' => '2017-11-17 12:37:15'
        ],
        [
            'id' => '71e63875-2b8c-43f1-b9f4-ef34119dc377',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:07',
            'modified' => '2017-11-17 12:37:07'
        ],
        [
            'id' => '7506ea8e-5107-46e2-a253-49308214cabf',
            'aco' => 'Resource',
            'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:25',
            'modified' => '2017-11-17 12:37:25'
        ],
        [
            'id' => '852d86ea-35e8-42fe-a4f7-d97c1b28993a',
            'aco' => 'Resource',
            'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 1,
            'created' => '2017-11-17 12:37:36',
            'modified' => '2017-11-17 12:37:36'
        ],
        [
            'id' => '877a8314-bf82-44d7-9ecd-cd32e9a06601',
            'aco' => 'Resource',
            'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:24',
            'modified' => '2017-11-17 12:37:24'
        ],
        [
            'id' => '8a4ee253-3baf-4eaa-97fc-c2025cf05d56',
            'aco' => 'Resource',
            'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 1,
            'created' => '2017-11-17 12:37:05',
            'modified' => '2017-11-17 12:37:05'
        ],
        [
            'id' => '8b1e7ea2-0cb1-4e41-a2d9-60bf23564f19',
            'aco' => 'Resource',
            'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 1,
            'created' => '2017-11-17 12:37:38',
            'modified' => '2017-11-17 12:37:38'
        ],
        [
            'id' => '8d4e126c-d513-4ab4-9f35-c786801b195b',
            'aco' => 'Resource',
            'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:20',
            'modified' => '2017-11-17 12:37:20'
        ],
        [
            'id' => 'a04bec9d-7711-4cf3-a835-99b45cada127',
            'aco' => 'Resource',
            'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:21',
            'modified' => '2017-11-17 12:37:21'
        ],
        [
            'id' => 'a5876e80-893d-4e0a-a699-421da2f34859',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:13',
            'modified' => '2017-11-17 12:37:13'
        ],
        [
            'id' => 'a680c79e-4e20-4b89-989a-e77d9d707ec1',
            'aco' => 'Resource',
            'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
            'aro' => 'Group',
            'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
            'type' => 7,
            'created' => '2017-11-17 12:37:19',
            'modified' => '2017-11-17 12:37:19'
        ],
        [
            'id' => 'a8a20d43-c60d-4817-8fb9-5d66a6ccec4f',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'type' => 7,
            'created' => '2017-11-17 12:37:09',
            'modified' => '2017-11-17 12:37:09'
        ],
        [
            'id' => 'a8cac22f-4a52-4560-aeaa-bc260a295bdc',
            'aco' => 'Resource',
            'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:05',
            'modified' => '2017-11-17 12:37:05'
        ],
        [
            'id' => 'aed6f7c1-d830-40b5-840c-be4af3df81a0',
            'aco' => 'Resource',
            'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:27',
            'modified' => '2017-11-17 12:37:27'
        ],
        [
            'id' => 'b12c00b9-b719-4d9c-9707-1a0217d9eace',
            'aco' => 'Resource',
            'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:23',
            'modified' => '2017-11-17 12:37:23'
        ],
        [
            'id' => 'b4e83790-c9a2-4dec-9696-2a41d8df2d47',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:41',
            'modified' => '2017-11-17 12:37:41'
        ],
        [
            'id' => 'b5896430-4c3e-44ed-b689-b3ca55762efd',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:12',
            'modified' => '2017-11-17 12:37:12'
        ],
        [
            'id' => 'bd5a769e-924d-4cae-9186-591fcef5b309',
            'aco' => 'Resource',
            'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 15,
            'created' => '2017-11-17 12:37:31',
            'modified' => '2017-11-17 12:37:31'
        ],
        [
            'id' => 'c2af0d72-8d0c-4021-8204-d1d9cbe40629',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'type' => 15,
            'created' => '2017-11-17 12:37:08',
            'modified' => '2017-11-17 12:37:08'
        ],
        [
            'id' => 'c30231c9-7752-42aa-a92d-36bf3c416bd8',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 1,
            'created' => '2017-11-17 12:37:06',
            'modified' => '2017-11-17 12:37:06'
        ],
        [
            'id' => 'c93bb86a-4945-4fe5-8e19-3777308564fe',
            'aco' => 'Resource',
            'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 15,
            'created' => '2017-11-17 12:37:34',
            'modified' => '2017-11-17 12:37:34'
        ],
        [
            'id' => 'cba6ff8f-151e-43c1-86f1-1671f45961e3',
            'aco' => 'Resource',
            'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:37',
            'modified' => '2017-11-17 12:37:37'
        ],
        [
            'id' => 'ce0f885e-17fa-41b6-959f-925afe866cf4',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:14',
            'modified' => '2017-11-17 12:37:14'
        ],
        [
            'id' => 'd2d079e6-a39a-43ca-9f00-3d2e2dc6f7fb',
            'aco' => 'Resource',
            'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:16',
            'modified' => '2017-11-17 12:37:16'
        ],
        [
            'id' => 'dfd1ca28-9297-493a-bea6-48dd79adf857',
            'aco' => 'Resource',
            'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:28',
            'modified' => '2017-11-17 12:37:28'
        ],
        [
            'id' => 'f42f81d1-9a6b-48fb-a0ee-ad075a204ebb',
            'aco' => 'Resource',
            'aco_foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'fb4e9591-2883-4edf-bdb3-9b5d0db6a917',
            'aco' => 'Resource',
            'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:35',
            'modified' => '2017-11-17 12:37:35'
        ],
    ];
}
