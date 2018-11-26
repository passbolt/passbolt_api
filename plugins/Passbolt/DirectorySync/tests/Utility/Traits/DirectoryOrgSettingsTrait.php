<?php
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
 * @since         2.6.0
 */
namespace Passbolt\DirectorySync\Test\Utility\Traits;

use App\Model\Entity\Role;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

trait DirectoryOrgSettingsTrait
{
    /**
     * Enable the directory integration.
     *
     * @return void
     */
    public function enableDirectoryIntegration()
    {
        $settings = [
            'defaultUser' => 'admin@passbolt.com',
            'defaultGroupAdminUser' => 'ada@passbolt.com',
            'jobs' => [
                'users' => [
                    'create' => true,
                    'delete' => true
                ],
                'groups' => [
                    'create' => true,
                    'delete' => true,
                    'update' => true
                ]
            ],
        ];
        $uac = $this->mockUserAccessControl('admin', Role::ADMIN);
        $this->directoryOrgSettings = new DirectoryOrgSettings($settings);
        $this->directoryOrgSettings->save($uac);
    }

    /**
     * Disable the directory integration.
     *
     * @return void
     */
    public function disableDirectoryIntegration()
    {
        $settings = [];
        $uac = $this->mockUserAccessControl('admin', Role::ADMIN);
        $this->directoryOrgSettings = new DirectoryOrgSettings($settings);
        $this->directoryOrgSettings->save($uac);
    }

    /**
     * Set the default group admin user.
     *
     * @param string $userAlias The user to set as default group admin user.
     * @return void
     */
    public function setDefaultGroupAdminUser($username)
    {
        $this->updateDirectoryOrgSettings('defaultGroupAdminUser', $username);
    }

    /**
     * Disable a sync operation
     *
     * @param string $objectType The object type to disable the sync for
     * @param string $operation The operation type to disable the sync for
     * @return void
     */
    public function disableSyncOperation($objectType, $operation)
    {
        $this->updateDirectoryOrgSettings("jobs.$objectType.$operation", false);
    }

    /**
     * Udpate the directory organization settings.
     *
     * @param string $path The path to insert at.
     * @param array|null $values The values to insert.
     * @return void
     */
    private function updateDirectoryOrgSettings($path, $value)
    {
        $uac = $this->mockUserAccessControl('admin', Role::ADMIN);
        $settings = $this->directoryOrgSettings->toArray();
        $settings = Hash::insert($settings, $path, $value);
        $this->directoryOrgSettings->set($settings);
        $this->directoryOrgSettings->save($uac);
    }
}
