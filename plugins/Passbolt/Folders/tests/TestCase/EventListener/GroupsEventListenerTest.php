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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\EventListener;

use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;

/**
 * \Passbolt\Folders\EventListener\GroupsEventListener test case
 *
 * @covers \Passbolt\Folders\EventListener\GroupsEventListener
 */
class GroupsEventListenerTest extends FoldersIntegrationTestCase
{
   /**
    * @var GroupsEventListener
    */
   //private $sut;

    public function setUp()
    {
        //$this->sut = new GroupsEventListener();

        parent::setUp();
    }

    public function testIncomplete()
    {
        $this->markTestIncomplete();
    }
}
