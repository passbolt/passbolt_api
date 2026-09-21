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
namespace Passbolt\OfflineMode\Service\Settings;

use App\Utility\ExtendedUserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\ORM\Locator\LocatorAwareTrait;

class OfflineSettingsDeleteService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    public const EVENT_SETTINGS_DELETED = 'OfflineSettings.afterDelete.success';

    /**
     * Load the offline-settings row by id, delete it, and dispatch the `OfflineSettings.afterDelete.success` event.
     *
     * @param \App\Utility\ExtendedUserAccessControl $uac Extended UAC Object.
     * @param string $orgSettingId `organization_settings.id` of the row to delete.
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException When the user is not an admin.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When the row does not exist or its property is not offlineMode.
     */
    public function delete(ExtendedUserAccessControl $uac, string $orgSettingId): void
    {
        $uac->assertIsAdmin();

        /** @var \Passbolt\OfflineMode\Model\Table\OfflineModeSettingsTable $offlineModeSettingsTable */
        $offlineModeSettingsTable = $this->fetchTable('Passbolt/OfflineMode.OfflineModeSettings');

        $offlineModeSettingsTable->getConnection()->transactional(
            function () use ($offlineModeSettingsTable, $orgSettingId, $uac): void {
                /** @var \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting $entity */
                $entity = $offlineModeSettingsTable->find()
                    ->where(['id' => $orgSettingId])
                    ->epilog('FOR UPDATE')
                    ->firstOrFail();

                $offlineModeSettingsTable->deleteOrFail($entity);

                $this->dispatchEvent(self::EVENT_SETTINGS_DELETED, compact('entity', 'uac'));
            },
        );
    }
}
