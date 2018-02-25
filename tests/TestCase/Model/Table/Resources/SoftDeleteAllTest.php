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

namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SoftDeleteAllTest extends AppTestCase
{
    use FormatValidationTrait;

    public $Resources;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/favorites', 'app.Base/groups_users',
        'app.Base/resources', 'app.Base/permissions'
    ];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::get('Resources', $config);
    }

    public function tearDown()
    {
        unset($this->Resources);
        parent::tearDown();
    }

    public function testSoftDeleteAllSuccess()
    {
        $this->markTestIncomplete();
    }
}
