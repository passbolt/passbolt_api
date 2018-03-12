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
            'id' => '02c6d271-e22b-46fe-b403-6191bc7bca8b',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:41',
            'modified' => '2017-11-17 12:37:41'
        ],
        [
            'id' => '0d2435a7-6be8-4e56-bbcc-91441e72f334',
            'aco' => 'Resource',
            'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 1,
            'created' => '2017-11-17 12:37:29',
            'modified' => '2017-11-17 12:37:29'
        ],
        [
            'id' => '0ed73c19-20d1-4bdf-a179-42da0920daf8',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:07',
            'modified' => '2017-11-17 12:37:07'
        ],
        [
            'id' => '171ef562-fc07-4457-ba44-b8a1afdea45e',
            'aco' => 'Resource',
            'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:23',
            'modified' => '2017-11-17 12:37:23'
        ],
        [
            'id' => '1796f77b-e47b-4e5f-83c0-e73d1f5d30ea',
            'aco' => 'Resource',
            'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:35',
            'modified' => '2017-11-17 12:37:35'
        ],
        [
            'id' => '18f4497c-5e27-4003-9390-d6b354e110ac',
            'aco' => 'Resource',
            'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:15',
            'modified' => '2017-11-17 12:37:15'
        ],
        [
            'id' => '1b337430-f1da-458d-99b5-2a9829efae38',
            'aco' => 'Resource',
            'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
            'aro' => 'Group',
            'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'type' => 15,
            'created' => '2017-11-17 12:37:17',
            'modified' => '2017-11-17 12:37:17'
        ],
        [
            'id' => '1ea8711e-255d-424f-8649-1c67c02d12e5',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:42',
            'modified' => '2017-11-17 12:37:42'
        ],
        [
            'id' => '23f3be89-ebac-4cf7-9cb9-1651b930f46b',
            'aco' => 'Resource',
            'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 15,
            'created' => '2017-11-17 12:37:31',
            'modified' => '2017-11-17 12:37:31'
        ],
        [
            'id' => '2a3b8930-8258-47f8-a61a-cb0fd9c2d8c1',
            'aco' => 'Resource',
            'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 7,
            'created' => '2017-11-17 12:37:32',
            'modified' => '2017-11-17 12:37:32'
        ],
        [
            'id' => '34641974-6c4f-41fc-a596-22df0fbc6516',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:12',
            'modified' => '2017-11-17 12:37:12'
        ],
        [
            'id' => '38bd4be0-31bc-4e8d-ba9c-5dc784bfde48',
            'aco' => 'Resource',
            'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 1,
            'created' => '2017-11-17 12:37:36',
            'modified' => '2017-11-17 12:37:36'
        ],
        [
            'id' => '3d4afc7c-2d25-4043-b2ee-3801f884c1e9',
            'aco' => 'Resource',
            'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:27',
            'modified' => '2017-11-17 12:37:27'
        ],
        [
            'id' => '43dd426f-d242-42ef-a1e4-7c87e183a599',
            'aco' => 'Resource',
            'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:21',
            'modified' => '2017-11-17 12:37:21'
        ],
        [
            'id' => '44681483-6045-4962-9c5e-f78a6d824242',
            'aco' => 'Resource',
            'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:25',
            'modified' => '2017-11-17 12:37:25'
        ],
        [
            'id' => '466dd667-7030-486d-b4c4-6451390134fc',
            'aco' => 'Resource',
            'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
            'aro' => 'Group',
            'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'type' => 15,
            'created' => '2017-11-17 12:37:26',
            'modified' => '2017-11-17 12:37:26'
        ],
        [
            'id' => '48328808-0c3b-48b5-82c4-0c6d13315c2f',
            'aco' => 'Resource',
            'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 15,
            'created' => '2017-11-17 12:37:34',
            'modified' => '2017-11-17 12:37:34'
        ],
        [
            'id' => '4c8723c5-b6df-4585-81fc-b0bfde37460a',
            'aco' => 'Resource',
            'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:15',
            'modified' => '2017-11-17 12:37:15'
        ],
        [
            'id' => '4ddd9686-7851-46d6-a2c2-384064b314c3',
            'aco' => 'Resource',
            'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 1,
            'created' => '2017-11-17 12:37:38',
            'modified' => '2017-11-17 12:37:38'
        ],
        [
            'id' => '666e87b9-b6a3-4ce3-b073-bde07f90e6d1',
            'aco' => 'Resource',
            'aco_foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '6b89b8ad-ebf8-4290-8ef5-1dd649111218',
            'aco' => 'Resource',
            'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:37',
            'modified' => '2017-11-17 12:37:37'
        ],
        [
            'id' => '6e787b26-e492-421e-85a6-9765840e33dd',
            'aco' => 'Resource',
            'aco_foreign_key' => '2d3958b8-18ba-5d0b-9464-0df0beec1433',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '7b5fb0a4-a20e-4bc7-a31a-84c1fc00f899',
            'aco' => 'Resource',
            'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:28',
            'modified' => '2017-11-17 12:37:28'
        ],
        [
            'id' => '7e702590-c3cd-4461-af0b-ebc755addf58',
            'aco' => 'Resource',
            'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:22',
            'modified' => '2017-11-17 12:37:22'
        ],
        [
            'id' => '86351f6f-6cf7-478e-860b-e39daba2512f',
            'aco' => 'Resource',
            'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
            'aro' => 'Group',
            'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
            'type' => 7,
            'created' => '2017-11-17 12:37:19',
            'modified' => '2017-11-17 12:37:19'
        ],
        [
            'id' => '8af01297-cd93-462b-ad1d-fc1437833c7c',
            'aco' => 'Resource',
            'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:16',
            'modified' => '2017-11-17 12:37:16'
        ],
        [
            'id' => '9012b646-01e4-4146-ab92-d55644a2d0b2',
            'aco' => 'Resource',
            'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:24',
            'modified' => '2017-11-17 12:37:24'
        ],
        [
            'id' => '90fa0934-1dc8-4805-825a-c102a58d1b0a',
            'aco' => 'Resource',
            'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:05',
            'modified' => '2017-11-17 12:37:05'
        ],
        [
            'id' => '94100a45-831d-4b57-ade0-56a8dd3be873',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 1,
            'created' => '2017-11-17 12:37:06',
            'modified' => '2017-11-17 12:37:06'
        ],
        [
            'id' => '9415af92-5a60-4196-a088-d339522cd4b7',
            'aco' => 'Resource',
            'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:20',
            'modified' => '2017-11-17 12:37:20'
        ],
        [
            'id' => '99c27715-1f87-42eb-a600-e393b07c0ae4',
            'aco' => 'Resource',
            'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:18',
            'modified' => '2017-11-17 12:37:18'
        ],
        [
            'id' => '9e432dd9-39c3-495a-a5a9-01df4afaa570',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:13',
            'modified' => '2017-11-17 12:37:13'
        ],
        [
            'id' => 'a643ab4e-184f-4270-9c15-1921840ce5d8',
            'aco' => 'Resource',
            'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 1,
            'created' => '2017-11-17 12:37:05',
            'modified' => '2017-11-17 12:37:05'
        ],
        [
            'id' => 'ab7ba605-6055-4471-ac94-d70f80498494',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:40',
            'modified' => '2017-11-17 12:37:40'
        ],
        [
            'id' => 'ae03c62f-2a8e-4d9b-a89f-cf4d2e2dc756',
            'aco' => 'Resource',
            'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:11',
            'modified' => '2017-11-17 12:37:11'
        ],
        [
            'id' => 'b34a886e-0be2-4c0a-bb14-7ed731a69707',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'type' => 7,
            'created' => '2017-11-17 12:37:09',
            'modified' => '2017-11-17 12:37:09'
        ],
        [
            'id' => 'b49d63e9-c23b-4454-ab43-6ac254eb42db',
            'aco' => 'Resource',
            'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 1,
            'created' => '2017-11-17 12:37:30',
            'modified' => '2017-11-17 12:37:30'
        ],
        [
            'id' => 'b55eac7b-1185-4c9f-9e88-51b32dd7194d',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:39',
            'modified' => '2017-11-17 12:37:39'
        ],
        [
            'id' => 'c33e2403-dda9-44a6-8f85-2252d23cb425',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'type' => 15,
            'created' => '2017-11-17 12:37:08',
            'modified' => '2017-11-17 12:37:08'
        ],
        [
            'id' => 'd7761e05-93cd-4a7e-80e3-9dbc29b3780e',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:43',
            'modified' => '2017-11-17 12:37:43'
        ],
        [
            'id' => 'e20954f9-3188-408b-976c-7de00990b6ef',
            'aco' => 'Resource',
            'aco_foreign_key' => '2d3958b8-18ba-5d0b-9464-0df0beec1433',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'e89f85b8-b4c3-4b91-969b-ad333df810a6',
            'aco' => 'Resource',
            'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:10',
            'modified' => '2017-11-17 12:37:10'
        ],
        [
            'id' => 'f3d701e5-b619-41a1-b8ab-f49c2252bc32',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:44',
            'modified' => '2017-11-17 12:37:44'
        ],
        [
            'id' => 'fbfd182c-63a2-48ab-a33a-8bdf3ed7ea71',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:14',
            'modified' => '2017-11-17 12:37:14'
        ],
    ];
}
