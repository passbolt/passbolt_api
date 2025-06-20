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
 * @since         5.1.0
 */
namespace Passbolt\PasswordExpiry\Event;

use App\Controller\Setup\SetupCompleteController;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryEnableOnInstanceCreationService;

class PasswordExpiryFirstUserSetupCompleteEventListener implements EventListenerInterface
{
    use EventDispatcherTrait;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            SetupCompleteController::COMPLETE_SUCCESS_EVENT_NAME => 'enablePasswordExpiryOnInstanceCreation',
        ];
    }

    /**
     * When the first user of an instance is registering, enable the password expiry feature
     *
     * @param \Cake\Event\EventInterface $event a user has completed registration
     * @return void
     */
    public function enablePasswordExpiryOnInstanceCreation(EventInterface $event): void
    {
        $uac = $event->getData('uac');
        (new PasswordExpiryEnableOnInstanceCreationService())->enableOnPassboltInstanceCreation($uac);
    }
}
