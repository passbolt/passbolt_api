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
 * @since         2.13.0
 */

namespace App\Notification\Email\Redactor;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use Cake\Core\Configure;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

class CoreEmailRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * Return true if the redactor is enabled
     *
     * @param string $notificationSettingPath Notification Settings path with dot notation
     * @return mixed
     */
    private function isRedactorEnabled(string $notificationSettingPath)
    {
        return EmailNotificationSettings::get($notificationSettingPath);
    }

    /**
     * @return SubscribedEmailRedactorInterface[]
     */
    public function getSubscribedRedactors()
    {
        $redactors = [];

        if ($this->isRedactorEnabled('send.admin.user.setup.completed') && Configure::read('passbolt.plugins.log.enabled')) {
            $redactors[] = new AdminUserSetupCompleteEmailRedactor();
        }

        return $redactors;
    }
}
