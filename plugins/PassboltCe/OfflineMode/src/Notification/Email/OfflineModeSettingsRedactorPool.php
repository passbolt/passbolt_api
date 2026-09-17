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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Notification\Email;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;

class OfflineModeSettingsRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * Return the list of subscribed redactors.
     *
     * @return array<\App\Notification\Email\SubscribedEmailRedactorInterface>
     */
    public function getSubscribedRedactors(): array
    {
        return [
            new OfflineSettingsSetEmailRedactor(),
            new OfflineSettingsDeleteEmailRedactor(),
        ];
    }
}
