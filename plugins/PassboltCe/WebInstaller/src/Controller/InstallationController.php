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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Controller;

use Passbolt\WebInstaller\Service\WebInstallerChangeConfigFolderPermissionService;

class InstallationController extends WebInstallerController
{
    /**
     * Initialize.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->stepInfo['previous'] = $this->getPrevious();
        $this->stepInfo['template'] = 'Pages/email';
        $this->stepInfo['install'] = '/install/installation/do_install';
    }

    /**
     * Index.
     *
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
     *
     * @param \Passbolt\WebInstaller\Service\WebInstallerChangeConfigFolderPermissionService $configFolderPermissionService Service handling the config directory permissions
     * @return void
     */
    public function install(WebInstallerChangeConfigFolderPermissionService $configFolderPermissionService)
    {
        $this->webInstaller->install($configFolderPermissionService);
        $this->set('data', $this->webInstaller->getSettings('user'));
        $this->viewBuilder()->setLayout('ajax');
        $this->render('Pages/installation_result');
    }

    /**
     * Define the previous step
     *
     * @return string
     */
    protected function getPrevious(): string
    {
        if (!$this->webInstaller->getSettings('hasAdmin')) {
            return '/install/account_creation';
        } elseif (!$this->webInstaller->getSettings('hasSmtpSettings')) {
            return 'install/email';
        } else {
            return 'install/options';
        }
    }
}
