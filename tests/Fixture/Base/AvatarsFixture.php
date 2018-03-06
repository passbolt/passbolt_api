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
            'id' => '1bc3afe5-dae0-45fa-950e-6c0605f04317',
            'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
            'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
            'model' => 'Avatar',
            'filename' => 'kathleen.png',
            'filesize' => 53376,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/32/51/74/1bc3afe5dae045fa950e6c0605f04317/',
            'adapter' => 'Local',
            'created' => '2018-02-27 12:01:42',
            'modified' => '2018-02-27 12:01:42'
        ],
        [
            'id' => '3b9b4a53-af66-43f1-945b-e87a7a0d9943',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
            'model' => 'Avatar',
            'filename' => 'dame steve.png',
            'filesize' => 20676,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/11/03/70/3b9b4a53af6643f1945be87a7a0d9943/',
            'adapter' => 'Local',
            'created' => '2018-02-27 12:01:41',
            'modified' => '2018-02-27 12:01:41'
        ],
        [
            'id' => '8811f56f-a185-4cda-b525-e3863da4dcff',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
            'model' => 'Avatar',
            'filename' => 'ada.png',
            'filesize' => 170049,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/17/94/88/8811f56fa1854cdab525e3863da4dcff/',
            'adapter' => 'Local',
            'created' => '2018-02-27 12:01:42',
            'modified' => '2018-02-27 12:01:42'
        ],
        [
            'id' => 'b6412d6b-1c3a-491b-99e0-99fad15f5ffb',
            'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
            'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
            'model' => 'Avatar',
            'filename' => 'edith.png',
            'filesize' => 20462,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/65/17/24/b6412d6b1c3a491b99e099fad15f5ffb/',
            'adapter' => 'Local',
            'created' => '2018-02-27 12:01:41',
            'modified' => '2018-02-27 12:01:41'
        ],
        [
            'id' => 'c05749c6-f7bf-4fbd-a30d-b31acc17b88f',
            'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
            'model' => 'Avatar',
            'filename' => 'carol.png',
            'filesize' => 733439,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/95/57/76/c05749c6f7bf4fbda30db31acc17b88f/',
            'adapter' => 'Local',
            'created' => '2018-02-27 12:01:41',
            'modified' => '2018-02-27 12:01:41'
        ],
        [
            'id' => 'd3bfefb3-570c-448d-8ed0-6ed2a004497a',
            'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
            'model' => 'Avatar',
            'filename' => 'betty.png',
            'filesize' => 115942,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/07/43/66/d3bfefb3570c448d8ed06ed2a004497a/',
            'adapter' => 'Local',
            'created' => '2018-02-27 12:01:42',
            'modified' => '2018-02-27 12:01:42'
        ],
        [
            'id' => 'e382dc08-0410-462f-9a5f-9b2513169974',
            'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
            'model' => 'Avatar',
            'filename' => 'frances.png',
            'filesize' => 283883,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/86/75/81/e382dc080410462f9a5f9b2513169974/',
            'adapter' => 'Local',
            'created' => '2018-02-27 12:01:42',
            'modified' => '2018-02-27 12:01:42'
        ],
    ];
}
