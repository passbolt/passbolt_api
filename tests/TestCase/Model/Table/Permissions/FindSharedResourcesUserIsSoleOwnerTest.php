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

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Test\Lib\AppTestCase;

class FindSharedResourcesUserIsSoleOwnerTest extends AppTestCase
{
    public $fixtures = ['app.permissions'];

    public function testFindResourceUserDoesNotOwnAnything()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testFindResouceUserOwnsAndIsShared()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testFindResourceUserOwnsAndIsNotShared()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
