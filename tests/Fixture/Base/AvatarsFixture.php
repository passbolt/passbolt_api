<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AvatarsFixture
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
        'extension' => ['type' => 'string', 'length' => 32, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
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
                'id' => '06f3518a-8e7f-419a-b135-7bb00f02f498',
                'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
                'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
                'model' => 'Avatar',
                'filename' => 'kathleen.png',
                'filesize' => 53376,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '95474e9309c7c7f14fbe5c3b0f943bd145c0a366',
                'path' => 'Avatar/ce/60/76/06f3518a8e7f419ab1357bb00f02f498/06f3518a8e7f419ab1357bb00f02f498.png',
                'adapter' => 'Local',
                'created' => '2019-07-02 18:51:43',
                'modified' => '2019-07-02 18:51:43'
            ],
            [
                'id' => '2f96c11e-f226-4e5e-80b4-0daa6f6fe2dd',
                'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
                'model' => 'Avatar',
                'filename' => 'carol.png',
                'filesize' => 733439,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '7445a736df60a1ac1bfdab8fc5b842a95c495aec',
                'path' => 'Avatar/9c/eb/10/2f96c11ef2264e5e80b40daa6f6fe2dd/2f96c11ef2264e5e80b40daa6f6fe2dd.png',
                'adapter' => 'Local',
                'created' => '2019-07-02 18:51:42',
                'modified' => '2019-07-02 18:51:42'
            ],
            [
                'id' => '66ff4a4a-0704-4fdf-a61e-491dcb56b1f7',
                'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
                'model' => 'Avatar',
                'filename' => 'dame steve.png',
                'filesize' => 20676,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => 'f2695972b9009970ac85aae95f907693268cd249',
                'path' => 'Avatar/b9/2a/40/66ff4a4a07044fdfa61e491dcb56b1f7/66ff4a4a07044fdfa61e491dcb56b1f7.png',
                'adapter' => 'Local',
                'created' => '2019-07-02 18:51:42',
                'modified' => '2019-07-02 18:51:42'
            ],
            [
                'id' => '7b80ddf8-14e7-4064-bbac-bbd7e5f52875',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
                'model' => 'Avatar',
                'filename' => 'ada.png',
                'filesize' => 170049,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '97e36ab6528e26e3b9f988444ef490f125f49a39',
                'path' => 'Avatar/0a/ae/40/7b80ddf814e74064bbacbbd7e5f52875/7b80ddf814e74064bbacbbd7e5f52875.png',
                'adapter' => 'Local',
                'created' => '2019-07-02 18:51:43',
                'modified' => '2019-07-02 18:51:43'
            ],
            [
                'id' => '86843607-a68f-4983-9f3d-4158f46bcf84',
                'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
                'model' => 'Avatar',
                'filename' => 'edith.png',
                'filesize' => 20462,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '6a508422b1765eaa13c28f4611340414622f9cf9',
                'path' => 'Avatar/62/6a/34/86843607a68f49839f3d4158f46bcf84/86843607a68f49839f3d4158f46bcf84.png',
                'adapter' => 'Local',
                'created' => '2019-07-02 18:51:42',
                'modified' => '2019-07-02 18:51:42'
            ],
            [
                'id' => 'bd4fbd32-e123-4643-aaf8-00e2b39c308d',
                'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
                'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
                'model' => 'Avatar',
                'filename' => 'frances.png',
                'filesize' => 283883,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '95af1b264a94de0b75af95e75030832245afc8bf',
                'path' => 'Avatar/ba/00/3f/bd4fbd32e1234643aaf800e2b39c308d/bd4fbd32e1234643aaf800e2b39c308d.png',
                'adapter' => 'Local',
                'created' => '2019-07-02 18:51:42',
                'modified' => '2019-07-02 18:51:42'
            ],
            [
                'id' => 'ed6e67c4-5e94-4a8d-b7b2-0f7864cad21e',
                'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
                'model' => 'Avatar',
                'filename' => 'betty.png',
                'filesize' => 115942,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '820a0cb765217a0e765f3a0abbb2e98b62ddecc1',
                'path' => 'Avatar/39/1a/94/ed6e67c45e944a8db7b20f7864cad21e/ed6e67c45e944a8db7b20f7864cad21e.png',
                'adapter' => 'Local',
                'created' => '2019-07-02 18:51:43',
                'modified' => '2019-07-02 18:51:43'
            ],
        ];
        parent::init();
    }
}
