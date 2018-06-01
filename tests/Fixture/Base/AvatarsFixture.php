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
                'id' => '48dc4953-bf07-4320-a6b7-51726580f97e',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
                'model' => 'Avatar',
                'filename' => 'ada.png',
                'filesize' => 170049,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/68/45/49/48dc4953bf074320a6b751726580f97e/',
                'adapter' => 'Local',
                'created' => '2018-05-01 05:25:34',
                'modified' => '2018-05-01 05:25:34'
            ],
            [
                'id' => '95e7282c-95e9-4d53-a8dc-5fe57caaed3b',
                'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
                'model' => 'Avatar',
                'filename' => 'edith.png',
                'filesize' => 20462,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/34/29/12/95e7282c95e94d53a8dc5fe57caaed3b/',
                'adapter' => 'Local',
                'created' => '2018-05-01 05:25:34',
                'modified' => '2018-05-01 05:25:34'
            ],
            [
                'id' => 'a891a76e-4ccc-4868-83d4-c4e5dc3cdafa',
                'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
                'model' => 'Avatar',
                'filename' => 'dame steve.png',
                'filesize' => 20676,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/97/72/01/a891a76e4ccc486883d4c4e5dc3cdafa/',
                'adapter' => 'Local',
                'created' => '2018-05-01 05:25:34',
                'modified' => '2018-05-01 05:25:34'
            ],
            [
                'id' => 'cea979b2-c592-4592-bd31-675ed157c443',
                'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
                'model' => 'Avatar',
                'filename' => 'carol.png',
                'filesize' => 733439,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/89/58/73/cea979b2c5924592bd31675ed157c443/',
                'adapter' => 'Local',
                'created' => '2018-05-01 05:25:34',
                'modified' => '2018-05-01 05:25:34'
            ],
            [
                'id' => 'd945702c-fd29-4142-a4e8-c73725e81e62',
                'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
                'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
                'model' => 'Avatar',
                'filename' => 'frances.png',
                'filesize' => 283883,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/01/94/31/d945702cfd294142a4e8c73725e81e62/',
                'adapter' => 'Local',
                'created' => '2018-05-01 05:25:34',
                'modified' => '2018-05-01 05:25:34'
            ],
            [
                'id' => 'fbfde8ce-df4f-48a1-b774-da9f6919af30',
                'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
                'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
                'model' => 'Avatar',
                'filename' => 'kathleen.png',
                'filesize' => 53376,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/40/36/20/fbfde8cedf4f48a1b774da9f6919af30/',
                'adapter' => 'Local',
                'created' => '2018-05-01 05:25:34',
                'modified' => '2018-05-01 05:25:34'
            ],
            [
                'id' => 'ff381798-f2c3-4362-8fb5-6e0418c545bb',
                'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
                'model' => 'Avatar',
                'filename' => 'betty.png',
                'filesize' => 115942,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => null,
                'path' => 'images/Avatar/49/38/28/ff381798f2c343628fb56e0418c545bb/',
                'adapter' => 'Local',
                'created' => '2018-05-01 05:25:34',
                'modified' => '2018-05-01 05:25:34'
            ],
        ];
        parent::init();
    }
}
