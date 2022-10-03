<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.8.0
 */
namespace Passbolt\SmtpSettings\Service;

use App\Error\Exception\NoAdminInDbException;
use App\Model\Entity\OrganizationSetting;
use App\Model\Entity\Role;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\UserAccessControl;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

class SmtpSettingsMigrationService
{
    use FeaturePluginAwareTrait;

    /**
     * @var array
     */
    private $smtpSettings;

    /**
     * @var string
     */
    private $passboltFileName;

    /**
     * @param string $passboltFileName The passbolt config file, modifiable for unit test purpose.
     */
    public function __construct(string $passboltFileName = CONFIG . DS . 'passbolt.php')
    {
        $this->passboltFileName = $passboltFileName;
    }

    /**
     * Save SMTP Settings in the DB if defined in config/passbolt.php file
     *
     * @return \App\Model\Entity\OrganizationSetting|null
     */
    public function migrateSmtpSettingsToDb(): ?OrganizationSetting
    {
        if (!$this->isFeaturePluginEnabled('SmtpSettings')) {
            return null;
        }

        $orgSetting = null;
        try {
            $this->fetchSettings();
            if ($this->isSourceFile()) {
                $orgSetting = $this->saveSettingsInDb();
                $orgSetting->set('source', SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_FILE);
            }
        } catch (NoAdminInDbException $e) {
          // Silently do nothing, this is probably due running a fresh installation
            Log::info($e->getMessage() . ' Ignoring the import of the SMTP Settings.');
        } catch (\Throwable $e) {
            $this->logWarning($e->getMessage());
        }

        return $orgSetting;
    }

    /**
     * Read the present settings
     *
     * @return void
     */
    private function fetchSettings(): void
    {
        $this->smtpSettings = (new SmtpSettingsGetService($this->passboltFileName))->getSettings();
        Log::info('SMTP Settings were detected in ' . $this->getSource() . '.');
    }

    /**
     * Import settings in the DB if found in file
     *
     * @return \App\Model\Entity\OrganizationSetting
     * @throws \App\Error\Exception\NoAdminInDbException if no admin is found
     */
    private function saveSettingsInDb(): OrganizationSetting
    {
        /** @var \App\Model\Table\UsersTable $Users */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $admin = $Users->findFirstAdminOrThrowNoAdminInDbException();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $orgSetting = (new SmtpSettingsSetService($uac))->saveSettings($this->smtpSettings);
        Log::info('SMTP Settings were imported from ' . CONFIG . DS . 'passbolt.php to the database.');

        return $orgSetting;
    }

    /**
     * @return string|null
     */
    private function getSource(): ?string
    {
        return $this->smtpSettings['source'] ?? null;
    }

    /**
     * @return bool
     */
    private function isSourceFile(): bool
    {
        return $this->getSource() === SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_FILE;
    }

    /**
     * @param string $msg Message to log
     * @return void
     */
    private function logWarning(string $msg): void
    {
        Log::warning('There was an error in V380SaveSmtpSettingsInDb');
        Log::warning($msg);
    }
}
