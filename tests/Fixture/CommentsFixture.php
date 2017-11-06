<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CommentsFixture
 *
 */
class CommentsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'parent_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'foreign_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'foreign_model' => ['type' => 'string', 'length' => 36, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'content' => ['type' => 'string', 'length' => 256, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
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
            'id' => '0ee762f9-71dd-3163-a9a1-345b44c90b28',
            'parent_id' => '',
            'foreign_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
            'foreign_model' => 'Resource',
            'content' => 'this is a short comment',
            'created' => '2012-11-25 13:39:25',
            'modified' => '2012-11-25 13:39:25',
            'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
            'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
        ],
        [
            'id' => '5bc93a6a-9bd7-338e-ad2a-d8d112281670',
            'parent_id' => '0ee762f9-71dd-3163-a9a1-345b44c90b28',
            'foreign_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
            'foreign_model' => 'Resource',
            'content' => 'this is a reply to the short comment',
            'created' => '2012-11-25 13:39:26',
            'modified' => '2012-11-25 13:39:26',
            'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
            'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
        ],
    ];
}
