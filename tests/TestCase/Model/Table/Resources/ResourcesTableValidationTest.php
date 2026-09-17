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
namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Table\ResourcesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Model\Table\ResourcesTable::validationDefault
 */
class ResourcesTableValidationTest extends TestCase
{
    public ResourcesTable $Resources;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function tearDown(): void
    {
        unset($this->Resources);
        parent::tearDown();
    }

    public function testResourcesTableValidation_Name_InvisibleCharacters_Error(): void
    {
        $resource = $this->Resources->newEntity(
            ['name' => "AWS\u{200B}root"],
            ['accessibleFields' => ['name' => true]],
        );
        $this->assertArrayHasKey('name', $resource->getErrors());
        $this->assertSame(
            'The string should not contain invisible characters.',
            $resource->getErrors()['name']['noInvisibleCharacters'],
        );
    }
}
