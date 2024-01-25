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

namespace Passbolt\Folders\EventListener;

use App\Model\Entity\Resource;
use App\Service\Resources\ResourcesAddService;
use App\Service\Resources\ResourcesShareService;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Passbolt\Folders\Service\Resources\ResourcesAfterAccessGrantedService;
use Passbolt\Folders\Service\Resources\ResourcesAfterAccessRevokedService;
use Passbolt\Folders\Service\Resources\ResourcesAfterCreateService;
use Passbolt\Folders\Service\Resources\ResourcesAfterSoftDeleteService;

/**
 * Listen when a resource is created or updated,
 * and use the request payload to create/delete relation between a resource and the given/existing parent.
 *
 * @package Passbolt\Folders\EventListener
 */
class ResourcesEventListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            ResourcesAddService::ADD_SUCCESS_EVENT_NAME => [
                'callable' => 'handleResourceAfterCreateEvent',
                'priority' => 1,
            ],
            'Model.Resource.afterSoftDelete' => 'handleResourceAfterSoftDeleteEvent',
            'Service.ResourcesShare.afterAccessGranted' => 'handleResourceAfterAccessGrantedEvent',
            ResourcesShareService::AFTER_ACCESS_REVOKED_EVENT_NAME => 'handleResourceAfterAccessRevokedEvent',
        ];
    }

    /**
     * Handle a resource after create event.
     *
     * @param \Cake\Event\Event $event The event.
     * @param \App\Model\Entity\Resource $resource The resource saved.
     * @return void
     * @throws \Exception
     */
    public function handleResourceAfterCreateEvent(Event $event, Resource $resource)
    {
        $uac = $event->getData('uac');
        $data = $event->getData('data');
        $service = new ResourcesAfterCreateService();
        $service->afterCreate($uac, $resource, $data);
    }

    /**
     * Handle a resource after soft delete event.
     *
     * @param \Cake\Event\Event $event The event.
     * @return void
     * @throws \Exception
     */
    public function handleResourceAfterSoftDeleteEvent(Event $event)
    {
        $resource = $event->getSubject();
        $service = new ResourcesAfterSoftDeleteService();
        $service->afterSoftDelete($resource);
    }

    /**
     * Handle a resource after an access has been granted.
     *
     * @param \Cake\Event\Event $event The event.
     * @return void
     * @throws \Exception
     */
    public function handleResourceAfterAccessGrantedEvent(Event $event)
    {
        $uac = $event->getData('accessControl');
        $permission = $event->getData('permission');
        $service = new ResourcesAfterAccessGrantedService();
        $service->afterAccessGranted($uac, $permission);
    }

    /**
     * Handle a resource after an access has been revoked.
     *
     * @param \Cake\Event\Event $event The event.
     * @return void
     * @throws \Exception
     */
    public function handleResourceAfterAccessRevokedEvent(Event $event)
    {
        $uac = $event->getData('accessControl');
        $permission = $event->getData('permission');
        $service = new ResourcesAfterAccessRevokedService();
        $service->afterAccessRevoked($uac, $permission);
    }
}
