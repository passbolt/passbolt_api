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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Event;

use App\Controller\Resources\ResourcesIndexController;
use App\Middleware\UacAwareMiddlewareTrait;
use App\Model\Event\TableFindIndexBefore;
use App\Model\Table\ResourcesTable;
use App\Utility\UuidFactory;
use Cake\Collection\CollectionInterface;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;

class OfflineResourceIndexListener implements EventListenerInterface
{
    use UacAwareMiddlewareTrait;

    /**
     * @var bool
     */
    private bool $isContainRequested = false;

    /**
     * @var bool
     */
    private bool $isRbacAllowed = false;

    /**
     * @var bool
     */
    private bool $isFeatureEnabled = false;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.initialize' => 'inspectRequestAndFlagState',
            TableFindIndexBefore::EVENT_NAME => 'attachOfflineContain',
        ];
    }

    /**
     * @param \Cake\Event\EventInterface $event Controller initialize event.
     * @return void
     */
    public function inspectRequestAndFlagState(EventInterface $event): void
    {
        $controller = $event->getSubject();
        if (!$controller instanceof ResourcesIndexController) {
            return;
        }

        $request = $controller->getRequest();
        $isContainRequested = (bool)$request->getQuery('contain.offline', false);
        if (!$isContainRequested) {
            return;
        }
        $this->isContainRequested = true;

        $uac = $this->getUacInRequest($request);

        /** @var \App\Model\Table\RolesTable $rolesTable */
        $rolesTable = TableRegistry::getTableLocator()->get('Roles');
        /** @var \App\Model\Entity\Role $role */
        $role = $rolesTable->find()->select(['id', 'name'])->where(['name' => $uac->roleName()])->firstOrFail();

        /** @var \Passbolt\Rbacs\Model\Table\RbacsTable $rbacsTable */
        $rbacsTable = TableRegistry::getTableLocator()->get('Passbolt/Rbacs.Rbacs');
        $actionId = UuidFactory::uuid(RbacsControlledActionsInsertService::NAME_OFFLINE_ITEMS_VIEW);
        $this->isRbacAllowed = $role->isAdmin() || $rbacsTable->isActionAllowedForRole($role->id, $actionId);

        /** @var \Passbolt\OfflineMode\Model\Table\OfflineModeSettingsTable $settingsTable */
        $settingsTable = TableRegistry::getTableLocator()->get('Passbolt/OfflineMode.OfflineModeSettings');
        $this->isFeatureEnabled = $settingsTable->isOfflineModeFeatureEnabled();
    }

    /**
     * @param \App\Model\Event\TableFindIndexBefore $event Event object.
     * @return void
     */
    public function attachOfflineContain(TableFindIndexBefore $event): void
    {
        if (!$this->isContainRequested) {
            return;
        }

        $table = $event->getSubject();
        if (!$table instanceof ResourcesTable) {
            return;
        }

        /** @var \Cake\ORM\Query\SelectQuery $query */
        $query = $event->getData('query');

        if (!$this->isRbacAllowed || !$this->isFeatureEnabled) {
            // Suppress the payload — either the acting role lacks OfflineItemsView.view
            // or the feature is disabled org-wide. Force `offline` to null on every row.
            $query->formatResults(function (CollectionInterface $results): CollectionInterface {
                return $results->map(function (EntityInterface|array $row): EntityInterface|array {
                    if ($row instanceof EntityInterface) {
                        $row->set('offline', null, ['guard' => false]);
                    } else {
                        $row['offline'] = null;
                    }

                    return $row;
                });
            });

            return;
        }

        /** @var \App\Model\Table\Dto\FindIndexOptions $options */
        $options = $event->getData('options');
        $userId = $options->getUserId();
        $query->contain('Offline', function (Query $q) use ($userId): Query {
            return $q->where(['Offline.user_id' => $userId]);
        });
    }
}
