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
namespace Passbolt\DirectorySync\Utility;

use Cake\Core\Configure;
use Passbolt\DirectorySync\Test\Utility\TestDirectory;

/**
 * Directory factory class
 *
 * @package App\Utility
 */
class DirectoryFactory
{
    /**
     * Get directory factory.
     *
     * @param \Passbolt\DirectorySync\Utility\DirectoryOrgSettings $settings The directory settings
     * @return mixed
     * @throws \Exception
     */
    public static function get(?DirectoryOrgSettings $settings = null)
    {
        if (Configure::read('passbolt.plugins.directorySync.test')) {
            return new TestDirectory();
        }
        if ($settings->isEnabled()) {
            return new LdapDirectory($settings);
        }
        throw new \Exception('Directory sync plugin is not enabled.');
    }
}
