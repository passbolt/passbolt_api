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
 * @since         5.8.0
 */
namespace Passbolt\Rbacs\Event;

use App\Service\Roles\RolesDeleteService;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;

class ClearRbacsOnRoleDeleteListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            RolesDeleteService::AFTER_ROLE_DELETE_SUCCESS_EVENT_NAME => 'clearRbacsOnRoleDelete',
        ];
    }

    /**
     * Hard-delete RBACs when the role is deleted.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     */
    public function clearRbacsOnRoleDelete(EventInterface $event): void
    {
        /** @var \App\Utility\UserAccessControl $uac */
        $uac = $event->getData('uac');
        if (!$uac) {
            throw new InvalidArgumentException('`uac` is missing from event data.');
        }

        /** @var \App\Model\Entity\Role $role */
        $role = $event->getData('role');
        if (!$role) {
            throw new InvalidArgumentException('`role` is missing from event data.');
        }

        TableRegistry::getTableLocator()
            ->get('Passbolt/Rbacs.Rbacs')
            ->deleteAll(['role_id' => $role->id]);
    }
}
