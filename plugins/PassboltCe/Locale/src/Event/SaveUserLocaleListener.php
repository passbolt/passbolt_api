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
use App\Service\Setup\RecoverCompleteServiceInterface;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\Utility\Hash;
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
            RecoverCompleteServiceInterface::COMPLETE_SUCCESS_EVENT_NAME => 'setUserLocaleIfFoundInPayload',
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
        $data = $event->getData('data');
        $localeKey = static::LOCALE_KEY;
        $locale = Hash::get($data, "user.$localeKey") ?: Hash::get($data, $localeKey);
        if (is_null($locale)) {
            return;
        }

        /** @var \App\Model\Entity\User $user */
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
