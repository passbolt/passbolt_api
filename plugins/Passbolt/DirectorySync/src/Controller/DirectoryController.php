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

namespace Passbolt\DirectorySync\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\ServiceUnavailableException;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

abstract class DirectoryController extends AppController
{
    /**
     * The directory org settings
     *
     * @var DirectoryOrgSettings
     */
    protected $directoryOrgSettings = null;

    /**
     * Initialization hook method.
     * Used to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize()
    {
        $isLdapLoaded = extension_loaded('ldap');
        if (!$isLdapLoaded) {
            throw new InternalErrorException('The PHP ldap extension is not loaded.');
        }

        $this->directoryOrgSettings = DirectoryOrgSettings::get();
        parent::initialize();
    }

    /**
     * Assert the directory is configured.
     *
     * @return void
     */
    protected function assertDirectoryEnabled()
    {
        if (!$this->directoryOrgSettings->isEnabled()) {
            throw new ServiceUnavailableException('Directory sync plugin is not enabled.');
        }
    }
}
