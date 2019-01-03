<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AvatarsFixture
 *
 */
class AvatarsFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'file_storage';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'foreign_key' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'model' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'filename' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'filesize' => ['type' => 'integer', 'length' => 16, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'mime_type' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'extension' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'hash' => ['type' => 'string', 'length' => 64, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'path' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'adapter' => ['type' => 'string', 'length' => 32, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => 'Gaufrette Storage Adapter Class', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
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
                'id' => '13f79d9f-4804-403d-824f-e9642abcdd16',
                'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
                'model' => 'Avatar',
                'filename' => 'betty.png',
                'filesize' => 115942,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/25/31/14/13f79d9f4804403d824fe9642abcdd16/',
                'adapter' => 'Local',
                'created' => '2019-01-03 05:01:56',
                'modified' => '2019-01-03 05:01:56'
            ],
            [
                'id' => '3267e701-4dae-4d03-814a-fa10176011b0',
                'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
                'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
                'model' => 'Avatar',
                'filename' => 'frances.png',
                'filesize' => 283883,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/97/41/51/3267e7014dae4d03814afa10176011b0/',
                'adapter' => 'Local',
                'created' => '2019-01-03 05:01:56',
                'modified' => '2019-01-03 05:01:56'
            ],
            [
                'id' => '32f7dfb1-e03e-4116-b929-1f39e90e2419',
                'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
                'model' => 'Avatar',
                'filename' => 'carol.png',
                'filesize' => 733439,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/63/42/81/32f7dfb1e03e4116b9291f39e90e2419/',
                'adapter' => 'Local',
                'created' => '2019-01-03 05:01:56',
                'modified' => '2019-01-03 05:01:56'
            ],
            [
                'id' => '916726ca-fa15-4971-8d93-a29a4896d8d7',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
                'model' => 'Avatar',
                'filename' => 'ada.png',
                'filesize' => 170049,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/05/56/98/916726cafa1549718d93a29a4896d8d7/',
                'adapter' => 'Local',
                'created' => '2019-01-03 05:01:56',
                'modified' => '2019-01-03 05:01:56'
            ],
            [
                'id' => '917dfd8a-b560-4f8e-8825-02d9ca140a17',
                'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
                'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
                'model' => 'Avatar',
                'filename' => 'kathleen.png',
                'filesize' => 53376,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/87/83/34/917dfd8ab5604f8e882502d9ca140a17/',
                'adapter' => 'Local',
                'created' => '2019-01-03 05:01:56',
                'modified' => '2019-01-03 05:01:56'
            ],
            [
                'id' => 'b39e2ada-ea06-440b-a978-d3618756bcb4',
                'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
                'model' => 'Avatar',
                'filename' => 'edith.png',
                'filesize' => 20462,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/59/21/85/b39e2adaea06440ba978d3618756bcb4/',
                'adapter' => 'Local',
                'created' => '2019-01-03 05:01:55',
                'modified' => '2019-01-03 05:01:55'
            ],
            [
                'id' => 'c1545813-88f8-440e-abfa-903e5ebafef1',
                'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
                'model' => 'Avatar',
                'filename' => 'dame steve.png',
                'filesize' => 20676,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/27/88/37/c154581388f8440eabfa903e5ebafef1/',
                'adapter' => 'Local',
                'created' => '2019-01-03 05:01:56',
                'modified' => '2019-01-03 05:01:56'
            ],
        ];
        parent::init();
    }
}
