<?php
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
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

trait EmailNotificationSettingsTestTrait
{
    /**
     * Set email notification setting
     *
     * @param string $config The config key
     * @param bool $value The config value
     */
    protected function setEmailNotificationSetting(string $config, bool $value)
    {
        $settings = [];
        $settings[$config] = $value;
        $this->setEmailNotificationSettings($settings);
    }

    /**
     * Set email notification settings
     *
     * @param array $settings Array of settings
     */
    protected function setEmailNotificationSettings(array $settings = [])
    {
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        EmailNotificationSettings::save($settings, $uac);
    }
}
