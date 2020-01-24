<?php
namespace Passbolt\Folders\Notification\Email;

use App\Model\Table\UsersTable;
use App\Notification\Email\AbstractSubscribedEmailRedactorPool;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use Cake\ORM\TableRegistry;

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
 * @since         2.14.0
 */

class FoldersEmailRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * Return a list of subscribed redactors
     * @return SubscribedEmailRedactorInterface[]
     */
    public function getSubscribedRedactors()
    {
        /** @var UsersTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        return [
            new CreateFolderEmailRedactor($usersTable)
        ];
    }
}
