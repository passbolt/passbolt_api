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
use Passbolt\Log\Events\Traits\EntitiesHistoryTrait;

class ActionLogsModelListener implements EventListenerInterface
{
    use EntitiesHistoryTrait;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        /**
         * Return a list if implemented Events, with their callback.
         * The callback is based on the camelized name of the event slug.
         * Example: event "user.add" will have callback "logUserAdd"
         */
        return [
            'Model.afterSave' => 'logEntityHistory',
            'Model.afterDelete' => 'logEntityHistory',
            'Model.afterRead' => 'logEntityHistory',
            'Model.initialize' => 'entityAssociationsInitialize',
        ];
    }
}
