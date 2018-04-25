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
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => '02c0e1c5-a48e-43d4-9343-cd0b1f29f13c',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
            'model' => 'Avatar',
            'filename' => 'dame steve.png',
            'filesize' => 20676,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/75/93/30/02c0e1c5a48e43d49343cd0b1f29f13c/',
            'adapter' => 'Local',
            'created' => '2018-04-24 05:24:24',
            'modified' => '2018-04-24 05:24:24'
        ],
        [
            'id' => '12ef13f5-3b0c-4969-aa83-eb5c2b2e55f3',
            'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
            'model' => 'Avatar',
            'filename' => 'carol.png',
            'filesize' => 733439,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/17/53/57/12ef13f53b0c4969aa83eb5c2b2e55f3/',
            'adapter' => 'Local',
            'created' => '2018-04-24 05:24:24',
            'modified' => '2018-04-24 05:24:24'
        ],
        [
            'id' => '2fff1b40-0876-4983-894d-b39bfa4e44e2',
            'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
            'model' => 'Avatar',
            'filename' => 'frances.png',
            'filesize' => 283883,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/57/50/76/2fff1b4008764983894db39bfa4e44e2/',
            'adapter' => 'Local',
            'created' => '2018-04-24 05:24:24',
            'modified' => '2018-04-24 05:24:24'
        ],
        [
            'id' => '5b81a98c-41bd-41f0-84dc-2e55ed78d7c9',
            'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
            'model' => 'Avatar',
            'filename' => 'betty.png',
            'filesize' => 115942,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/83/97/28/5b81a98c41bd41f084dc2e55ed78d7c9/',
            'adapter' => 'Local',
            'created' => '2018-04-24 05:24:24',
            'modified' => '2018-04-24 05:24:24'
        ],
        [
            'id' => '97fa1baf-3c55-4115-9157-557654760d75',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
            'model' => 'Avatar',
            'filename' => 'ada.png',
            'filesize' => 170049,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/23/06/49/97fa1baf3c5541159157557654760d75/',
            'adapter' => 'Local',
            'created' => '2018-04-24 05:24:24',
            'modified' => '2018-04-24 05:24:24'
        ],
        [
            'id' => 'c622232d-15d0-483d-82a0-ce478d49f2b8',
            'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
            'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
            'model' => 'Avatar',
            'filename' => 'kathleen.png',
            'filesize' => 53376,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/42/63/62/c622232d15d0483d82a0ce478d49f2b8/',
            'adapter' => 'Local',
            'created' => '2018-04-24 05:24:24',
            'modified' => '2018-04-24 05:24:24'
        ],
        [
            'id' => 'f0c42f99-39bc-4597-9f97-455acb140e14',
            'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
            'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
            'model' => 'Avatar',
            'filename' => 'edith.png',
            'filesize' => 20462,
            'mime_type' => 'image/png',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/03/23/64/f0c42f9939bc45979f97455acb140e14/',
            'adapter' => 'Local',
            'created' => '2018-04-24 05:24:23',
            'modified' => '2018-04-24 05:24:23'
        ],
    ];
}
