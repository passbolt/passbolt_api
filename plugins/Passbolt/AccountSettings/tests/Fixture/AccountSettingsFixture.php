<?php
namespace Passbolt\AccountSettings\Test\Fixture;

use App\Utility\UuidFactory;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * AccountSettingsFixture
 *
 */
class AccountSettingsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'property_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'property' => ['type' => 'string', 'length' => 256, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'value' => ['type' => 'string', 'length' => 256, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'user_id' => ['type' => 'index', 'columns' => ['user_id', 'property_id'], 'length' => []],
        ],
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
                'id' => UuidFactory::uuid('account.settings.ada.theme'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'property_id' => UuidFactory::uuid('account.settings.property.id.theme'),
                'property' => 'theme',
                'value' => 'midgar'
            ],
            [
                'id' => UuidFactory::uuid('account.settings.betty.theme'),
                'user_id' => UuidFactory::uuid('user.id.betty'),
                'property_id' => UuidFactory::uuid('account.settings.property.id.theme'),
                'property' => 'theme',
                'value' => 'default'
            ],
        ];
        parent::init();
    }
}
