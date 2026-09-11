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
namespace Passbolt\Folders\Test\TestCase\Model\Table\Folders;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Passbolt\Folders\Model\Table\FoldersTable;

/**
 * @covers \Passbolt\Folders\Model\Table\FoldersTable::validationDefault
 */
class FoldersTableValidationTest extends TestCase
{
    public FoldersTable $Folders;

    public function setUp(): void
    {
        parent::setUp();
        $this->Folders = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
    }

    public function tearDown(): void
    {
        unset($this->Folders);
        parent::tearDown();
    }

    public function testFoldersTableValidation_Name_InvisibleCharacters_Error(): void
    {
        $folder = $this->Folders->newEntity(
            ['name' => "shared\u{200B}folder"],
            ['accessibleFields' => ['name' => true]],
        );
        $this->assertArrayHasKey('name', $folder->getErrors());
        $this->assertSame(
            'The string should not contain invisible characters.',
            $folder->getErrors()['name']['noInvisibleCharacters'],
        );
    }
}
