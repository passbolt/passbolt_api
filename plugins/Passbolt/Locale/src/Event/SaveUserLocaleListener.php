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
 * @since         3.2.0
 */
namespace Passbolt\Locale\Event;

use App\Controller\Setup\SetupCompleteController;
use App\Controller\Users\UsersRegisterController;
use App\Error\Exception\ValidationException;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Passbolt\Locale\Service\SetUserLocaleService;

class SaveUserLocaleListener implements EventListenerInterface
{
    public const LOCALE_KEY = 'locale';

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            SetupCompleteController::COMPLETE_SUCCESS_EVENT_NAME => 'setUserLocaleIfFoundInPayload',
            UsersRegisterController::USERS_REGISTER_EVENT_NAME => 'setUserLocaleIfFoundInPayload',
        ];
    }

    /**
     * On successful complete setup, the locale provided in the payload
     * is stored in the user's settings.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     */
    public function setUserLocaleIfFoundInPayload(EventInterface $event): void
    {
        $locale = $event->getData('data')[static::LOCALE_KEY] ?? null;
        $user = $event->getData('user');
        $service = new SetUserLocaleService();
        if ($service->isValidLocale($locale)) {
            try {
                $service->save($user->id, $locale);
            } catch (ValidationException $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
