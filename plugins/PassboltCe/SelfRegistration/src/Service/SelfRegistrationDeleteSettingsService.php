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
 * @since         3.9.0
 */
namespace Passbolt\SelfRegistration\Service;

use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class SelfRegistrationDeleteSettingsService
{
    /**
     * Delete the self registration settings in the DB
     * If not found, return a NotFoundException
     *
     * @param string $id ID of the setting to delete
     * @return bool
     * @throws \Cake\Http\Exception\NotFoundException if no self registration settings were found for the provided ID
     */
    public function deleteSettings(string $id): bool
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $settings = $OrganizationSettings->getByProperty(
            SelfRegistrationBaseSettingsService::USER_SELF_REGISTRATION_SETTINGS_PROPERTY_NAME
        );
        if (is_null($settings) || $settings->get('id') !== $id) {
            throw new NotFoundException('Self registration setting not found.');
        }

        return $OrganizationSettings->deleteOrFail($settings);
    }
}
