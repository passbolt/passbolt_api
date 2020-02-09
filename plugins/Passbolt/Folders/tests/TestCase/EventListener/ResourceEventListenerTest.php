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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Test\TestCase\EventListener;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\Folders\EventListener\ResourcesEventListener;

/**
 * Class ResourceEventListenerTest
 * @covers \Passbolt\Folders\EventListener\ResourcesEventListener
 */
class ResourceEventListenerTest extends AppIntegrationTestCase
{
    /**
     * @var ResourcesEventListener
     */
    private $sut;

    public function setUp()
    {
        $this->sut = new ResourcesEventListener();

        parent::setUp();
    }

    public function testThatFolderParentIsCreated()
    {
        $this->markTestIncomplete();
    }
}
