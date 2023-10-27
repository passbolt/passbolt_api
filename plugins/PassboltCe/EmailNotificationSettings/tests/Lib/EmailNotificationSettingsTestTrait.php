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
 * @since         2.10.0
 */
namespace Passbolt\EmailNotificationSettings\Test\Lib;

use App\Model\Entity\Role;
use App\Notification\NotificationSettings\CoreNotificationSettingsDefinition;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Event\EventManager;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

trait EmailNotificationSettingsTestTrait
{
    protected function loadNotificationSettings()
    {
        EventManager::instance()
            ->on(new CoreNotificationSettingsDefinition());
    }

    protected function unloadNotificationSettings()
    {
        EmailNotificationSettings::flushCache();
    }

    /**
     * Set email notification setting
     *
     * @param string $config The config key
     * @param bool $value The config value
     */
    protected function setEmailNotificationSetting(string $config, bool $value)
    {
        $this->setEmailNotificationSettings([$config => $value]);
    }

    /**
     * Set email notification settings
     *
     * @param array $settings Array of settings
     */
    protected function setEmailNotificationSettings(array $settings)
    {
        $settingsToSave = [];
        foreach ($settings as $key => $setting) {
            $key = EmailNotificationSettings::underscoreToDottedFormat($key);
            $settingsToSave[$key] = $setting;
        }
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        EmailNotificationSettings::save($settingsToSave, $uac, true);
    }

    /**
     * Returns default email notification values.
     *
     * @return array
     * @throws \Exception When config file can't be read
     */
    public function getDefaultEmailNotificationConfig(): array
    {
        $configPath = CONFIG . 'default.php';
        if (!is_file($configPath) || !is_readable($configPath)) {
            throw new \Exception(
                __('The {0} data file can not be found/read: {1}', 'default.php', CONFIG)
            );
        }

        $defaultConfigArray = include $configPath;

        return $defaultConfigArray['passbolt']['email'];
    }
}
