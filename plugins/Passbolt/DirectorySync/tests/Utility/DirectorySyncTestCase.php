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
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertDirectoryTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertReportTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\MockDirectoryTrait;

abstract class DirectorySyncTestCase extends TestCase
{
    private $originalConfig;

    use MockDirectoryTrait;
    use AssertDirectoryTrait;
    use AssertReportTrait;

    /**
     * @var \Passbolt\DirectorySync\Actions\SyncAction
     */
    protected $action;

    public function setUp()
    {
        $this->originalConfig = Configure::read('passbolt.plugin.directorySync');
        $this->Groups = TableRegistry::get('Groups');
        Configure::write('passbolt.plugin.directorySync.test', true);
        parent::setUp();
    }

    public function tearDown()
    {
        if ($this->originalConfig !== null) {
            Configure::write('passbolt.plugin.directorySync', $this->originalConfig);
        }
        parent::tearDown();
    }

}