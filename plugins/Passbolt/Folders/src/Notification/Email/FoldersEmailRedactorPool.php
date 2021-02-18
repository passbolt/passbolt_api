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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Notification\Email;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

class FoldersEmailRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * Return a list of subscribed redactors
     *
     * @return \Passbolt\Folders\Notification\Email\SubscribedEmailRedactorInterface[]
     */
    public function getSubscribedRedactors()
    {
        $redactors = [];

        if ($this->isRedactorEnabled('send.folder.create')) {
            $redactors[] = new CreateFolderEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.folder.update')) {
            $redactors[] = new UpdateFolderEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.folder.delete')) {
            $redactors[] = new DeleteFolderEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.folder.share')) {
            $redactors[] = new ShareFolderEmailRedactor();
        }

        return $redactors;
    }

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
}
