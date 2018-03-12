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
            'id' => '1f147b32-798b-4ce8-95f6-8fb1ca153e63',
            'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
            'model' => 'Avatar',
            'filename' => 'carol.png',
            'filesize' => 733439,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/87/47/23/1f147b32798b4ce895f68fb1ca153e63/',
            'adapter' => 'Local',
            'created' => '2018-03-05 09:59:37',
            'modified' => '2018-03-05 09:59:37'
        ],
        [
            'id' => '1f77dde7-54fb-47cd-8b9d-2bf87faec7dd',
            'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
            'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
            'model' => 'Avatar',
            'filename' => 'edith.png',
            'filesize' => 20462,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/36/34/89/1f77dde754fb47cd8b9d2bf87faec7dd/',
            'adapter' => 'Local',
            'created' => '2018-03-05 09:59:37',
            'modified' => '2018-03-05 09:59:37'
        ],
        [
            'id' => '600b72ce-575e-4c42-a719-69478684fc6e',
            'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
            'model' => 'Avatar',
            'filename' => 'betty.png',
            'filesize' => 115942,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/47/14/97/600b72ce575e4c42a71969478684fc6e/',
            'adapter' => 'Local',
            'created' => '2018-03-05 09:59:38',
            'modified' => '2018-03-05 09:59:38'
        ],
        [
            'id' => '68c7e62e-6d3f-4aba-a77d-7b34de8bd2af',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
            'model' => 'Avatar',
            'filename' => 'dame steve.png',
            'filesize' => 20676,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/04/28/69/68c7e62e6d3f4abaa77d7b34de8bd2af/',
            'adapter' => 'Local',
            'created' => '2018-03-05 09:59:37',
            'modified' => '2018-03-05 09:59:37'
        ],
        [
            'id' => '6f05f07c-05b0-43f5-8920-9abde364ba7d',
            'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
            'model' => 'Avatar',
            'filename' => 'frances.png',
            'filesize' => 283883,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/43/02/01/6f05f07c05b043f589209abde364ba7d/',
            'adapter' => 'Local',
            'created' => '2018-03-05 09:59:37',
            'modified' => '2018-03-05 09:59:37'
        ],
        [
            'id' => '9148158d-2bec-485d-921e-cd1e8c6af722',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
            'model' => 'Avatar',
            'filename' => 'ada.png',
            'filesize' => 170049,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/05/03/21/9148158d2bec485d921ecd1e8c6af722/',
            'adapter' => 'Local',
            'created' => '2018-03-05 09:59:37',
            'modified' => '2018-03-05 09:59:37'
        ],
        [
            'id' => 'b5314a83-6d96-41de-8cf5-f8b98e9ad3b2',
            'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
            'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
            'model' => 'Avatar',
            'filename' => 'kathleen.png',
            'filesize' => 53376,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/39/19/71/b5314a836d9641de8cf5f8b98e9ad3b2/',
            'adapter' => 'Local',
            'created' => '2018-03-05 09:59:37',
            'modified' => '2018-03-05 09:59:37'
        ],
    ];
}
