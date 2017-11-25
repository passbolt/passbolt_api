<?php
namespace App\Test\Fixture\alt0;

use App\Utility\UuidFactory;
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

    public function init()
    {
        $this->records = [
            [
                'id' => UuidFactory::uuid(),
                'group_id' => UuidFactory::uuid('group.id.accounting'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'is_admin' => true,
                'created' => '2017-11-17 12:37:03'
            ],
            [
                'id' => UuidFactory::uuid(),
                'group_id' => UuidFactory::uuid('group.id.accounting'),
                'user_id' => UuidFactory::uuid('user.id.betty'),
                'is_admin' => false,
                'created' => '2017-11-17 12:37:03'
            ],
            [
                'id' => UuidFactory::uuid(),
                'group_id' => UuidFactory::uuid('group.id.board'),
                'user_id' => UuidFactory::uuid('user.id.betty'),
                'is_admin' => true,
                'created' => '2017-11-17 12:37:03'
            ],
            [
                'id' => UuidFactory::uuid(),
                'group_id' => UuidFactory::uuid('group.id.board'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'is_admin' => true,
                'created' => '2017-11-17 12:37:03'
            ],
            [
                'id' => UuidFactory::uuid(),
                'group_id' => UuidFactory::uuid('group.id.creative'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'is_admin' => true,
                'created' => '2017-11-17 12:37:03'
            ],
            [
                'id' => UuidFactory::uuid(),
                'group_id' => UuidFactory::uuid('group.id.developer'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'is_admin' => true,
                'created' => '2017-11-17 12:37:03'
            ],
            [
                'id' => UuidFactory::uuid(),
                'group_id' => UuidFactory::uuid('group.id.developer'),
                'user_id' => UuidFactory::uuid('user.id.betty'),
                'is_admin' => true,
                'created' => '2017-11-17 12:37:03'
            ],
            [
                'id' => UuidFactory::uuid(),
                'group_id' => UuidFactory::uuid('group.id.ergonom'),
                'user_id' => UuidFactory::uuid('user.id.dame'),
                'is_admin' => true,
                'created' => '2017-11-17 12:37:03'
            ],
            [
                'id' => UuidFactory::uuid(),
                'group_id' => UuidFactory::uuid('group.id.freelancer'),
                'user_id' => UuidFactory::uuid('user.id.edith'),
                'is_admin' => true,
                'created' => '2017-11-17 12:37:03'
            ],
            [
                'id' => UuidFactory::uuid(),
                'group_id' => UuidFactory::uuid('group.id.freelancer'),
                'user_id' => UuidFactory::uuid('user.id.frances'),
                'is_admin' => false,
                'created' => '2017-11-17 12:37:03'
            ],
            [
                'id' => UuidFactory::uuid(),
                'group_id' => UuidFactory::uuid('group.id.freelancer'),
                'user_id' => UuidFactory::uuid('user.id.grace'),
                'is_admin' => false,
                'created' => '2017-11-17 12:37:03'
            ]
        ];
        parent::init();
    }
}
