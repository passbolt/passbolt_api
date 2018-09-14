<?php
namespace App\Test\Fixture\Alt0;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GroupsUsersFixture
 *
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
                'id' => '04e7d8bc-056a-5e4f-b857-4ee179c39bf4',
                'group_id' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
                'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'is_admin' => false,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '20971b7c-943f-5b78-a513-da776a857109',
                'group_id' => '4ff007f6-80ec-5bf7-8f0a-46a17178db6f',
                'user_id' => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '260f596c-9aba-50f9-b6a5-afe2d44ed9b1',
                'group_id' => 'c9c8fd8e-a0fa-53f0-967b-42edca3d91e4',
                'user_id' => '5302c3cb-5d33-53b1-82cd-57df36e13acc',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '4164a504-74cb-5899-9425-fa0152994817',
                'group_id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'user_id' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'is_admin' => false,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '42cf7c41-c7b4-50f9-8f97-172c50163599',
                'group_id' => '3feba74f-47da-5146-9d8f-76c7266c60ea',
                'user_id' => '8d038399-ecac-55b4-8ad3-b7f0650de2a2',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '46ed6559-24d1-50e5-98cd-e9b105484ed1',
                'group_id' => '3feba74f-47da-5146-9d8f-76c7266c60ea',
                'user_id' => 'f7e9754a-2f64-5cdd-8ba2-178b33383505',
                'is_admin' => false,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '587d324f-f393-5727-9337-5fd12360eb5a',
                'group_id' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '6410654c-597a-5387-bfe5-8df69efa8b19',
                'group_id' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '68889c93-224a-5daa-ba57-7912c8419732',
                'group_id' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '70d733ae-1034-542c-b715-689ba1400f7f',
                'group_id' => 'de5fcb71-6db2-5fe5-8803-7d3eb4d6ad9c',
                'user_id' => '92946500-2940-54ff-889a-3da69afe5078',
                'is_admin' => false,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '836d45fc-3e98-531b-bf68-fafbb767d2d7',
                'group_id' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '87cf7142-c885-5d88-9365-a2ade468e9f3',
                'group_id' => '516c2db6-0aed-52d8-854f-b3f3499995e7',
                'user_id' => 'e7fa0375-61df-5dbc-9e42-e0d363bd0ecf',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '8cdd6d1f-7f08-5192-85db-66c618445320',
                'group_id' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => '8dcefdf8-ff2c-5167-8727-473f8fa818cf',
                'group_id' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => 'aaf8be68-e1b1-5cf2-a757-3632dae0f81c',
                'group_id' => '428ed4cd-81b1-56af-aa7f-a7cbdbe227e4',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => 'b07c3563-0fd1-5db3-8e66-dd3f1c2611c2',
                'group_id' => 'f16c507f-9105-502e-aa8a-ba24c36dbdcf',
                'user_id' => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => 'b19bec56-dbc6-5514-bb2c-28b05497d47d',
                'group_id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => 'b7ad13f1-3b49-5a8e-95cf-dabcf6ec72ad',
                'group_id' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => 'd02ef0c6-03a0-5055-b18b-86bf02fa3787',
                'group_id' => 'de5fcb71-6db2-5fe5-8803-7d3eb4d6ad9c',
                'user_id' => 'a0559bb5-050b-50a3-ad39-c6756a46dbb7',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => 'e0240e9b-fee5-5804-8db3-72065d0fc130',
                'group_id' => 'b7cbce9f-6a20-545b-b20a-fcf4092307df',
                'user_id' => 'af5e1f70-a0ee-5b76-935b-c846f8a6a190',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => 'e2b8dd91-ffc4-5c42-a5f3-9745c74109d8',
                'group_id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
                'is_admin' => false,
                'created' => '2018-09-07 09:25:10'
            ],
            [
                'id' => 'ffed3025-5a4f-59e2-bd97-d04e5c53c1d6',
                'group_id' => 'a89b771e-62ab-5434-b2fa-950827439ac7',
                'user_id' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'is_admin' => true,
                'created' => '2018-09-07 09:25:10'
            ],
        ];
        parent::init();
    }
}
