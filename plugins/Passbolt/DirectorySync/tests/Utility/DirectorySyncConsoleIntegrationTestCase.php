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

use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Test\Utility\Traits\DirectoryOrgSettingsTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\MockDirectoryTrait;
use Cake\TestSuite\ConsoleIntegrationTestTrait;

abstract class DirectorySyncConsoleIntegrationTestCase
{
    use ConsoleIntegrationTestTrait;
    use DirectoryOrgSettingsTrait;
    use MockDirectoryTrait;
    use UserAccessControlTrait;

    public $fixtures = [
        'app.Base/OrganizationSettings',
    ];

    public function setUp()
    {
        //parent::setUp();
        Configure::load('Passbolt/DirectorySync.config', 'default', true);
        Configure::write('passbolt.plugins.directorySync.test', true);
        $this->enableDirectoryIntegration();
    }
}
