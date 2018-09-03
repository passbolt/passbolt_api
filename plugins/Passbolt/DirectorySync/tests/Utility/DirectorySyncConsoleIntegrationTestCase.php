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
namespace Passbolt\DirectorySync\Test\Utility;

use Cake\Core\Configure;
use Cake\TestSuite\ConsoleIntegrationTestCase;

abstract class DirectorySyncConsoleIntegrationTestCase extends ConsoleIntegrationTestCase
{
     public function setUp()
    {
        parent::setUp();
        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        Configure::write('passbolt.plugins.directorySync.test', true);
        Configure::write('passbolt.plugins.directorySync.defaultUser', 'admin@passbolt.com');
        Configure::write('passbolt.plugins.directorySync.defaultGroupAdminUser', 'ada@passbolt.com');
    }
}
