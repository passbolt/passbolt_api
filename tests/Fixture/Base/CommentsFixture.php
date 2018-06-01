<?php
namespace App\Test\Fixture\Base;

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
        'parent_id' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'foreign_key' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'foreign_model' => ['type' => 'string', 'length' => 36, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'content' => ['type' => 'string', 'length' => 256, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
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
                'id' => '1dd295f0-4d4e-51b3-b0ca-b0a9763b560c',
                'parent_id' => 'da213c84-3d61-596e-882b-f870c26bd0f5',
                'foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
                'foreign_model' => 'Resource',
                'content' => 'this is a reply to the short comment',
                'created' => '2012-11-25 13:39:26',
                'modified' => '2012-11-25 13:39:26',
                'created_by' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'modified_by' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'user_id' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498'
            ],
            [
                'id' => 'da213c84-3d61-596e-882b-f870c26bd0f5',
                'parent_id' => null,
                'foreign_key' => '8e3874ae-4b40-590b-968a-418f704b9d9a',
                'foreign_model' => 'Resource',
                'content' => 'this is a short comment',
                'created' => '2012-11-25 13:39:25',
                'modified' => '2012-11-25 13:39:25',
                'created_by' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'modified_by' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'user_id' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498'
            ],
        ];
        parent::init();
    }
}
