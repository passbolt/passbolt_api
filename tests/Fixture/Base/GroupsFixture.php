<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GroupsFixture
 *
 */
class GroupsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'deleted' => ['type' => 'index', 'columns' => ['deleted'], 'length' => []],
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
                'id' => '15cec625-8417-5533-bdb1-a17aec0bfcf4',
                'name' => 'Freelancer',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => '36563004-3f25-50c0-b22e-6554c3ccc4e7',
                'name' => 'Board',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => '3feba74f-47da-5146-9d8f-76c7266c60ea',
                'name' => 'Management',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => '428ed4cd-81b1-56af-aa7f-a7cbdbe227e4',
                'name' => 'Marketing',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => '469edf9d-ca1e-5003-91d6-3a46755d5a50',
                'name' => 'Accounting',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => '4cdc85b3-f442-5511-b28d-cbd109100189',
                'name' => 'Creative',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => '4ff007f6-80ec-5bf7-8f0a-46a17178db6f',
                'name' => 'Procurement',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => '516c2db6-0aed-52d8-854f-b3f3499995e7',
                'name' => 'Leadership team',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => '5fe7a6af-d97e-54f1-a4fc-b4b8bdb6e2ac',
                'name' => 'Traffic',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => 'a237ddd3-658f-57b8-a408-19a5cfbd51f3',
                'name' => 'deleted',
                'deleted' => true,
                'created' => '2016-02-02 18:59:05',
                'modified' => '2016-02-02 18:59:05',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => 'a89b771e-62ab-5434-b2fa-950827439ac7',
                'name' => 'Quality assurance',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => 'b7cbce9f-6a20-545b-b20a-fcf4092307df',
                'name' => 'Resource planning',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => 'b8b17d77-51e5-5c99-a0b6-86cf5757a781',
                'name' => 'IT support',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => 'c9c8fd8e-a0fa-53f0-967b-42edca3d91e4',
                'name' => 'Network',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => 'de5fcb71-6db2-5fe5-8803-7d3eb4d6ad9c',
                'name' => 'Human resource',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => 'eabe3a36-9bfb-5eba-b1aa-56eff5530006',
                'name' => 'Ergonom',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => 'f16c507f-9105-502e-aa8a-ba24c36dbdcf',
                'name' => 'Operations',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => 'faa73142-fb5e-5891-8b9f-4a00b3836fad',
                'name' => 'Sales',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
            [
                'id' => 'fc299a60-3ed9-5e54-8ba7-3de125660ae2',
                'name' => 'Developer',
                'deleted' => false,
                'created' => '2016-01-29 13:39:25',
                'modified' => '2016-01-29 13:39:25',
                'created_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'modified_by' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856'
            ],
        ];
        parent::init();
    }
}
