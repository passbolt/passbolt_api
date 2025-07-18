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
 * @since         5.4.0
 */
namespace App\Event;

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;

class UpdateUserLastLoggedInListener implements EventListenerInterface
{
    public const EVENT_USER_LOGIN_SUCCESS = 'UserLogin.Success';

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [self::EVENT_USER_LOGIN_SUCCESS => 'updateUserLastLoggedIn'];
    }

    /**
     * @param \Cake\Event\EventInterface $event Event object.
     * @return void
     */
    public function updateUserLastLoggedIn(EventInterface $event): void
    {
        $userData = $event->getData('user');
        $userId = $userData['id'] ?? null;

        if (is_null($userId)) {
            return;
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $usersTable->updateAll(['last_logged_in' => DateTime::now()], ['id' => $userId]);
    }
}
