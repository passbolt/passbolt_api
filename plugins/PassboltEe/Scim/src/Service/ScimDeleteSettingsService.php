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
 * @since         4.1.0
 */
namespace Passbolt\Scim\Service;

use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class ScimDeleteSettingsService
{
    use EventDispatcherTrait;

    /**
     * Delete the SCIM settings in the DB
     * If not found, return a NotFoundException
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param string $id ID of the setting to delete
     * @return bool
     * @throws \Cake\Http\Exception\NotFoundException if no SCIM settings were found for the provided ID
     */
    public function deleteSettings(UserAccessControl $uac, string $id): bool
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $settings = $OrganizationSettings->getByProperty(
            ScimBaseSettingsService::SCIM_SETTINGS_PROPERTY_NAME
        );
        if (is_null($settings) || $settings->get('id') !== $id) {
            throw new NotFoundException('The SCIM setting does not exist.');
        }

        $result = $OrganizationSettings->deleteOrFail($settings);
        $eventData = [
            'modified_by' => $uac->getId(),
        ];

        $this->dispatchEvent(
            ScimSetSettingsService::SCIM_SETTINGS_UPDATE_EVENT_NAME,
            $eventData
        );

        return $result;
    }
}
