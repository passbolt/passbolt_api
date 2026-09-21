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

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsDeleteService;

/**
 * Truncates `offline_items` when Offline Mode is disabled organisation-wide.
 */
class OfflineItemsSettingsDeleteListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            OfflineSettingsDeleteService::EVENT_SETTINGS_DELETED => 'truncateOfflineItems',
        ];
    }

    /**
     * Bulk-truncate `offline_items` inside the delete-service transaction.
     *
     * @param \Cake\Event\EventInterface $event `OfflineSettings.afterDelete.success` event.
     * @return void
     */
    public function truncateOfflineItems(EventInterface $event): void
    {
        /** @var \Passbolt\OfflineMode\Model\Table\OfflineItemsTable $offlineItemsTable */
        $offlineItemsTable = TableRegistry::getTableLocator()->get('Passbolt/OfflineMode.OfflineItems');
        $offlineItemsTable->deleteAll([]);
    }
}
