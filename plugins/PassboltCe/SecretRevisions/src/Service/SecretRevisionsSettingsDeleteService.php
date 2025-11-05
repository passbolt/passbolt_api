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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Service;

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;

class SecretRevisionsSettingsDeleteService
{
    use LocatorAwareTrait;

    /**
     * Deletes the secret revisions settings.
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException When user role is not admin.
     * @throws \Cake\ORM\Exception\PersistenceFailedException If settings aren't deleted.
     */
    public function delete(UserAccessControl $uac): void
    {
        $uac->assertIsAdmin();

        /** @var \App\Model\Table\OrganizationSettingsTable $orgSettingsTable */
        $orgSettingsTable = TableRegistry::getTableLocator()->get('OrganizationSettings');

        try {
            $setting = $orgSettingsTable->getFirstSettingOrFail(SecretRevisionsSettingsGetService::ORG_SETTING_PROPERTY); // phpcs:ignore
        } catch (RecordNotFoundException) {
            // Return success if settings aren't present
            return;
        }

        $orgSettingsTable->deleteOrFail($setting);
    }
}
