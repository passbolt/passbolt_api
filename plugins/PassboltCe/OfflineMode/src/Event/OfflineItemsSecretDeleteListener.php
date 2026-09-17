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

use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\Secret;
use App\Model\Table\GroupsTable;
use App\Service\GroupsUsers\GroupsUsersDeleteService;
use App\Service\Resources\ResourcesShareService;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\OfflineMode\Model\Table\OfflineItemsTable;

class OfflineItemsSecretDeleteListener implements EventListenerInterface
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            ResourcesShareService::SHARE_SUCCESS_EVENT_NAME => 'handleSecretsBatchDeleted',
            GroupsUsersDeleteService::AFTER_GROUP_USER_DELETED_EVENT_NAME => 'handleSecretsBatchDeleted',
            GroupsTable::EVENT_MODEL_GROUP_AFTER_SOFT_DELETE => 'handleSecretsBatchDeleted',
        ];
    }

    /**
     * @param \Cake\Event\Event $event Event carrying an `entitiesChanges` DTO whose deleted Secret entities determine
     *                                 which `(user_id, resource_id)` offline_items rows to wipe.
     * @return void
     */
    public function handleSecretsBatchDeleted(Event $event): void
    {
        $changes = $event->getData('entitiesChanges');
        if (!$changes instanceof EntitiesChangesDto) {
            return;
        }
        $deletedSecrets = $changes->getDeletedEntities(Secret::class);
        if (empty($deletedSecrets)) {
            return;
        }
        $resourceIdsByUser = [];
        foreach ($deletedSecrets as $secret) {
            $resourceIdsByUser[$secret->get('user_id')][] = $secret->get('resource_id');
        }
        $offlineItems = $this->fetchTable('Passbolt/OfflineMode.OfflineItems');
        foreach ($resourceIdsByUser as $userId => $resourceIds) {
            $offlineItems->deleteAll([
                'user_id' => $userId,
                'foreign_model' => OfflineItemsTable::FOREIGN_MODEL_RESOURCE,
                'foreign_key IN' => array_values(array_unique($resourceIds)),
            ]);
        }
    }
}
