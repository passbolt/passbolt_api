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
 */

namespace Passbolt\Log\Test\TestCase\Model\Actions;

use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Datasource\ModelAwareTrait;

/**
 * Class FindOrCreateTest
 *
 * @property \Passbolt\Log\Model\Table\ActionsTable $Actions
 */
class FindOrCreateTest extends AppTestCase
{
    use ModelAwareTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadModel('Passbolt/Log.Actions');
    }

    /**
     * Test FindOrCreateAction function.
     */
    public function testLogFindOrCreateDoNotCreateDuplicates()
    {
        // Delete cache
        $this->Actions->clearCache();

        $this->Actions->findOrCreateAction(UuidFactory::uuid('Test.test'), 'Test.test');
        $allActions = $this->Actions->find()->all();
        $this->assertEquals(count($allActions), 1);

        $this->Actions->findOrCreateAction(UuidFactory::uuid('Test.test'), 'Test.test');
        $allActions = $this->Actions->find()->all();
        $this->assertEquals(count($allActions), 1);
    }
}
