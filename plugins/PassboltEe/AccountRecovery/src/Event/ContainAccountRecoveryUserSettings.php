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

use App\Controller\Users\UsersIndexController;
use App\Controller\Users\UsersViewController;
use App\Middleware\UacAwareMiddlewareTrait;
use App\Model\Event\TableFindIndexBefore;
use App\Model\Table\UsersTable;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Query;

class ContainAccountRecoveryUserSettings implements EventListenerInterface
{
    use UacAwareMiddlewareTrait;

    /**
     * @var bool
     */
    private $isContained = false;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.initialize' => 'setIsContained',
            TableFindIndexBefore::EVENT_NAME => 'containUserSettings',
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
        $controller = $event->getSubject();
        $isContainedInQuery = (bool)$controller->getRequest()->getQuery('contain.account_recovery_user_setting');
        $uac = $this->getUacInRequest($controller->getRequest());
        if ($controller instanceof UsersViewController) {
            // On the view controller, only the logged-in user can access the contained data.
            $userId = $controller->getRequest()->getParam('id');
            $isMe = $userId === 'me' || $userId === $uac->getId();
            $this->isContained = $isContainedInQuery && $isMe;
        } elseif ($controller instanceof UsersIndexController) {
            // On the index controller, only admins can access the contained data
            $this->isContained = $isContainedInQuery && $uac->isAdmin();
        } else {
            $this->isContained = false;
        }
    }

    /**
     * If the user setting is contained in the request, contain it in the query
     *
     * @param \App\Model\Event\TableFindIndexBefore $event Event
     * @return void
     */
    public function containUserSettings(TableFindIndexBefore $event): void
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

        $event->getQuery()->contain('AccountRecoveryUserSettings', function (Query $q) {
            return $q->select('status');
        });
    }
}
