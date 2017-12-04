<?php
namespace App\Test\Fixture;

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
            'id' => '1cfa200d-43b2-488e-ac52-c4e8052ea7ef',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
            'model' => 'Avatar',
            'filename' => 'ada.png',
            'filesize' => 170049,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/47/31/95/1cfa200d43b2488eac52c4e8052ea7ef/',
            'adapter' => 'Local',
            'created' => '2017-11-28 16:12:07',
            'modified' => '2017-11-28 16:12:07'
        ],
        [
            'id' => '219f7770-1781-44f1-8144-33c220fb8469',
            'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
            'model' => 'Avatar',
            'filename' => 'betty.png',
            'filesize' => 115942,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/08/21/33/219f7770178144f1814433c220fb8469/',
            'adapter' => 'Local',
            'created' => '2017-11-28 16:12:07',
            'modified' => '2017-11-28 16:12:07'
        ],
        [
            'id' => '479f48fd-ca12-47a0-9668-b1a1b7b71eab',
            'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
            'model' => 'Avatar',
            'filename' => 'frances.png',
            'filesize' => 283883,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/70/67/36/479f48fdca1247a09668b1a1b7b71eab/',
            'adapter' => 'Local',
            'created' => '2017-11-28 16:12:06',
            'modified' => '2017-11-28 16:12:06'
        ],
        [
            'id' => '4fcb2ee9-770f-46bf-843d-191806213d21',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
            'model' => 'Avatar',
            'filename' => 'dame steve.png',
            'filesize' => 20676,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/11/52/13/4fcb2ee9770f46bf843d191806213d21/',
            'adapter' => 'Local',
            'created' => '2017-11-28 16:12:06',
            'modified' => '2017-11-28 16:12:06'
        ],
        [
            'id' => 'bca7d212-8055-4897-8c12-251fca8e3a92',
            'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
            'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
            'model' => 'Avatar',
            'filename' => 'edith.png',
            'filesize' => 20462,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/92/57/02/bca7d212805548978c12251fca8e3a92/',
            'adapter' => 'Local',
            'created' => '2017-11-28 16:12:06',
            'modified' => '2017-11-28 16:12:06'
        ],
        [
            'id' => 'd171fdab-bb6c-479f-a26f-5ed9b59fdf4b',
            'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
            'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
            'model' => 'Avatar',
            'filename' => 'kathleen.png',
            'filesize' => 53376,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/08/58/33/d171fdabbb6c479fa26f5ed9b59fdf4b/',
            'adapter' => 'Local',
            'created' => '2017-11-28 16:12:06',
            'modified' => '2017-11-28 16:12:06'
        ],
        [
            'id' => 'eec27556-f59b-452f-92c5-1d27ebe4e830',
            'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
            'model' => 'Avatar',
            'filename' => 'carol.png',
            'filesize' => 733439,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/79/57/16/eec27556f59b452f92c51d27ebe4e830/',
            'adapter' => 'Local',
            'created' => '2017-11-28 16:12:06',
            'modified' => '2017-11-28 16:12:06'
        ],
    ];
}
