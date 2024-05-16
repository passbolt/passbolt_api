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

class FoldersEmailRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * Return a list of subscribed redactors
     *
     * @return \App\Notification\Email\SubscribedEmailRedactorInterface[]
     */
    public function getSubscribedRedactors(): array
    {
        $redactors[] = new CreateFolderEmailRedactor();
        $redactors[] = new UpdateFolderEmailRedactor();
        $redactors[] = new DeleteFolderEmailRedactor();
        $redactors[] = new ShareFolderEmailRedactor();

        return $redactors;
    }
}
