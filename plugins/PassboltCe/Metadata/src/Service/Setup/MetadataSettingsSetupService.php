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
 * @since         5.4.0
 */
namespace Passbolt\Metadata\Service\Setup;

use App\Model\Entity\Role;
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Model\Dto\Setup\MetadataSettingsSetupDto;

class MetadataSettingsSetupService
{
    use LocatorAwareTrait;

    /**
     * @return \Passbolt\Metadata\Model\Dto\Setup\MetadataSettingsSetupDto
     */
    public function get(): MetadataSettingsSetupDto
    {
        // Set flag default value to true
        $flag = Configure::read('passbolt.plugins.metadata.enableForNewInstances', true);
        if (!$flag) {
            return new MetadataSettingsSetupDto(false);
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        /** @var array<\App\Model\Entity\User> $users */
        $users = $usersTable->find()->all()->toArray();

        if (count($users) !== 1) {
            return new MetadataSettingsSetupDto(false);
        }

        // If there's only one user, and they are active, and their role is admin,
        // then we consider it as the fresh install and consider for enablement of encrypted metadata
        $soleUser = $users[0];
        $enablement = $soleUser->active && $soleUser->role_id === $usersTable->Roles->getIdByName(Role::ADMIN);

        return new MetadataSettingsSetupDto($enablement);
    }
}
