<?php

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\Folders\EventListener\ResourceCreateEventListener;

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

class ResourceCreateEventListenerTest extends AppIntegrationTestCase
{
    /**
     * @var ResourceCreateEventListener
     */
    private $sut;

    public function setUp()
    {
        $this->sut = new ResourceCreateEventListener();

        parent::setUp();
    }

    public function testThatFolderParentIsCreated()
    {

    }
}
