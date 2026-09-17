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

use App\Model\Table\ResourcesTable;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\OfflineMode\Model\Table\OfflineItemsTable;

class OfflineItemsResourceDeleteListener implements EventListenerInterface
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            ResourcesTable::EVENT_MODEL_RESOURCE_AFTER_SOFT_DELETE => 'handleResourceAfterSoftDelete',
        ];
    }

    /**
     * @param \Cake\Event\Event $event Event with the deleted `Resource` as subject.
     * @return void
     */
    public function handleResourceAfterSoftDelete(Event $event): void
    {
        /** @var \App\Model\Entity\Resource $resource */
        $resource = $event->getSubject();
        $this->fetchTable('Passbolt/OfflineMode.OfflineItems')
            ->deleteAll([
                'foreign_model' => OfflineItemsTable::FOREIGN_MODEL_RESOURCE,
                'foreign_key' => $resource->get('id'),
            ]);
    }
}
