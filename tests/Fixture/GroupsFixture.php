<?php
namespace App\Test\Fixture;

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
        'name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
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
            'id' => '012568d6-9300-385b-a22a-e27d191764eb',
            'name' => 'Sales',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '0241e630-b161-3a3d-a6f7-f6d8e3cea3c8',
            'name' => 'IT support',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '14153251-abcb-3c00-a2d1-b4fdb1423d26',
            'name' => 'Management',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '1ec15696-e564-3e60-ad76-d73415b73afd',
            'name' => 'Human resource',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '24537609-6db5-31bb-af0d-f7f0494dd184',
            'name' => 'Creative',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '53074209-fd29-3c8e-abaf-e017497f43cf',
            'name' => 'Operations',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '563a4b8f-e7ac-31ff-a3e8-f1b6d3ff222a',
            'name' => 'Accounting',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '5a1e3498-35a0-32dc-ac2e-80dbd85c9017',
            'name' => 'Leadership team',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '5deebe9f-8e83-354c-a035-4e79353a0957',
            'name' => 'Developer',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '640d1f30-0197-3276-a87e-a1ef389ee5fb',
            'name' => 'Quality assurance',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '73c705f1-919d-3916-a5b7-990b3a517d14',
            'name' => 'Traffic',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '73dba62a-d402-3cbf-a036-d07b5dac5255',
            'name' => 'deleted',
            'deleted' => true,
            'created' => '2016-02-02 18:59:05',
            'modified' => '2016-02-02 18:59:05',
            'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
            'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
        ],
        [
            'id' => '7fb692ab-2631-35bb-ab34-7f69b2e7f0a2',
            'name' => 'Freelancer',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => '981777cc-aa3a-3b5b-ac1d-86e281c37982',
            'name' => 'Ergonom',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => 'd6b15a4e-4fdf-3026-ac49-5a8de3fc49a0',
            'name' => 'Board',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => 'da776e4a-73b7-3a58-a047-f31614bd15bb',
            'name' => 'Marketing',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => 'e6d598a1-e050-3237-a4d6-2ba75d65dc3b',
            'name' => 'Resource planning',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => 'ecc19da3-69b8-3c0d-a03a-2b29bfb7a610',
            'name' => 'Procurement',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
        [
            'id' => 'fea0c76e-046c-33ab-ab27-809ce35c0cdb',
            'name' => 'Network',
            'deleted' => false,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
            'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
        ],
    ];
}
