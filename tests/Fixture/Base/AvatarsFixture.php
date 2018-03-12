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
        'user_id' => ['type' => 'string', 'length' => 36, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'foreign_key' => ['type' => 'string', 'length' => 36, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
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
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => '19f30308-3a31-4354-94c0-ef7aaed04d3e',
            'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
            'model' => 'Avatar',
            'filename' => 'frances.png',
            'filesize' => 283883,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/12/77/16/19f303083a31435494c0ef7aaed04d3e/',
            'adapter' => 'Local',
            'created' => '2018-03-12 13:45:56',
            'modified' => '2018-03-12 13:45:56'
        ],
        [
            'id' => '3770dd59-4efa-4de3-9170-212c1ec6ca7d',
            'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
            'model' => 'Avatar',
            'filename' => 'carol.png',
            'filesize' => 733439,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/31/93/55/3770dd594efa4de39170212c1ec6ca7d/',
            'adapter' => 'Local',
            'created' => '2018-03-12 13:45:56',
            'modified' => '2018-03-12 13:45:56'
        ],
        [
            'id' => '541899d0-2b29-4ab8-90f4-e077eee4dfaa',
            'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
            'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
            'model' => 'Avatar',
            'filename' => 'edith.png',
            'filesize' => 20462,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/60/87/13/541899d02b294ab890f4e077eee4dfaa/',
            'adapter' => 'Local',
            'created' => '2018-03-12 13:45:56',
            'modified' => '2018-03-12 13:45:56'
        ],
        [
            'id' => '77e7bf1b-46c1-4ade-8e0b-dddcd75b9579',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
            'model' => 'Avatar',
            'filename' => 'ada.png',
            'filesize' => 170049,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/33/14/19/77e7bf1b46c14ade8e0bdddcd75b9579/',
            'adapter' => 'Local',
            'created' => '2018-03-12 13:45:56',
            'modified' => '2018-03-12 13:45:56'
        ],
        [
            'id' => '93f940b9-21a0-42b0-a806-b4cb2c70140c',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
            'model' => 'Avatar',
            'filename' => 'dame steve.png',
            'filesize' => 20676,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/02/38/83/93f940b921a042b0a806b4cb2c70140c/',
            'adapter' => 'Local',
            'created' => '2018-03-12 13:45:56',
            'modified' => '2018-03-12 13:45:56'
        ],
        [
            'id' => 'bcd666f5-b682-41f6-bb57-a6aebd2bd9cf',
            'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
            'model' => 'Avatar',
            'filename' => 'betty.png',
            'filesize' => 115942,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/56/95/84/bcd666f5b68241f6bb57a6aebd2bd9cf/',
            'adapter' => 'Local',
            'created' => '2018-03-12 13:45:56',
            'modified' => '2018-03-12 13:45:56'
        ],
        [
            'id' => 'bf10a126-daf6-4272-86a7-083cc851bb15',
            'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
            'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
            'model' => 'Avatar',
            'filename' => 'kathleen.png',
            'filesize' => 53376,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/50/17/19/bf10a126daf6427286a7083cc851bb15/',
            'adapter' => 'Local',
            'created' => '2018-03-12 13:45:56',
            'modified' => '2018-03-12 13:45:56'
        ],
    ];
}
