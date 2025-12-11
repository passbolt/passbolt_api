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
use App\Model\Event\TableFindIndexBefore;
use App\Model\Table\UsersTable;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\UuidFactory;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\Query;
use Passbolt\Rbacs\RbacsPlugin;
use Passbolt\Rbacs\Service\ActionAccessControl\RbacsRoleActionAccessControlService;

class ContainPendingAccountRecoveryRequest implements EventListenerInterface
{
    use FeaturePluginAwareTrait;

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
            TableFindIndexBefore::EVENT_NAME => 'containPendingAccountRecoveryRequest',
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
        if (!($controller instanceof UsersIndexController)) {
            return;
        }
        $isContainInRequest = (bool)$controller
            ->getRequest()
            ->getQuery('contain.pending_account_recovery_request');
        if (!$isContainInRequest) {
            return;
        }
        /** @var \App\Model\Entity\User $identity */
        $identity = $controller->getRequest()->getAttribute('identity');
        if ($identity->role->isAdmin()) {
            $this->isContained = true;

            return;
        }

        if (!$this->isFeaturePluginEnabled(RbacsPlugin::class)) {
            return;
        }

        try {
            (new RbacsRoleActionAccessControlService())->controlUserRoleActionAccess(
                $identity->role,
                UuidFactory::uuid('AccountRecoveryRequestsView.view')
            );
            $this->isContained = true;
        } catch (ForbiddenException) {
            // Do nothing, $this->isContained remains false
        }
    }

    /**
     * If the user pending request is contained in the request, contain it in the query
     *
     * @param \App\Model\Event\TableFindIndexBefore $event Event
     * @return void
     */
    public function containPendingAccountRecoveryRequest(TableFindIndexBefore $event): void
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

        $event->getQuery()->contain('PendingAccountRecoveryRequests', function (Query $q) {
            return $q->select([
                'PendingAccountRecoveryRequests.id',
                'PendingAccountRecoveryRequests.status',
            ]);
        });
    }
}
