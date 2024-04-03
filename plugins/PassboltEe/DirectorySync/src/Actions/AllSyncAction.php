<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Actions;

use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\Secret;
use App\Service\Resources\ResourcesExpireResourcesServiceInterface;
use Cake\ORM\TableRegistry;
use Passbolt\DirectorySync\Actions\Reports\ActionReportCollection;

class AllSyncAction
{
    /**
     * @var \App\Service\Resources\ResourcesExpireResourcesServiceInterface
     */
    protected ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService;

    protected EntitiesChangesDto $entitiesChangesDto;

    /**
     * @param \App\Service\Resources\ResourcesExpireResourcesServiceInterface $expireResourcesService expiry resource service
     */
    public function __construct(
        ResourcesExpireResourcesServiceInterface $expireResourcesService
    ) {
        $this->resourcesExpireResourcesService = $expireResourcesService;
        $this->entitiesChangesDto = new EntitiesChangesDto();
    }

    /**
     * Synchronize users.
     *
     * @param bool $dryRun is the sync action in dry run mode
     * @return \Passbolt\DirectorySync\Actions\Reports\ActionReportCollection reports collection
     */
    private function syncUsers(bool $dryRun): ActionReportCollection
    {
        $userSyncAction = (new UserSyncAction($this->resourcesExpireResourcesService))->setDryRun($dryRun);

        return $this->executeSyncAction($userSyncAction);
    }

    /**
     * Synchronize groups.
     *
     * @param bool $dryRun is the sync action in dry run mode
     * @return \Passbolt\DirectorySync\Actions\Reports\ActionReportCollection reports collection
     */
    private function syncGroups(bool $dryRun): ActionReportCollection
    {
        $groupSyncAction = (new GroupSyncAction($this->resourcesExpireResourcesService))->setDryRun($dryRun);

        return $this->executeSyncAction($groupSyncAction);
    }

    /**
     * Synchronize users or groups
     * Collect entity changes
     *
     * @param \Passbolt\DirectorySync\Actions\SyncAction $syncAction Sync action to execute
     * @return \Passbolt\DirectorySync\Actions\Reports\ActionReportCollection
     */
    private function executeSyncAction(SyncAction $syncAction): ActionReportCollection
    {
        $reports = $syncAction->setAsPartOfAllSync()->execute();
        $this->entitiesChangesDto->merge($syncAction->getEntitiesChangesDto());

        return $reports;
    }

    /**
     * Synchronize all (users and groups).
     *
     * @param bool $dryRun is the sync in dry run mode
     * @return array array reports collection for each item
     */
    private function syncAll(bool $dryRun): array
    {
        $userReports = $this->syncUsers($dryRun);
        $groupReports = $this->syncGroups($dryRun);
        if (!$dryRun) {
            $deletedSecrets = $this->entitiesChangesDto->getDeletedEntities(Secret::class);
            $this->resourcesExpireResourcesService->expireResourcesForSecrets($deletedSecrets);
        }

        return [
            'users' => $userReports,
            'groups' => $groupReports,
        ];
    }

    /**
     * Execute.
     *
     * @param bool $dryRun whether to do it in dry run mode.
     * @return array reports collection.
     */
    public function execute(?bool $dryRun = false): array
    {
        $Users = TableRegistry::getTableLocator()->get('Users');
        $reports = [];
        if ($dryRun) {
            $conn = $Users->getConnection();
            $conn->begin();
            $conn->transactional(function () use (&$reports, $dryRun) {
                $reports = $this->syncAll($dryRun);
            });
            $conn->rollback();
        } else {
            $reports = $this->syncAll($dryRun);
        }

        return $reports;
    }
}
