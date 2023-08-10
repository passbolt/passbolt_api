<?php
declare(strict_types=1);

use Cake\Log\Log;
use Migrations\AbstractMigration;
use Passbolt\DirectorySync\Service\DirectorySettings\UpdateDirectorySettingsService;

/**
 * Change the ldap domain key `servers` for `hosts` in the organization settings
 */
class V400ChangeLdapServersConfigKey extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        try {
            (new UpdateDirectorySettingsService())->updateSettings();
        } catch (Throwable $e) {
            Log::error('There was an error in V400ChangeLdapServersConfigKey');
            Log::error($e->getMessage());
        }
    }
}
