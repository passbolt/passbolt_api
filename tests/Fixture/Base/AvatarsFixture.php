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
            'id' => '623f7ae8-7754-4729-a483-59fe27577437',
            'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
            'model' => 'Avatar',
            'filename' => 'carol.png',
            'filesize' => 733439,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/31/48/32/623f7ae877544729a48359fe27577437/',
            'adapter' => 'Local',
            'created' => '2018-01-06 21:50:02',
            'modified' => '2018-01-06 21:50:02'
        ],
        [
            'id' => '84080f4b-5643-40a3-b52a-453e719a0c6d',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
            'model' => 'Avatar',
            'filename' => 'ada.png',
            'filesize' => 170049,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/98/06/74/84080f4b564340a3b52a453e719a0c6d/',
            'adapter' => 'Local',
            'created' => '2018-01-06 21:50:03',
            'modified' => '2018-01-06 21:50:03'
        ],
        [
            'id' => '881789cc-8afe-4041-ba54-f4a3d8afbbbe',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
            'model' => 'Avatar',
            'filename' => 'dame steve.png',
            'filesize' => 20676,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/50/34/33/881789cc8afe4041ba54f4a3d8afbbbe/',
            'adapter' => 'Local',
            'created' => '2018-01-06 21:50:02',
            'modified' => '2018-01-06 21:50:02'
        ],
        [
            'id' => '9e9c3888-8447-4f28-a466-8c57436a7331',
            'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
            'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
            'model' => 'Avatar',
            'filename' => 'edith.png',
            'filesize' => 20462,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/75/03/19/9e9c388884474f28a4668c57436a7331/',
            'adapter' => 'Local',
            'created' => '2018-01-06 21:50:02',
            'modified' => '2018-01-06 21:50:02'
        ],
        [
            'id' => 'b8ef724d-507b-4251-93bf-d2a8aafa7624',
            'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
            'model' => 'Avatar',
            'filename' => 'betty.png',
            'filesize' => 115942,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/18/04/00/b8ef724d507b425193bfd2a8aafa7624/',
            'adapter' => 'Local',
            'created' => '2018-01-06 21:50:03',
            'modified' => '2018-01-06 21:50:03'
        ],
        [
            'id' => 'd9fe298a-0e79-46da-82bb-7d40bf0f1ddf',
            'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
            'model' => 'Avatar',
            'filename' => 'frances.png',
            'filesize' => 283883,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/97/14/59/d9fe298a0e7946da82bb7d40bf0f1ddf/',
            'adapter' => 'Local',
            'created' => '2018-01-06 21:50:02',
            'modified' => '2018-01-06 21:50:02'
        ],
        [
            'id' => 'e5375a1d-7ae7-4d90-9aba-777cc1950405',
            'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
            'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
            'model' => 'Avatar',
            'filename' => 'kathleen.png',
            'filesize' => 53376,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/43/83/18/e5375a1d7ae74d909aba777cc1950405/',
            'adapter' => 'Local',
            'created' => '2018-01-06 21:50:03',
            'modified' => '2018-01-06 21:50:03'
        ],
    ];
}
