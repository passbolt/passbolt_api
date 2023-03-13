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
 * @since         3.12.0
 */

namespace Passbolt\Log\Test\TestCase\Strategy;

use Cake\Core\Configure;
use Cake\Log\Log;
use Passbolt\Log\Strategy\ActionLogsDefaultQueryStrategy;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

/**
 * Class ActionLogsDefaultQueryStrategyTest
 */
class ActionLogsDefaultQueryStrategyTest extends LogIntegrationTestCase
{
    /**
     * @var string
     */
    protected $fileName;

    public function setUp(): void
    {
        parent::setUp();
        $config = Log::getConfig('actionLogsOnFile');
        $this->fileName = 'testActionLogsErrorsOnlyQueryStrategy_' . rand(1, 1000);
        Log::drop('actionLogsOnFile');
        $config['enabled'] = true;
        $config['path'] = TMP . 'tests' . DS . 'logs' . DS;
        $config['file'] = $this->fileName;
        $config['strategy'] = ActionLogsDefaultQueryStrategy::class;
        Log::setConfig('actionLogsOnFile', $config);
    }

    public function tearDown(): void
    {
        if (file_exists($this->getLogFile())) {
            unlink($this->getLogFile());
        }
        unset($this->fileName);
        parent::tearDown();
    }

    protected function getLogFile(): string
    {
        return Log::getConfig('actionLogsOnFile')['path'] . $this->fileName . '.log';
    }

    public function testActionLogsErrorsOnlyQueryStrategy_On_File_Without_Error()
    {
        Configure::write('passbolt.healthcheck.error', true);
        $this->getJson('healthcheck/error.json');
        $this->assertInternalError('Internal Server Error');
        $this->assertSame(1, ActionLogFactory::count());

        $errorAction = ActionLogFactory::find()->firstOrFail();
        $actionJsonEncoded = json_encode($errorAction->jsonSerialize());
        $this->assertTrue(file_exists($this->getLogFile()));
        $content = file_get_contents($this->getLogFile());
        $this->assertTextContains($actionJsonEncoded, $content);
    }
}
