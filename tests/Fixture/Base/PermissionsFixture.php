<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PermissionsFixture
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
                'id' => '0402c60f-7053-501f-9e8d-d416c7770ca5',
                'aco' => 'Resource',
                'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '0718ec88-a867-57b8-ad9f-671237554469',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '078ad063-fc87-5ecd-8388-57e434bd62e4',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '0825fbab-4298-594d-8db9-4bcfe64cee07',
                'aco' => 'Resource',
                'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '09e93d10-8da6-51f1-8dcf-f9569866eb40',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '0c628ca9-56f6-508f-81e4-a57575a5974f',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '0c6831d8-7493-521b-83a9-0ef3180c6362',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 15,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '0dbc240f-27ab-5137-ab79-f16754f4a847',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '0e36783e-a634-5dfc-9111-2f92fcee2b14',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '0eb3eff4-6f04-540f-bee1-4d6564f9a85f',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '145df3b2-8b9a-56f9-9049-1b44c23853e1',
                'aco' => 'Resource',
                'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '181bfe99-d953-5edf-b686-8fa1119ebb14',
                'aco' => 'Resource',
                'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '194ed7b3-9421-59cc-93e6-0b9f370762a4',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '199e67cd-04fd-5a48-8e15-54ca2c32dd46',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '1af29ef5-166a-5340-8776-4dd1e69efb8b',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '1b7542c8-8ad9-51cf-b39e-6284c99e59f5',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '1e11af8d-08a5-509f-b8ed-1ddeede93ccc',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '1e9c5829-4f46-5b14-a94f-3ed4b65a714e',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '1f0befe1-eeff-57e0-9ef6-66b86ecbeefa',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '21162d13-4088-5fa2-92a3-ee5e4f6aded2',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '2169588b-cf80-51f4-82a2-4b5e914d0d47',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '2208a4bb-9c47-5c07-a0ba-2d7344a3a9ae',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 7,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '243d427b-3556-57c1-916d-1b66d2d0b018',
                'aco' => 'Resource',
                'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '25ca8eca-eb2d-55d0-bf80-efa7807f8515',
                'aco' => 'Resource',
                'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '2762e52a-e4bf-5b5f-850a-e4976b71ac78',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '27abc1c6-c0a5-5361-a41c-0c8426d7af39',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '2849f828-5b30-5027-af6a-19495963e4ff',
                'aco' => 'Resource',
                'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '28e4244c-d7cd-5eeb-944a-25f870381921',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '2a914ce8-13d3-54a2-a887-af8200cb9b98',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '3068c70f-cbe4-5c32-8921-eec07777166b',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 15,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '338d1589-cc76-5eb6-9225-15d354cc58dd',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 7,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '36366a82-3d75-5e0e-97d3-0437ad4ee2cf',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '36998a09-8ed7-511f-b193-4d4806f0ef21',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '37698c89-94ac-5fb8-9136-b294bc34fe1e',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '37ad7f5d-e3b7-540b-a969-5b79734e4248',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 15,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '397bb251-4f93-598a-98e5-5b5f3e59dd57',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '39acc708-f643-548f-b305-14236220327d',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '39b95688-1652-5165-89b4-bd852022de11',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '3bb4592e-7788-538f-bb7b-e3136e8ce28e',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '3d46aa16-a2a7-588a-ada9-89595978d39b',
                'aco' => 'Resource',
                'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '40e26b09-8ed0-5d15-9b0c-d595bbb3cfe4',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '4283465f-2ef8-5821-8f13-441de586c7cb',
                'aco' => 'Resource',
                'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '4c90edfa-9eb5-5d0d-9b80-9a2e10286c94',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '4cc0020e-3cfe-5641-9413-67a8bba8843b',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '4f3b33f7-74b7-51ca-8a9a-d6f310c99440',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '521b4284-e8fc-55a8-8d32-03a82d3eebf3',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '52e83227-af65-5cbc-95e8-c329a353925b',
                'aco' => 'Resource',
                'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '557496ad-e177-529a-a91e-3cdcd15df167',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '56a27867-5181-5935-a2e1-a4a6ed036a62',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '580e7d71-75e7-5335-b7ce-12b7b783eb6e',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '5b1d78a2-15f2-5be3-9a1a-412ddf83e6f6',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '5e67fdae-b640-5a44-91f8-44befe60ee1e',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '6065b902-48ca-5618-bf06-09ecc2686d18',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '608d7a91-e45d-506f-8749-2b73764a574a',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '629315f6-c8b5-55b2-ae29-220715d7bda1',
                'aco' => 'Resource',
                'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '642445e1-8e7c-5217-a8c4-c01283e894fe',
                'aco' => 'Resource',
                'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '654a0672-dfbb-5bc2-b296-e160c60d22d6',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '66073597-5323-5413-a6bd-b288b724b2c3',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '672728ac-c3f2-52a5-a21c-07dfe84b7ad9',
                'aco' => 'Resource',
                'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '69e1efd1-fcb5-5555-8fb4-514af8b3220c',
                'aco' => 'Resource',
                'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '6a127fe9-e660-592a-b023-68946b78b122',
                'aco' => 'Resource',
                'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '6a905f61-bec9-5388-8309-6681078b5fa4',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'User',
                'aro_foreign_key' => '1e73e104-d53e-579d-a0c4-e9aeaca76c56',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '6abe6918-bb37-506d-8c80-1c74d61ff5ac',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '6c586f90-6ded-5e66-baf8-3cfff8afa214',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '6eb6003f-7974-5d0a-8a55-eee6ca7f81eb',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '6f65173d-a5e8-5014-9659-e1bb4f19707d',
                'aco' => 'Resource',
                'aco_foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '6fbb477b-9670-5db2-a2ee-8af633f4631a',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '6fc269ec-2b6f-5d42-ad25-c10f7f3bb731',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '75d3e1dc-e589-5746-ac61-6ab690d22066',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '77624b0c-b81e-5575-8924-0a1f048eb680',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '7848652f-6f6d-5d2a-9aee-c17ac7cc7457',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '79c84454-e32b-5410-ab2c-026ac9c87244',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '7aa03168-d326-50ef-be12-6f25aa7bf2de',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '7aadbabd-3f7d-5bc6-8b64-9c830017ce70',
                'aco' => 'Resource',
                'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '7d3dcd8a-5757-5c39-8fe1-7b0f904d4730',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '7f8c7c5e-2133-5091-a4de-892ba97686cb',
                'aco' => 'Resource',
                'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '81b479f4-5661-5050-834e-aa9d79a90a45',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '82c38b87-b9aa-5b44-a8e3-50a8b64a2fbd',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '83d14e0b-4889-59f0-9eac-aa7f4464dbb7',
                'aco' => 'Resource',
                'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '84233c83-fae8-5ce1-ac52-032d676a981c',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '849b1743-e145-53a8-85ca-edf78823c801',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '84ae712d-1d2a-559e-b3b3-007c33735eb3',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 7,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '853cee9c-9f0a-50bd-bc08-4184b408b06c',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '85f87f38-40f5-5eca-91f5-601a51b66f2a',
                'aco' => 'Resource',
                'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '86e6bc13-4e75-5d6b-8c2a-555b31377187',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '898ce1d0-601f-5194-976b-147a680dd472',
                'aco' => 'Resource',
                'aco_foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '8be6ce8d-083d-51d7-b12f-aa2367c1059e',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '8c5b5b24-b339-5bde-a947-e54706fcdc2d',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 7,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '8cbffc4d-3692-5b82-a0d9-56524c563453',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 15,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '8dfd59a7-852d-5c57-bd45-75c28bbb3f6c',
                'aco' => 'Resource',
                'aco_foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '8faf6335-48df-5a9f-9e6c-4f2a4b226b73',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '91940345-7e5f-5465-b2b1-93d440207097',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '9250856b-57ee-5660-8dcb-ca48bb5395ec',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '9512511d-b895-55a6-b424-bb07e6b29e1a',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '96cbcf82-e852-5232-ade2-9b648380e59f',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '97125c1d-257d-5b90-a33c-088de339fc05',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 7,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '972bf3fc-0d5b-579c-9097-56d86394c255',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '98ac06ff-d605-52c6-9d38-c6d1d950439d',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '98f80aea-541d-566f-b8a7-d72474c1dad9',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '9b06ad36-3b82-503a-a8e6-d83e85bfd6ac',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '9c6c0abb-3a81-5274-8f23-73b7b93fee4c',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => '9cdadfb4-4cc8-5d79-9f10-c4a58ffa0060',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '9df69989-5eff-5898-8878-a34e3144001e',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => '9ea3efed-b358-541c-8379-7b7162a8f562',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => '9f340f51-f1a2-5ff9-8f48-82521b2e5d04',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 15,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'a3e34b8c-1145-5a46-af32-1950df3d98b2',
                'aco' => 'Resource',
                'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'a6b17854-806c-5232-aec5-c213a657bc36',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'a6bdc2d9-3c37-5fc4-a383-ec1249ff5611',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 7,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'a6ed31ab-1b07-504c-bb56-d7257b436c97',
                'aco' => 'Resource',
                'aco_foreign_key' => '662497d8-7f1d-550a-9133-0fedd7250867',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 7,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'a79f4026-0162-588a-90f8-9b83f62ad71b',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'a80a4cea-371a-5686-970f-2c16a402b9db',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'a99187f5-6ca7-57d5-bfb9-c9511dbd95d1',
                'aco' => 'Resource',
                'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'aadb4110-50ae-5be3-8887-b3d1e3640835',
                'aco' => 'Resource',
                'aco_foreign_key' => '09c790c0-c003-53c8-a640-25d33cfebc22',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'ab006b27-526e-52aa-9379-2b13a628d751',
                'aco' => 'Resource',
                'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'adc451c8-d721-50f1-978f-dfb01a270ca8',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'ae2ea2b0-a825-5543-9c28-4f0ee6b91918',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'b0c3bb0a-4b45-5280-9bdb-90568f7d29fe',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'b0ff4313-1bf9-51a2-9a53-436938cd0ce3',
                'aco' => 'Resource',
                'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'b1138a67-5534-51f1-92f4-3e69f32441c3',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'b326db05-4cdd-5ce1-ab74-f2024a0cb2f4',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'b332a736-6fbb-5080-97f9-18db42f3c1d0',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'b3ec81f7-1039-592a-afae-644b952717bc',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'b83d3700-6b9c-51a1-8cf7-007fad9423d0',
                'aco' => 'Resource',
                'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'b8e7077e-460c-52bc-a5b5-05b5add5ef8e',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'b91fdbb4-24b6-5456-a4a7-4946311dc130',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'b9943a0a-d8b9-5982-9e09-ca79fd58a639',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'bd0f44ac-4930-5f81-ad0a-153caf672be8',
                'aco' => 'Resource',
                'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'bf5bc0d8-e651-56f1-932e-3aa1e713c49f',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'c059706a-62d6-52ce-a6b9-797602cd7720',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'c07f4730-2a72-5089-8173-d8b78aa58fbf',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'c1ca5549-98f1-5199-9065-3fa8d1bee6e8',
                'aco' => 'Resource',
                'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'c1cc3954-76b7-56f0-8134-00c66acf5402',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'c22f62f5-6fd0-5fd1-b42f-4dd2bef6714e',
                'aco' => 'Resource',
                'aco_foreign_key' => 'c8b93000-56b3-5a16-8048-c579d1babbd7',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'c2c1f2fa-515c-5b33-83f4-7a0bb1cce108',
                'aco' => 'Resource',
                'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'c344543a-4223-51d6-9144-328e79098491',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'c953dc56-86ee-5932-ae24-a2df7003c906',
                'aco' => 'Resource',
                'aco_foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'ca5c80a4-cf4a-558f-a0d5-389db640255b',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'Group',
                'aro_foreign_key' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'type' => 15,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'cb03409b-9cd6-5d64-8df5-ff60a8a38a41',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'User',
                'aro_foreign_key' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'ce3f9b4b-3361-51b3-807c-a38897be46c7',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'cee750b6-60c8-55c5-bd2c-f69544b31810',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 15,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'cf22bc91-697a-53eb-a433-80101457b310',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'd06a8538-3883-5b0f-9ed7-812a75f3b9e3',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'd113dd06-f287-5178-9d04-0c035ef36cb0',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 15,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'd14e333e-4071-5461-9c1b-f9b70bf1eeef',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 7,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'd17d758c-f6a6-5ae5-a85d-94fad614fe6c',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'd33d5e15-7b43-516a-acd1-dbb4049c03ee',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'd354b624-b988-59d0-89ad-e95ba4a3e10b',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'd48886d8-0025-558f-b9b2-041bd6322053',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'd61deb9c-b355-5148-a952-829441e4d5ce',
                'aco' => 'Resource',
                'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'd6d138e6-18f7-5411-b560-b26d589ef6ab',
                'aco' => 'Resource',
                'aco_foreign_key' => '2a08d0ad-cd50-5f06-a1b1-a2fa46e44d3f',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'd6e870b5-3dc0-5b5c-b1b1-56b948931927',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'd84323b8-db0f-5d09-904b-e4b6b824d9cc',
                'aco' => 'Resource',
                'aco_foreign_key' => '8378fa3d-b9f4-5428-90a4-ab5478c1a5bb',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'd8af35a1-337e-58cf-99b5-56e7872e2fda',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'd9a35a16-1218-5be4-a660-bf5935bfa1ea',
                'aco' => 'Resource',
                'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'db1641db-b058-533d-917b-2145366536d2',
                'aco' => 'Resource',
                'aco_foreign_key' => 'daaf057e-7fc3-5537-a8a9-e8c151890878',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 7,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'de54b1fb-fcb7-54e0-a80b-c5ad567b7c43',
                'aco' => 'Resource',
                'aco_foreign_key' => '6e66e10e-36d7-5d4e-8fa2-8474e4510819',
                'aro' => 'User',
                'aro_foreign_key' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'de99b3b4-7052-5c99-b309-f3dfe354283c',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'e0fba0a6-d7b1-57c3-bf9b-ec7b36f62ac5',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 15,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'e2c1da09-344b-5d87-a6d2-40461bb4868e',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'e32b034c-d780-51f4-a89a-44042a5f69e0',
                'aco' => 'Resource',
                'aco_foreign_key' => '690b6e40-f371-579c-b0c6-86e8ef383adc',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'e48ec870-796c-5faf-8e6b-0b6275e987d5',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'Group',
                'aro_foreign_key' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'e4e7167c-3df5-52a8-8593-c17175685cfe',
                'aco' => 'Resource',
                'aco_foreign_key' => '4d7adb92-0d85-56d7-8b92-e2b919ef8eb8',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'e51e1673-81f1-5e3c-8f5e-815fc5295b3a',
                'aco' => 'Resource',
                'aco_foreign_key' => '9e2d7f42-4164-5882-9445-92e42a8cf067',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 1,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'e5666018-2432-5e29-be81-0e56c0de0d8c',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'User',
                'aro_foreign_key' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'e7aabc04-691e-5352-bc5f-b5d794d71907',
                'aco' => 'Resource',
                'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'ec3a3692-6451-5417-a9e7-243dda8acbae',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'ef4a4d28-62b8-50cd-8695-cb3a6dca77e6',
                'aco' => 'Resource',
                'aco_foreign_key' => 'f7cef480-fcc3-5c20-a043-340c62e89cd8',
                'aro' => 'User',
                'aro_foreign_key' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'f03ba314-f5f8-586e-a79c-11ef6b7f36bc',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 7,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'f0eb8208-679b-5656-841b-9956cc488e06',
                'aco' => 'Resource',
                'aco_foreign_key' => 'faba99af-9c7a-5801-ac9d-acaf4c0988fe',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
            [
                'id' => 'f1a264f8-eb84-5143-876c-4a1116008cd5',
                'aco' => 'Resource',
                'aco_foreign_key' => 'dbddca52-9d16-53b6-81ad-daaf730cd675',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'f1d7b16f-99ed-5242-9bdc-fdc045d14cd1',
                'aco' => 'Resource',
                'aco_foreign_key' => 'd2ab45e9-0d70-5ae3-a373-d2f381bccd99',
                'aro' => 'User',
                'aro_foreign_key' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'type' => 1,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'f423c773-9138-5a33-a269-195233a41fad',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'User',
                'aro_foreign_key' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'f9a157db-2635-51cc-b7f3-34d53a852693',
                'aco' => 'Resource',
                'aco_foreign_key' => '90da24d8-9862-59e4-8748-33cd4563bd81',
                'aro' => 'User',
                'aro_foreign_key' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'type' => 7,
                'created' => '2019-07-02 18:51:50',
                'modified' => '2019-07-02 18:51:50'
            ],
            [
                'id' => 'faeda794-c431-509c-82d2-3f0b936e0806',
                'aco' => 'Resource',
                'aco_foreign_key' => '76d75fef-d7ed-5a0d-8df0-0a0ffb7c44c8',
                'aro' => 'Group',
                'aro_foreign_key' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'type' => 15,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'fc64507b-6a84-5454-a1c7-e0fac3513abd',
                'aco' => 'Resource',
                'aco_foreign_key' => 'ecf0ed85-3bfc-5f45-b11d-74e9a86aa313',
                'aro' => 'Group',
                'aro_foreign_key' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'fedefeee-ee7d-56a5-8147-6c5aecd688bf',
                'aco' => 'Resource',
                'aco_foreign_key' => '73e3309f-1121-5eca-8777-37a7451ee386',
                'aro' => 'Group',
                'aro_foreign_key' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'type' => 1,
                'created' => '2019-07-02 18:51:51',
                'modified' => '2019-07-02 18:51:51'
            ],
            [
                'id' => 'ffe06f25-6a21-5200-adae-09c82bb7d918',
                'aco' => 'Resource',
                'aco_foreign_key' => '0e292a47-1c2c-513e-aaeb-ada143d3e728',
                'aro' => 'User',
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'type' => 15,
                'created' => '2019-07-02 18:51:49',
                'modified' => '2019-07-02 18:51:49'
            ],
        ];
        parent::init();
    }
}
