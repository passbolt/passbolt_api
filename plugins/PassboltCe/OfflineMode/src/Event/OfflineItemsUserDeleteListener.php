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
namespace Passbolt\OfflineMode\Event;

use App\Model\Table\UsersTable;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Locator\LocatorAwareTrait;

class OfflineItemsUserDeleteListener implements EventListenerInterface
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            UsersTable::EVENT_MODEL_USERS_AFTER_SOFT_DELETE => 'handleUserAfterSoftDelete',
        ];
    }

    /**
     * @param \Cake\Event\Event $event Event with the deleted `User` as subject.
     * @return void
     */
    public function handleUserAfterSoftDelete(Event $event): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = $event->getSubject();
        $this->fetchTable('Passbolt/OfflineMode.OfflineItems')
            ->deleteAll(['user_id' => $user->get('id')]);
    }
}
