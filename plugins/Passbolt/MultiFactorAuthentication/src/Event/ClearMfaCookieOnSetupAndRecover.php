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
namespace Passbolt\MultiFactorAuthentication\Event;

use App\Controller\Setup\RecoverAbortController;
use App\Controller\Setup\RecoverCompleteController;
use App\Controller\Setup\SetupCompleteController;
use App\Controller\Users\UsersRecoverController;
use App\Controller\Users\UsersRegisterController;
use App\Model\Entity\Role;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\MultiFactorAuthentication\Service\ClearMfaCookieInResponseService;

class ClearMfaCookieOnSetupAndRecover implements EventListenerInterface
{
    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.shutdown' => 'clearMfaCookieInResponse',
        ];
    }

    /**
     * The controllers concerned
     *
     * @return string[]
     */
    public function getListOfControllers(): array
    {
        return [
            UsersRegisterController::class,
            UsersRecoverController::class,
            SetupCompleteController::class,
            RecoverCompleteController::class,
            RecoverAbortController::class,
        ];
    }

    /**
     * If a user is accessing one of the end points above and is guest,
     * clear any MFA Cookie found in the request.
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function clearMfaCookieInResponse(EventInterface $event): void
    {
        /** @var \App\Controller\AppController $controller */
        $controller = $event->getSubject();
        $isUserGuest = $controller->User->getAccessControl()->roleName() === Role::GUEST;
        $isControllerInList = in_array(get_class($controller), $this->getListOfControllers());

        if ($isUserGuest && $isControllerInList) {
            (new ClearMfaCookieInResponseService($controller))->clearMfaCookie();
        }
    }
}
