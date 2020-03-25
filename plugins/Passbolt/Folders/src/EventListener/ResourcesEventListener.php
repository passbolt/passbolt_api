<?php
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

namespace Passbolt\Folders\EventListener;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Passbolt\Folders\Service\Resources\ResourcesAfterCreateService;
use Passbolt\Folders\Service\Resources\ResourcesAfterSoftDeleteService;
use Passbolt\Folders\Service\Resources\ResourcesAfterUpdateService;

/**
 * Listen when a resource is created or updated,
 * and use the request payload to create/delete relation between a resource and the given/existing parent.
 *
 * Class AddFolderParentIdBehavior
 * @package Passbolt\Folders\EventListener
 */
class ResourcesEventListener implements EventListenerInterface
{
    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'ResourcesAddController.addPost.success' => 'handleResourceAfterCreateEvent',
            'ResourcesUpdateController.update.success' => 'handleResourceAfterUpdateEvent',
            'Model.Resource.afterSoftDelete' => 'handleResourceAfterSoftDeleteEvent',
        ];
    }

    /**
     * Handle a resource after create event.
     * @param Event $event The event.
     * @return void
     * @throws \Exception
     */
    public function handleResourceAfterCreateEvent(Event $event)
    {
        $resource = $event->getData('resource');
        $uac = $event->getData('accessControl');
        $data = $event->getData('data');
        $service = new ResourcesAfterCreateService();
        $service->afterCreate($uac, $resource, $data);
    }

    /**
     * Handle a resource after update event.
     * @param Event $event The event.
     * @return void
     * @throws \Exception
     */
    public function handleResourceAfterUpdateEvent(Event $event)
    {
        $resource = $event->getData('resource');
        $uac = $event->getData('accessControl');
        $data = $event->getData('data');
        $service = new ResourcesAfterUpdateService();
        $service->afterUpdate($uac, $resource, $data);
    }

    /**
     * Handle a resource after soft delete event.
     * @param Event $event The event.
     * @return void
     * @throws \Exception
     */
    public function handleResourceAfterSoftDeleteEvent(Event $event)
    {
        $resource = $event->getSubject();
        $service = new ResourcesAfterSoftDeleteService();
        $service->afterSoftDelete($resource);
    }
}
