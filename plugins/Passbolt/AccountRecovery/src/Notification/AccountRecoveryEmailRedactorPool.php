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
 * @since         3.3.0
 */

namespace Passbolt\AccountRecovery\Notification;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;

class AccountRecoveryEmailRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * Return true if the redactor pool is enabled
     *
     * @return bool
     */
    private function isAccountRecoveryRedactorEnabled(): bool
    {
        // TODO: Complete this logic with settings when implemented.
        return true;
    }

    /**
     * @return \App\Notification\Email\SubscribedEmailRedactorInterface[]
     */
    public function getSubscribedRedactors(): array
    {
        $redactors = [];

        if ($this->isAccountRecoveryRedactorEnabled()) {
            $redactors[] = new AccountRecoveryRequestCreatedEmailRedactor();
        }

        return $redactors;
    }
}