<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;
use App\Utility\UuidFactory;
use App\Model\Entity\Permission;

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

    public function init()
    {
        $this->records = [
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.apache'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
                'type' => Permission::READ,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
                'type' => Permission::READ,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.centos'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.centos'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
                'type' => Permission::UPDATE,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.cakephp'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.chai'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.composer'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.debian'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.developer'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.docker'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.enlightenment'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.enlightenment'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('group.id.carol'),
                'type' => Permission::READ,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.fosdem'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.fosdem'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('group.id.carol'),
                'type' => Permission::READ,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.framasoft'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.framasoft'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('group.id.carol'),
                'type' => Permission::READ,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.fsfe'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.developer'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.fsfe'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('group.id.carol'),
                'type' => Permission::READ,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.ftp'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.ftp'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('group.id.betty'),
                'type' => Permission::READ,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.grogle'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
                'type' => Permission::READ,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.grogle'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('group.id.carol'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.grunt'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
                'type' => Permission::UPDATE,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.grunt'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('group.id.carol'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.gnupg'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.ergnomom'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.gnupg'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
                'type' => Permission::READ,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.git'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.ergnomom'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.git'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
                'type' => Permission::READ,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('group.id.carol'),
                'type' => Permission::UPDATE,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid('group.id.carol'),
                'type' => Permission::UPDATE,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
            [
                'id' => UuidFactory::uuid(),
                'aco' => 'Resource',
                'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
                'aro' => 'Group',
                'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
                'type' => Permission::OWNER,
                'created' => '2017-11-17 12:37:04',
                'modified' => '2017-11-17 12:37:04'
            ],
        ];
        parent::init();
    }
}
