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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Controller;

use Cake\Network\Exception\ForbiddenException;
use Migrations\Migrations;

class InstallationController extends WebInstallerController
{

    /**
     * Initialize.
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['previous'] = 'install/options';
        $this->stepInfo['next_create_user'] = 'install/account_creation';
        $this->stepInfo['next_complete'] = 'install/complete';
        $this->stepInfo['template'] = 'Pages/email';
        $this->stepInfo['install'] = 'install/installation/do_install';
    }

    /**
     * Index.
     */
    function index() {
        $this->_writeConfigurationFile();
        $this->set(['redirectUrl' => $this->_getNextStepUrl()]);
        $this->render('Pages/installation');
    }

    /**
     * Get next step url.
     * @return mixed
     */
    protected function _getNextStepUrl() {
        $session = $this->request->getSession();
        $hasExistingAdmin = $session->read(self::CONFIG_KEY . '.hasExistingAdmin');
        if (!$hasExistingAdmin) {
            return $this->stepInfo['next_create_user'];
        }

        return $this->stepInfo['next_complete'];
    }

    /**
     * Install passbolt
     * This function will be called through ajax.
     */
    function install() {
        $res = $this->_installDb();
        echo $res ? '1' : '0';
        // TODO: remove die and do something more elegant.
        die();
    }

    /**
     * Complete installation
     */
    function complete() {
        $session = $this->request->getSession();
        $hasExistingAdmin = $session->read(self::CONFIG_KEY . '.hasExistingAdmin');
        if (!$hasExistingAdmin) {
            $session = $this->request->getSession();
            $token = $session->read(self::CONFIG_KEY . '.user.token');
            $this->set(['redirectUrl' => 'setup/install/' . $token['user_id'] . '/' . $token['token']]);
        } else {
            $this->set(['redirectUrl' => '/']);
        }

        // Delete session info.
        $session->delete(self::CONFIG_KEY);

        $this->render('Pages/complete');
    }

    /**
     * Write passbolt configuration file.
     */
    private function _writeConfigurationFile() {
        $session = $this->request->getSession();
        $config = $session->read('Passbolt.Config');

        // Sanitize output before writing the file.
        foreach($config as $key => $itemConfig) {
            if (is_array($itemConfig)) {
                $config[$key] = $this->_sanitizeEntries($itemConfig);
            } elseif (is_string($itemConfig)) {
                $config[$key] = $this->_sanitizeEntry($itemConfig);
            }
        }

        $this->set(['config' => $config]);
        $configView = $this->createView();
        $contents = $configView->render('/Config/passbolt', 'ajax');
        $contents = "<?php\n$contents";
        file_put_contents(CONFIG . 'passbolt.php', $contents);
    }

    /**
     * Sanitize all entries of a configuration array.
     * Sanitize = we escape the characters ' and \
     * Works on a single dimension array only.
     * @param $entries
     * @return mixed
     */
    private function _sanitizeEntries($entries) {
        foreach($entries as $key => $entry) {
            if (is_string($entry)) {
                $entries[$key] = $this->_sanitizeEntry($entry);
            }
        }

        return $entries;
    }

    /**
     * Sanitize an entry before writing it in a file.
     * @param $entry
     * @return mixed
     */
    private function _sanitizeEntry($entry) {
        $entry = addslashes($entry);
        return $entry;
    }

    /**
     * Install database.
     */
    private function _installDb() {
        $migrations = new Migrations();
        $migrated = $migrations->migrate();

        return $migrated;
    }
}