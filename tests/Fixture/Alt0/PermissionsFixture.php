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
            'id' => '0032c67e-7568-4f54-9839-45fa0ec1c604',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '01304cb7-0918-424f-8072-4e58538af3a0',
            'aco' => 'Resource',
            'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '1045cb5f-0d91-44f8-880b-797b433b11f9',
            'aco' => 'Resource',
            'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
            'aro' => 'Group',
            'aro_foreign_key' => '1ab1135b-383e-52fd-977a-704e40522576',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '140f2a47-6794-4368-a076-9e38989b1d51',
            'aco' => 'Resource',
            'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '249cbfd4-8e68-4cd9-bcb6-ae096d2bd1fc',
            'aco' => 'Resource',
            'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '24a05c7c-63c9-4baf-82d5-6f0350536726',
            'aco' => 'Resource',
            'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
            'aro' => 'Group',
            'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '33a91d09-89c3-4183-9489-4222745ccd8e',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '41518459-f766-4281-80aa-6c31eadb910d',
            'aco' => 'Resource',
            'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
            'aro' => 'Group',
            'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '4400485c-ead0-41bd-b621-63f340e46f54',
            'aco' => 'Resource',
            'aco_foreign_key' => '2d3958b8-18ba-5d0b-9464-0df0beec1433',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '447c0704-b512-490f-b2b0-a716eeea249c',
            'aco' => 'Resource',
            'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '49c327cd-361f-43dd-84c7-fb1da7880fcb',
            'aco' => 'Resource',
            'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '4be0dd03-cede-4106-8995-a913f0a77c35',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '51f753a4-0e3a-4f28-8565-32cb11df3e8a',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '590535f1-3e9f-4b50-97b9-f6de418a8b10',
            'aco' => 'Resource',
            'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '5c099809-eb92-45e6-bc0c-74c1f9b06e8d',
            'aco' => 'Resource',
            'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '5db0cf4c-cdb3-4bbb-adc7-d9fc717675df',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '5e5b133d-6850-40d5-b812-3796c9727506',
            'aco' => 'Resource',
            'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '6a158ffc-8076-440d-af8b-1de08e5175ed',
            'aco' => 'Resource',
            'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '6adcb2bf-4787-459e-a9e3-04e00bd09bdb',
            'aco' => 'Resource',
            'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '7aa4e87a-bb1b-478e-b79e-b2794dcca504',
            'aco' => 'Resource',
            'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '8776008d-3efe-4518-bb9b-692be2b5b1e2',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '9fccb447-1142-45e0-9231-12266846b16e',
            'aco' => 'Resource',
            'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'ab32ff66-c1d1-4f17-a16d-44473b0b536c',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'acf9a1a4-ab77-4802-bed0-6c03f9c44f2a',
            'aco' => 'Resource',
            'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'b677835b-84c2-4930-b4db-73fd7b4b4420',
            'aco' => 'Resource',
            'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
            'aro' => 'Group',
            'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'ba0ae334-8425-4fc6-a6fd-9d76a6eabbe7',
            'aco' => 'Resource',
            'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
            'aro' => 'Group',
            'aro_foreign_key' => '1ab1135b-383e-52fd-977a-704e40522576',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'ba715979-0413-4c24-9ad3-a1c7bf10b3eb',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'bbc80acf-60e7-4cb1-91fc-3cf1bd8cf256',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'd0816d15-a20a-48a5-a00a-953e001b6015',
            'aco' => 'Resource',
            'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'd27a46fe-5f4d-4fce-9cd4-e00defdb24b7',
            'aco' => 'Resource',
            'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'd2bdc3f1-6307-4485-8706-888f9e8a3f28',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'dac1c446-9582-468c-b8ee-5ced013800e7',
            'aco' => 'Resource',
            'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'e3568f03-c5e7-4511-a2be-ef660330a913',
            'aco' => 'Resource',
            'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'e5566be5-996b-4b58-991e-0a70e519a07d',
            'aco' => 'Resource',
            'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'e8681564-1f68-4229-8740-59d3e9488ab2',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'e8c485dd-3e8b-4ba6-9092-a5d0aa37be29',
            'aco' => 'Resource',
            'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'edf3f8a8-bbe7-4659-a880-d7ea298e5343',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'f077744f-a402-4198-bf53-766026919c25',
            'aco' => 'Resource',
            'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'f2422847-136f-41b1-8022-543d8c2eab5c',
            'aco' => 'Resource',
            'aco_foreign_key' => '2d3958b8-18ba-5d0b-9464-0df0beec1433',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'f456032d-c800-4e12-bfa6-b6d1bb098daf',
            'aco' => 'Resource',
            'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'f980f503-e652-457c-9279-43d87264ba00',
            'aco' => 'Resource',
            'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'fa66d361-2061-4824-be80-63c8ddcbd0d1',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'fd1e1e68-09d7-4aea-9236-4f970964befa',
            'aco' => 'Resource',
            'aco_foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'ff8df6c6-79f3-4172-8a18-b4f2f3de67dc',
            'aco' => 'Resource',
            'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
    ];
}
