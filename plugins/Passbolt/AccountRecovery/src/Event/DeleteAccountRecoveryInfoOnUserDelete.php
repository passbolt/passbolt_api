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
 * @since         3.6.0
 */
namespace Passbolt\AccountRecovery\Event;

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\AccountRecovery\Service\AccountRecoveryUserDelete\AccountRecoveryUserDeleteService;

class DeleteAccountRecoveryInfoOnUserDelete implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Model.afterSave' => 'deleteAccountRecoveryInfo',
        ];
    }

    /**
     * Checks if the user setting is contained in the request
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function deleteAccountRecoveryInfo(EventInterface $event): void
    {
        /** @var \Cake\ORM\Table $table */
        $table = $event->getSubject();
        if ($table->getAlias() !== 'Users') {
            return;
        }

        $data = $event->getData();
        $entity = $data['entity'];
        if (!$entity->deleted) {
            return;
        }

        (new AccountRecoveryUserDeleteService())->deleteInfo($entity->id);
    }
}
