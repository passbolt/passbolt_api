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
            'id' => '05f10bc4-e492-4af2-987c-9c535841a531',
            'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
            'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
            'model' => 'Avatar',
            'filename' => 'carol.png',
            'filesize' => 733439,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/00/94/85/05f10bc4e4924af2987c9c535841a531/',
            'adapter' => 'Local',
            'created' => '2018-01-03 03:32:49',
            'modified' => '2018-01-03 03:32:49'
        ],
        [
            'id' => '0f6c623b-31bd-413f-868a-7853584e06b3',
            'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
            'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
            'model' => 'Avatar',
            'filename' => 'ada.png',
            'filesize' => 170049,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/14/76/07/0f6c623b31bd413f868a7853584e06b3/',
            'adapter' => 'Local',
            'created' => '2018-01-03 03:32:50',
            'modified' => '2018-01-03 03:32:50'
        ],
        [
            'id' => '1008d9c5-342a-4d04-976a-10918e73841a',
            'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
            'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
            'model' => 'Avatar',
            'filename' => 'frances.png',
            'filesize' => 283883,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/10/46/75/1008d9c5342a4d04976a10918e73841a/',
            'adapter' => 'Local',
            'created' => '2018-01-03 03:32:49',
            'modified' => '2018-01-03 03:32:49'
        ],
        [
            'id' => '2931a815-3520-4381-ac8d-879ca76ee6ee',
            'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
            'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
            'model' => 'Avatar',
            'filename' => 'betty.png',
            'filesize' => 115942,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/46/02/13/2931a81535204381ac8d879ca76ee6ee/',
            'adapter' => 'Local',
            'created' => '2018-01-03 03:32:50',
            'modified' => '2018-01-03 03:32:50'
        ],
        [
            'id' => 'bd776af0-37a4-4ba4-8f23-ccfb8883bbd6',
            'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
            'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
            'model' => 'Avatar',
            'filename' => 'dame steve.png',
            'filesize' => 20676,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/90/89/57/bd776af037a44ba48f23ccfb8883bbd6/',
            'adapter' => 'Local',
            'created' => '2018-01-03 03:32:49',
            'modified' => '2018-01-03 03:32:49'
        ],
        [
            'id' => 'd5b9f362-86f4-45d3-a9a6-ab16c3108944',
            'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
            'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
            'model' => 'Avatar',
            'filename' => 'edith.png',
            'filesize' => 20462,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/96/64/43/d5b9f36286f445d3a9a6ab16c3108944/',
            'adapter' => 'Local',
            'created' => '2018-01-03 03:32:49',
            'modified' => '2018-01-03 03:32:49'
        ],
        [
            'id' => 'f7afcbf8-ddbf-456b-97b2-5adaa1d774de',
            'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
            'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
            'model' => 'Avatar',
            'filename' => 'kathleen.png',
            'filesize' => 53376,
            'mime_type' => '',
            'extension' => 'png',
            'hash' => null,
            'path' => 'images/Avatar/15/50/61/f7afcbf8ddbf456b97b25adaa1d774de/',
            'adapter' => 'Local',
            'created' => '2018-01-03 03:32:50',
            'modified' => '2018-01-03 03:32:50'
        ],
    ];
}
