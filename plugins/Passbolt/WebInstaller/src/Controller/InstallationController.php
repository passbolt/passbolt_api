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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Controller;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use Migrations\Migrations;
use Passbolt\WebInstaller\Utility\DatabaseConnection;
use Passbolt\WebInstaller\Utility\Gpg;

class InstallationController extends WebInstallerController
{
    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['previous'] = 'install/options';
        $this->stepInfo['template'] = 'Pages/email';
        $this->stepInfo['install'] = 'install/installation/do_install';
    }

    /**
     * Index.
     * @return void
     */
    public function index()
    {
        $createFirstUser = $this->webInstaller->getSettings('first_user');
        $this->set('createFirstUser', !empty($createFirstUser));
        $this->render('Pages/installation');
    }

    /**
     * Install passbolt.
     * @return void
     */
    public function install()
    {
        $this->webInstaller->install();
        $this->set('data', $this->webInstaller->getSettings('user'));
        $this->viewBuilder()->setLayout('ajax');
        $this->render('Pages/installation_result');
    }
}
