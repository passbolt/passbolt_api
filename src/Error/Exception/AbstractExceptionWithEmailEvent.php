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
 * @since         3.3.0
 */

namespace App\Error\Exception;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;
use Cake\Log\Log;
use Throwable;

abstract class AbstractExceptionWithEmailEvent extends BadRequestException
{
    /**
     * @var \App\Controller\AppController
     */
    protected $controller;

    /**
     * Name of the event triggered.
     *
     * @return string
     */
    abstract public function getEventName(): string;

    /**
     * The attacked user
     *
     * @return string|null
     */
    abstract public function getUserId(): ?string;

    /**
     * @inheritDoc
     */
    public function __construct(?string $message = null, ?int $code = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        Log::error($message);
        $this->dispatchEmailEvent();
    }

    /**
     * Dispatch the exception's event.
     * The name of the event is the fully qualified exception class.
     * Register these events in a EmailRedactorPool, e.g. in JwtAuthenticationAttackEmailRedactor
     *
     * @return void
     */
    protected function dispatchEmailEvent(): void
    {
        EventManager::instance()->on('Controller.beforeRender', function (EventInterface $event) {
            $this->controller = $event->getSubject();
            $event = new Event($this->getEventName(), $this);
            $this->getController()->getEventManager()->dispatch($event);
        });
    }

    /**
     * The controller calling the exception.
     *
     * @return \App\Controller\AppController
     */
    public function getController(): AppController
    {
        return $this->controller;
    }

    /**
     * @param \App\Controller\AppController $controller Controller of the request
     * @return void
     */
    public function setController(AppController $controller): void
    {
        $this->controller = $controller;
    }
}
