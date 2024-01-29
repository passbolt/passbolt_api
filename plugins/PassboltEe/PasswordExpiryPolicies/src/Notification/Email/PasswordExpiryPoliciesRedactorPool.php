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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiryPolicies\Notification\Email;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;

class PasswordExpiryPoliciesRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * @inheritDoc
     */
    public function getSubscribedRedactors(): array
    {
        $redactors = [];
        $isCronNotificationEnabled =
            $this->isRedactorEnabled('send.password.aboutToExpire') ||
            $this->isRedactorEnabled('send.password.expire');
        if ($isCronNotificationEnabled) {
            $redactors[] = new PasswordExpiryPoliciesNotifyAboutExpiredResourcesEmailRedactor();
        }

        return $redactors;
    }
}
