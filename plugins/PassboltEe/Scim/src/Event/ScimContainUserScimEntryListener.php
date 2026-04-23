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
 * @since         5.5.0
 */
namespace Passbolt\Scim\Event;

use App\Controller\Users\UsersIndexController;
use App\Controller\Users\UsersViewController;
use App\Middleware\UacAwareMiddlewareTrait;
use App\Model\Event\TableFindIndexBefore;
use App\Model\Table\UsersTable;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;

class ScimContainUserScimEntryListener implements EventListenerInterface
{
    use UacAwareMiddlewareTrait;

    /**
     * @var bool
     */
    private bool $isContained = false;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.initialize' => 'setIsContained',
            TableFindIndexBefore::EVENT_NAME => 'containUserScimEntry',
        ];
    }

    /**
     * Checks if the user setting is contained in the request
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function setIsContained(EventInterface $event): void
    {
        $isContained = false;
        $controller = $event->getSubject();
        if ($controller instanceof UsersIndexController || $controller instanceof UsersViewController) {
            $isAdmin = $this->getUacInRequest($controller->getRequest())->isAdmin();
            $isContained = $isAdmin && $controller
                ->getRequest()
                ->getQuery('contain.scim_entry');
        }
        $this->isContained = $isContained;
    }

    /**
     * If the user pending request is contained in the request, contain it in the query
     *
     * @param \App\Model\Event\TableFindIndexBefore $event Event
     * @return void
     */
    public function containUserScimEntry(TableFindIndexBefore $event): void
    {
        if (!$this->isContained) {
            return;
        }

        $table = $event->getSubject();
        // This decoration should apply to the users table only, and silently
        // return if this is not the case.
        if (!($table instanceof UsersTable)) {
            return;
        }

        $event->getQuery()->contain('ScimEntries');
    }
}
