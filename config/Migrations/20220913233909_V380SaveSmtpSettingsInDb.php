<?php
declare(strict_types=1);

use App\Utility\Application\FeaturePluginAwareTrait;
use Migrations\AbstractMigration;
use Passbolt\SmtpSettings\Service\SmtpSettingsMigrationService;

class V380SaveSmtpSettingsInDb extends AbstractMigration
{
    use FeaturePluginAwareTrait;

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        if (!$this->isFeaturePluginEnabled('SmtpSettings')) {
            return;
        }

        (new SmtpSettingsMigrationService())->migrateSmtpSettingsToDb();
    }
}
