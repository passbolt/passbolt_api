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
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => '04e7d8bc-056a-5e4f-b857-4ee179c39bf4',
            'group_id' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'is_admin' => false,
            'created' => '2018-04-23 12:04:41'
        ],
        [
            'id' => '4164a504-74cb-5899-9425-fa0152994817',
            'group_id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
            'user_id' => '620de627-8f07-5427-9149-e2c43219c5aa',
            'is_admin' => false,
            'created' => '2018-04-23 12:04:41'
        ],
        [
            'id' => '587d324f-f393-5727-9337-5fd12360eb5a',
            'group_id' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'is_admin' => true,
            'created' => '2018-04-23 12:04:41'
        ],
        [
            'id' => '6410654c-597a-5387-bfe5-8df69efa8b19',
            'group_id' => '4cdc85b3-f442-5511-b28d-cbd109100189',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'is_admin' => true,
            'created' => '2018-04-23 12:04:41'
        ],
        [
            'id' => '68889c93-224a-5daa-ba57-7912c8419732',
            'group_id' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'is_admin' => true,
            'created' => '2018-04-23 12:04:41'
        ],
        [
            'id' => '836d45fc-3e98-531b-bf68-fafbb767d2d7',
            'group_id' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
            'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'is_admin' => true,
            'created' => '2018-04-23 12:04:41'
        ],
        [
            'id' => '8cdd6d1f-7f08-5192-85db-66c618445320',
            'group_id' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'is_admin' => true,
            'created' => '2018-04-23 12:04:41'
        ],
        [
            'id' => '8dcefdf8-ff2c-5167-8727-473f8fa818cf',
            'group_id' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'is_admin' => true,
            'created' => '2018-04-23 12:04:41'
        ],
        [
            'id' => 'b19bec56-dbc6-5514-bb2c-28b05497d47d',
            'group_id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
            'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
            'is_admin' => true,
            'created' => '2018-04-23 12:04:41'
        ],
        [
            'id' => 'b7ad13f1-3b49-5a8e-95cf-dabcf6ec72ad',
            'group_id' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'is_admin' => true,
            'created' => '2018-04-23 12:04:41'
        ],
        [
            'id' => 'e2b8dd91-ffc4-5c42-a5f3-9745c74109d8',
            'group_id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
            'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'is_admin' => false,
            'created' => '2018-04-23 12:04:41'
        ],
    ];
}
