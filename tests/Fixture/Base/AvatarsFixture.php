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
            'id' => '0f6abec0-ed8e-4beb-8177-0c7f11a7c6b5',
            'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
            'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
            'model' => 'Avatar',
            'filename' => 'edith.png',
            'filesize' => 20462,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/83/80/79/0f6abec0ed8e4beb81770c7f11a7c6b5/',
            'adapter' => 'Local',
            'created' => '2018-02-23 08:37:16',
            'modified' => '2018-02-23 08:37:16'
        ],
        [
            'id' => '4d0b4b4b-9bda-43c2-9592-04e42f3234a1',
            'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
            'model' => 'Avatar',
            'filename' => 'betty.png',
            'filesize' => 115942,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/35/68/11/4d0b4b4b9bda43c2959204e42f3234a1/',
            'adapter' => 'Local',
            'created' => '2018-02-23 08:37:16',
            'modified' => '2018-02-23 08:37:16'
        ],
        [
            'id' => '656a09f6-8809-4ac8-9380-a8a33f8b0465',
            'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
            'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
            'model' => 'Avatar',
            'filename' => 'kathleen.png',
            'filesize' => 53376,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/53/04/13/656a09f688094ac89380a8a33f8b0465/',
            'adapter' => 'Local',
            'created' => '2018-02-23 08:37:16',
            'modified' => '2018-02-23 08:37:16'
        ],
        [
            'id' => '7cc4156c-d98f-4e93-ae8c-a0d336eaac56',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
            'model' => 'Avatar',
            'filename' => 'dame steve.png',
            'filesize' => 20676,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/69/36/30/7cc4156cd98f4e93ae8ca0d336eaac56/',
            'adapter' => 'Local',
            'created' => '2018-02-23 08:37:16',
            'modified' => '2018-02-23 08:37:16'
        ],
        [
            'id' => '85bf1f16-992a-4463-85c4-98c7a76e78a7',
            'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
            'model' => 'Avatar',
            'filename' => 'frances.png',
            'filesize' => 283883,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/69/94/16/85bf1f16992a446385c498c7a76e78a7/',
            'adapter' => 'Local',
            'created' => '2018-02-23 08:37:16',
            'modified' => '2018-02-23 08:37:16'
        ],
        [
            'id' => 'b954597a-d661-4787-842d-87b6720c4e0a',
            'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
            'model' => 'Avatar',
            'filename' => 'carol.png',
            'filesize' => 733439,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/42/74/14/b954597ad6614787842d87b6720c4e0a/',
            'adapter' => 'Local',
            'created' => '2018-02-23 08:37:16',
            'modified' => '2018-02-23 08:37:16'
        ],
        [
            'id' => 'bc2e3a71-7180-41c0-9f7e-46e67fc86e18',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
            'model' => 'Avatar',
            'filename' => 'ada.png',
            'filesize' => 170049,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/74/19/94/bc2e3a71718041c09f7e46e67fc86e18/',
            'adapter' => 'Local',
            'created' => '2018-02-23 08:37:16',
            'modified' => '2018-02-23 08:37:16'
        ],
    ];
}
