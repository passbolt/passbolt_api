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
 * @since         5.15.0
 */
namespace App\Test\TestCase\Model\Table\Groups;

use App\Model\Table\GroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Model\Table\GroupsTable::validationDefault
 */
class GroupsTableValidationTest extends TestCase
{
    public GroupsTable $Groups;

    public function setUp(): void
    {
        parent::setUp();
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
    }

    public function tearDown(): void
    {
        unset($this->Groups);
        parent::tearDown();
    }

    public function testGroupsTableValidation_Name_InvisibleCharacters_Error(): void
    {
        $group = $this->Groups->newEntity(
            ['name' => "sales\u{200B}team"],
            ['accessibleFields' => ['name' => true]],
        );
        $this->assertArrayHasKey('name', $group->getErrors());
        $this->assertSame(
            'The string should not contain invisible characters.',
            $group->getErrors()['name']['noInvisibleCharacters'],
        );
    }
}
