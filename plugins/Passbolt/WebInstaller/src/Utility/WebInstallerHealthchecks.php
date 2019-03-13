<?php
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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Utility;

use App\Utility\Healthchecks;
use Cake\Core\Configure;
use Cake\Database\Exception as DatabaseException;

class WebInstallerHealthchecks
{
    /**
     * Run all databases health checks
     *
     * @param array $checks List of checks
     * @return array
     */
    public static function all($checks = [])
    {
        $checks = self::canWriteConfig($checks);

        return $checks;
    }

    /**
     * Check if application can write into the config folder
     *
     * @param array $checks List of checks
     * @return array
     */
    public static function canWriteConfig($checks = [])
    {
        $configFolderWritable = is_writable(CONFIG);

        $passboltConfigPath = CONFIG . 'passbolt.php';
        $passboltConfigFileIsWritable = file_exists($passboltConfigPath) ? is_writable($passboltConfigPath) : $configFolderWritable;
        $checks['webInstaller']['passboltConfigWritable'] = $passboltConfigFileIsWritable;

        if (Configure::read('passbolt.plugins.license')) {
            $passboltLicensePath = CONFIG . 'license';
            $passboltLicenseFileIsWritable = file_exists($passboltLicensePath) ? is_writable($passboltLicensePath) : $configFolderWritable;
            $checks['webInstaller']['passboltLicenseWritable'] = $passboltLicenseFileIsWritable;
        }

        $keyFolderWritable = is_writable(dirname(Configure::read('passbolt.gpg.serverKey.public')));

        $publicKeyPath = Configure::read('passbolt.gpg.serverKey.public');
        $publicKeyFileIsWritable = file_exists($publicKeyPath) ? is_writable($publicKeyPath) : $keyFolderWritable;
        $checks['webInstaller']['publicKeyWritable'] = $publicKeyFileIsWritable;

        $privateKeyPath = Configure::read('passbolt.gpg.serverKey.private');
        $privateKeyFileIsWritable = file_exists($privateKeyPath) ? is_writable($privateKeyPath) : $keyFolderWritable;
        $checks['webInstaller']['privateKeyWritable'] = $privateKeyFileIsWritable;

        return $checks;
    }
}
