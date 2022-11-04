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
 */
namespace Passbolt\Log\Events;

use Cake\Event\EventListenerInterface;
use Passbolt\Log\Events\Traits\ControllerActionTrait;
use Passbolt\Log\Events\Traits\EntitiesHistoryTrait;

class ActionsListener implements EventListenerInterface
{
    use ControllerActionTrait;
    use EntitiesHistoryTrait;

    /**
     * Return a list if implemented Events, with their callback.
     * The callback is based on the camelized name of the event slug.
     * Example: event "user.add" will have callback "logUserAdd"
     *
     * @return array
     */
    public static function getImplementedEvents()
    {
        $implementedEvents = [
            'Controller.beforeRender' => 'logControllerAction',
            'Model.afterSave' => 'logEntityHistory',
            'Model.afterDelete' => 'logEntityHistory',
            'Model.afterRead' => 'logEntityHistory',
            'Model.initialize' => 'entityAssociationsInitialize',
        ];

        return $implementedEvents;
    }

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return self::getImplementedEvents();
    }
}
