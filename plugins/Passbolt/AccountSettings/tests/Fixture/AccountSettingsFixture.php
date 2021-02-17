<?php
namespace Passbolt\AccountSettings\Test\Fixture;

use App\Utility\UuidFactory;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * AccountSettingsFixture
 */
class AccountSettingsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => UuidFactory::uuid('account.settings.ada.theme'),
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'property_id' => UuidFactory::uuid('account.settings.property.id.theme'),
                'property' => 'theme',
                'value' => 'midgar',
            ],
            [
                'id' => UuidFactory::uuid('account.settings.betty.theme'),
                'user_id' => UuidFactory::uuid('user.id.betty'),
                'property_id' => UuidFactory::uuid('account.settings.property.id.theme'),
                'property' => 'theme',
                'value' => 'default',
            ],
        ];
        parent::init();
    }
}
