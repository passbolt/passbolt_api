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
                'id' => '00e7690b-1857-4594-97bd-89063bb8bfe1',
                'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'foreign_key' => 'cbce5d22-46c1-51d1-b851-36b174e40611',
                'model' => 'Avatar',
                'filename' => 'betty.png',
                'filesize' => 115942,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '820a0cb765217a0e765f3a0abbb2e98b62ddecc1',
                'path' => 'Avatar/2d/34/f2/00e7690b1857459497bd89063bb8bfe1/00e7690b1857459497bd89063bb8bfe1.png',
                'adapter' => 'Local',
                'created' => '2019-03-08 12:57:34',
                'modified' => '2019-03-08 12:57:34'
            ],
            [
                'id' => '08b86d9c-588f-4724-a919-f53b35e47892',
                'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'foreign_key' => '2766ff6b-87f1-53a9-98fd-72cd32a3df69',
                'model' => 'Avatar',
                'filename' => 'dame steve.png',
                'filesize' => 20676,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => 'f2695972b9009970ac85aae95f907693268cd249',
                'path' => 'Avatar/39/60/5e/08b86d9c588f4724a919f53b35e47892/08b86d9c588f4724a919f53b35e47892.png',
                'adapter' => 'Local',
                'created' => '2019-03-08 12:57:33',
                'modified' => '2019-03-08 12:57:33'
            ],
            [
                'id' => '63dd68f0-8ef4-4b1c-85cb-262438d85813',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'foreign_key' => '99522cc9-0acc-5ae2-b996-d03bded3c0a6',
                'model' => 'Avatar',
                'filename' => 'ada.png',
                'filesize' => 170049,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '97e36ab6528e26e3b9f988444ef490f125f49a39',
                'path' => 'Avatar/07/7c/31/63dd68f08ef44b1c85cb262438d85813/63dd68f08ef44b1c85cb262438d85813.png',
                'adapter' => 'Local',
                'created' => '2019-03-08 12:57:33',
                'modified' => '2019-03-08 12:57:33'
            ],
            [
                'id' => 'c02b86ed-94f7-487e-9dfc-7a37e95bcaee',
                'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
                'foreign_key' => '90c45240-00ae-5aea-92a1-4b5488d5ec11',
                'model' => 'Avatar',
                'filename' => 'kathleen.png',
                'filesize' => 53376,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '95474e9309c7c7f14fbe5c3b0f943bd145c0a366',
                'path' => 'Avatar/aa/46/eb/c02b86ed94f7487e9dfc7a37e95bcaee/c02b86ed94f7487e9dfc7a37e95bcaee.png',
                'adapter' => 'Local',
                'created' => '2019-03-08 12:57:33',
                'modified' => '2019-03-08 12:57:33'
            ],
            [
                'id' => 'e2532898-a572-430f-9913-20092a7ffdfc',
                'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'foreign_key' => '08710a74-8996-5f60-b5db-ffabfa85bfe6',
                'model' => 'Avatar',
                'filename' => 'edith.png',
                'filesize' => 20462,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '6a508422b1765eaa13c28f4611340414622f9cf9',
                'path' => 'Avatar/bc/76/54/e2532898a572430f991320092a7ffdfc/e2532898a572430f991320092a7ffdfc.png',
                'adapter' => 'Local',
                'created' => '2019-03-08 12:57:32',
                'modified' => '2019-03-08 12:57:32'
            ],
            [
                'id' => 'e382bc9e-c187-46c1-b277-37f5b3993735',
                'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'foreign_key' => '48bcd9ac-a520-53e0-b3a4-9da7e57b91aa',
                'model' => 'Avatar',
                'filename' => 'carol.png',
                'filesize' => 733439,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '7445a736df60a1ac1bfdab8fc5b842a95c495aec',
                'path' => 'Avatar/5d/4d/6b/e382bc9ec18746c1b27737f5b3993735/e382bc9ec18746c1b27737f5b3993735.png',
                'adapter' => 'Local',
                'created' => '2019-03-08 12:57:33',
                'modified' => '2019-03-08 12:57:33'
            ],
            [
                'id' => 'fe4b7a2a-3d0f-4e04-a58e-ca2d399eb23e',
                'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
                'foreign_key' => '543865d0-5f9b-598d-928b-2811f3cae77f',
                'model' => 'Avatar',
                'filename' => 'frances.png',
                'filesize' => 283883,
                'mime_type' => 'image/png',
                'extension' => 'png',
                'hash' => '95af1b264a94de0b75af95e75030832245afc8bf',
                'path' => 'Avatar/e7/e1/c7/fe4b7a2a3d0f4e04a58eca2d399eb23e/fe4b7a2a3d0f4e04a58eca2d399eb23e.png',
                'adapter' => 'Local',
                'created' => '2019-03-08 12:57:33',
                'modified' => '2019-03-08 12:57:33'
            ],
        ];
        parent::init();
    }
}
