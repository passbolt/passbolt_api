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
namespace App\Test\TestCase\Model\Table\Profiles;

use App\Model\Table\ProfilesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Model\Table\ProfilesTable::validationDefault
 */
class ProfilesTableValidationTest extends TestCase
{
    public ProfilesTable $Profiles;

    public function setUp(): void
    {
        parent::setUp();
        $this->Profiles = TableRegistry::getTableLocator()->get('Profiles');
    }

    public function tearDown(): void
    {
        unset($this->Profiles);
        parent::tearDown();
    }

    public function testProfilesTableValidation_Name_InvisibleCharacters_Error(): void
    {
        $profile = $this->Profiles->newEntity(
            [
                'first_name' => "Ada\u{200B}",
                'last_name' => "Love\u{200B}lace",
            ],
            ['accessibleFields' => ['first_name' => true, 'last_name' => true]],
        );
        $errors = $profile->getErrors();
        $this->assertSame(
            'The string should not contain invisible characters.',
            $errors['first_name']['noInvisibleCharacters'],
        );
        $this->assertSame(
            'The string should not contain invisible characters.',
            $errors['last_name']['noInvisibleCharacters'],
        );
    }
}
