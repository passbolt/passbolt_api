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
 * @since         4.0.0
 */
namespace Passbolt\DirectorySync\Service\DirectorySettings;

use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class UpdateDirectorySettingsService
{
    use LocatorAwareTrait;

    /**
     * Update old directory settings to new format
     *
     * @return void
     * @throws \UnexpectedValueException If directory settings stored are invalid and cannot be parsed
     */
    public function updateSettings(): void
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = $this->fetchTable('OrganizationSettings');
        /** @var \App\Model\Entity\OrganizationSetting|null $directorySyncSettings */
        $directorySyncSettings = $OrganizationSettings
            ->find()
            ->where([
                'property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY,
            ])->first();

        if (!$directorySyncSettings) {
            return;
        }

        $value = json_decode($directorySyncSettings['value'], true);
        if (!$value || !is_array($value)) {
            throw new \UnexpectedValueException(
                __('Directory Settings are invalid. Please check your config and try again.')
            );
        }
        // set the new key for list of servers/hosts in the new library
        if (is_array($value['ldap']['domains'])) {
            foreach ($value['ldap']['domains'] as $domain => $config) {
                if (array_key_exists('servers', $config)) {
                    $value['ldap']['domains'][$domain]['hosts'] = $config['servers'];
                    unset($value['ldap']['domains'][$domain]['servers']);
                }
            }
        }

        // keep compatibility with old library default class value
        if (empty($value['groupObjectClass'])) {
            $value['groupObjectClass'] = 'groupOfNames';
        }
        $value['enabled'] = false;

        $directorySyncSettings->set('value', json_encode($value));
        $result = $OrganizationSettings
            ->save($directorySyncSettings);
        if (!$result) {
            throw new \RuntimeException(__('New directory settings could not be saved.'));
        }
    }
}
