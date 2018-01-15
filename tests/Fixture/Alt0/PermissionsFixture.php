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
            'id' => '0cf190b9-47bb-4c0c-984c-14f1c7f926c0',
            'aco' => 'Resource',
            'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
            'aro' => 'Group',
            'aro_foreign_key' => '1ab1135b-383e-52fd-977a-704e40522576',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '1a542f82-14b9-45b9-a309-e19d33d2d229',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '2185f124-bfa2-4de5-9636-9e247eba69db',
            'aco' => 'Resource',
            'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '258f1877-8348-414e-871c-e83e7313b46c',
            'aco' => 'Resource',
            'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '2b6ac0c5-5069-421a-aed5-00a79c9a71c1',
            'aco' => 'Resource',
            'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '2c185e68-26d8-4d13-9ee2-7f4029bb59e5',
            'aco' => 'Resource',
            'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '313c9cfd-6a40-4152-b3b4-bf7ac20ee567',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '3166eee1-4871-40a5-b627-c7d879c92324',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '3256cd42-f368-461a-998d-edaf013733e8',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '38f9f0dd-b752-4fbf-8684-770ec5c69964',
            'aco' => 'Resource',
            'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '4a02a9cd-9018-4a02-81c6-1e455ab6ce3f',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '4b0458ea-6ecd-4714-acbf-8ed9a25f2ace',
            'aco' => 'Resource',
            'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '4e1571f4-ef68-40ec-845d-c0d0a0657cca',
            'aco' => 'Resource',
            'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '52a70f1f-c962-4744-9670-791ecfa7565b',
            'aco' => 'Resource',
            'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '54a0a1ca-7655-4235-939f-2d6ab0b9c46a',
            'aco' => 'Resource',
            'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '5560ce0f-4959-41d1-83b3-99db0d6859d9',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '5add15ec-20f3-42ed-bd9d-e0bf17f6b871',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '5ede7234-24ac-4ccb-80c0-f4d597d6d57d',
            'aco' => 'Resource',
            'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
            'aro' => 'Group',
            'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '60c5bb61-e6c0-478e-9b15-3f59fb2b014b',
            'aco' => 'Resource',
            'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '6114daf8-cc8b-4b23-9c32-8170ea69f850',
            'aco' => 'Resource',
            'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '62078e1e-0067-4fb2-b60b-072042269e53',
            'aco' => 'Resource',
            'aco_foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '65886de0-81ed-497f-80e4-781e3e5db6ae',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '669ee80d-9ece-476d-9752-9a3ea246c580',
            'aco' => 'Resource',
            'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '690e7750-f1b1-4462-b232-52896e6f7e92',
            'aco' => 'Resource',
            'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '6bff2862-a335-4a1f-8ecf-8ac521e841bb',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '710bb11a-f362-40a2-81fa-58a91c7cc00f',
            'aco' => 'Resource',
            'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '78370337-ae24-4a6b-b23d-a2c4abcfb44e',
            'aco' => 'Resource',
            'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '7cb69c28-849e-4f81-a86c-f739bfd14743',
            'aco' => 'Resource',
            'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '89468c99-14e8-47cc-b145-12990bf693ed',
            'aco' => 'Resource',
            'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => '924bdd52-447e-4607-9689-c5afa402f00f',
            'aco' => 'Resource',
            'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'a1528f3a-f7de-4fcd-af86-60d980d45f87',
            'aco' => 'Resource',
            'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'a16dbdad-7f80-4e11-9f13-51a5d4ab8100',
            'aco' => 'Resource',
            'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
            'aro' => 'User',
            'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'a7d58814-3323-47fe-9b31-3a6f47a0085b',
            'aco' => 'Resource',
            'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'b6edd25d-fe37-451f-b774-47d37f9e1577',
            'aco' => 'Resource',
            'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
            'aro' => 'Group',
            'aro_foreign_key' => '1ab1135b-383e-52fd-977a-704e40522576',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'b8f4a00d-2b75-4195-9607-196f4b1d443f',
            'aco' => 'Resource',
            'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
            'aro' => 'Group',
            'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'c0f60871-d7bd-4d80-b759-69d6d53e0fa0',
            'aco' => 'Resource',
            'aco_foreign_key' => '7015d152-1abd-5e14-bbc4-acff2cca2f86',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'cc3638e5-ac97-4ead-bffb-1f8c1c5d6e3c',
            'aco' => 'Resource',
            'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
            'aro' => 'Group',
            'aro_foreign_key' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'cf27f3d1-15eb-4ecd-8a28-c4d97881aaa4',
            'aco' => 'Resource',
            'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
            'aro' => 'Group',
            'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'daf50f85-c49a-4434-aa50-9ebcb3eb1d5d',
            'aco' => 'Resource',
            'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
            'aro' => 'Group',
            'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'dca3ec8f-eae6-48c2-bca4-1cf3645fc4db',
            'aco' => 'Resource',
            'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
            'aro' => 'Group',
            'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
            'type' => 7,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'fbd31c05-a833-446f-b540-28811d2f7c91',
            'aco' => 'Resource',
            'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
            'aro' => 'Group',
            'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'type' => 15,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
        [
            'id' => 'ff77b50c-bae7-4bdc-9b40-dc148b0d2ed0',
            'aco' => 'Resource',
            'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
            'aro' => 'User',
            'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'type' => 1,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ],
    ];
}
