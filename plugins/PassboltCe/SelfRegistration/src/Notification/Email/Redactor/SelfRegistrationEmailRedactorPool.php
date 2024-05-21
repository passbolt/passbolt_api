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
 * @since         3.10.0
 */

namespace Passbolt\SelfRegistration\Notification\Email\Redactor;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;
use Passbolt\SelfRegistration\Notification\Email\Redactor\Settings\SelfRegistrationSettingsAdminEmailRedactor;
use Passbolt\SelfRegistration\Notification\Email\Redactor\User\SelfRegistrationAdminEmailRedactor;

class SelfRegistrationEmailRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * @inheritDoc
     */
    public function getSubscribedRedactors(): array
    {
        $redactors = [];

        // This setting cannot be deactivated
        $redactors[] = new SelfRegistrationSettingsAdminEmailRedactor();
        $redactors[] = new SelfRegistrationAdminEmailRedactor();

        return $redactors;
    }
}
