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
namespace Passbolt\WebInstaller\Test\Lib;

use Cake\Core\Configure;

trait ConfigurationTrait
{
    // Keep a copy of the original passbolt config.
    private $backupConfig = [];

    /*
     * Skip the test if the environment is production like:
     * - config/passbolt.php not writable
     * - config/license not writable
     */
    protected function skipTestIfNotWebInstallerFriendly()
    {
        if (!$this->isWebInstallerFriendly()) {
            $this->markTestSkipped('Config directory not writable, skipping test');
        }
    }

    /*
     * The environment is considered as production (and not friendly) like if :
     * - config/passbolt.php not writable
     * - or config/license not writable
     */
    protected function isWebInstallerFriendly()
    {
        $configFolderWritable = is_writable(CONFIG);

        $passboltConfigPath = CONFIG . 'passbolt.php';
        $passboltConfigFileIsWritable = file_exists($passboltConfigPath) ? is_writable($passboltConfigPath) : $configFolderWritable;
        if (!$passboltConfigFileIsWritable) {
            return false;
        }

        $passboltLicensePath = CONFIG . 'license';
        $passboltLicenseFileIsWritable = file_exists($passboltLicensePath) ? is_writable($passboltLicensePath) : $configFolderWritable;
        if (!$passboltLicenseFileIsWritable) {
            return false;
        }

        return true;
    }

    /*
     * Backup the passbolt configuration
     */
    protected function backupConfiguration()
    {
        // Backup the config and restore it after each test.
        $this->backupConfig = [];
        if (file_exists(CONFIG . 'passbolt.php')) {
            $this->backupConfig['passboltConfig'] = file_get_contents(CONFIG . 'passbolt.php');
        }
        if (file_exists(CONFIG . 'license')) {
            $this->backupConfig['license'] = file_get_contents(CONFIG . 'license');
        }
        $this->backupConfig['public'] = Configure::read('passbolt.gpg.serverKey.public');
        $this->backupConfig['private'] = Configure::read('passbolt.gpg.serverKey.private');

        // Write the keys
        Configure::write('passbolt.gpg.serverKey.public', TMP . 'tests' . DS . 'testkey.asc');
        Configure::write('passbolt.gpg.serverKey.private', TMP . 'tests' . DS . 'testkey_private.asc');
    }

    /*
     * Restore the passbolt backup configuration
     */
    protected function restoreConfiguration()
    {
        if (!$this->isWebInstallerFriendly()) {
            return;
        }
        if (file_exists(CONFIG . 'license')) {
            chmod(CONFIG . 'license', 0777);
        }
        if (file_exists(CONFIG . 'passbolt.php')) {
            chmod(CONFIG . 'passbolt.php', 0777);
        }
        if (isset($this->backupConfig['passboltConfig'])) {
            file_put_contents(CONFIG . 'passbolt.php', $this->backupConfig['passboltConfig']);
        } else {
            if (file_exists(CONFIG . 'passbolt.php')) {
                unlink(CONFIG . 'passbolt.php');
            }
        }
        if (isset($this->backupConfig['license'])) {
            file_put_contents(CONFIG . 'license', $this->backupConfig['license']);
        } else {
            if (file_exists(CONFIG . 'license')) {
                unlink(CONFIG . 'license');
            }
        }
        if (file_exists(TMP . 'tests' . DS . 'testkey.asc')) {
            unlink(TMP . 'tests' . DS . 'testkey.asc');
        }
        if (file_exists(TMP . 'tests' . DS . 'testkey_private.asc')) {
            unlink(TMP . 'tests' . DS . 'testkey_private.asc');
        }

        // Write the keys
        Configure::write('passbolt.gpg.serverKey.public', $this->backupConfig['public']);
        Configure::write('passbolt.gpg.serverKey.private', $this->backupConfig['private']);
    }
}
