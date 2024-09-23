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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Event;

use App\Controller\Setup\SetupCompleteController;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\Metadata\Utility\ShareMetadataKeyServiceFactory;

class SetupCompleteListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            SetupCompleteController::COMPLETE_SUCCESS_EVENT_NAME => 'onSetupCompleteEvent',
        ];
    }

    /**
     * @param \Cake\Event\EventInterface $event event
     * @return void
     */
    public function onSetupCompleteEvent(EventInterface $event): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = $event->getData('user');
        $strategy = (new ShareMetadataKeyServiceFactory())->get();

        try {
            $strategy->shareMetadataKeyWithUser($user);
        } catch (\Exception $e) {
            $strategy->onFailure($e);
        }
    }
}
