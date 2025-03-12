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
 * @since         4.2.0
 */

namespace App\Test\TestCase\Model\Table\Users;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;

class FindNotDisabledTest extends AppTestCase
{
    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        RoleFactory::make()->guest()->persist();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->Users);
    }

    public function testFindNotDisabledSuccess()
    {
        UserFactory::make()->user()->disabled()->created(\Cake\I18n\DateTime::now()->subDays(1))->persist();
        UserFactory::make()->user()->notDisabled()->created(\Cake\I18n\DateTime::now()->subDays(1))->persist();
        UserFactory::make()->user()->willDisable()->created(\Cake\I18n\DateTime::now()->subDays(2))->persist();

        $result = $this->Users->find('notDisabled')->orderBy(['created' => 'DESC'])->all()->toArray();
        $this->assertEquals(2, count($result));
        $this->assertNull($result[0]['disabled']);
        $this->assertNotNull($result[1]['disabled']);
    }
}
