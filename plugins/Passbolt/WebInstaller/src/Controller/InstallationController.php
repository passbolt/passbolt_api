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

use Cake\Controller\Controller;
use Migrations\Migrations;

class InstallationController extends Controller
{
    /**
     * Index
     */
    function index() {
        $this->request->getSession()->write('Passbolt.Config.database.name', 'passbolt7');
        $this->_writeConfigurationFile();

        $this->render('Pages/installation');
    }

    /**
     * Install passbolt
     * This function will be called through ajax.
     */
    function install() {
        // TODO: make sure these functions can't be called if passbolt is installed.
        $res = $this->_installDb();
        echo $res ? '1' : '0';
        die();
    }

    /**
     * Write passbolt configuration file.
     */
    private function _writeConfigurationFile() {
        $session = $this->request->getSession();
        $config = $session->read('Passbolt.Config');

        $this->set(['config' => $config]);
        $configView = $this->createView();
        $contents = $configView->render('/Config/passbolt', 'ajax');
        $contents = "<?php\n$contents";
        file_put_contents(CONFIG . 'passbolt.php', $contents);
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