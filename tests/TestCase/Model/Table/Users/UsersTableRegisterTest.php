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
 * @since         3.11.0
 */

namespace App\Test\TestCase\Model\Table\Users;

use App\Error\Exception\ValidationException;
use App\Model\Validation\EmailValidationRule;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

/**
 * @group emailValidation
 */
class UsersTableRegisterTest extends TestCase
{
    use TruncateDirtyTables;

    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function testUsersTableRegister_Email_WithRegexp()
    {
        RoleFactory::make()->user()->persist();
        // Rule is an "a" followed by a b.
        $regex = '/a(b)/';
        Configure::write(EmailValidationRule::REGEX_CHECK_KEY, $regex);
        $profile = [
            'first_name' => '傅',
            'last_name' => '苹',
        ];

        $validUsernames = [
            'ab@test.test',
            'ab@test',
            'ab',
        ];
        foreach ($validUsernames as $username) {
            $this->Users->register(compact('username', 'profile'));
        }
        $this->assertSame(count($validUsernames), UserFactory::count());
    }

    public function testUsersTableRegister_ValidEmail_WithNotMatchingRegexpShouldFail()
    {
        RoleFactory::make()->user()->persist();
        // Rule is an "a" followed by a b.
        $regex = '/a(b)/';
        Configure::write(EmailValidationRule::REGEX_CHECK_KEY, $regex);
        $profile = [
            'first_name' => '傅',
            'last_name' => '苹',
        ];

        $username = 'b@test.test';
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Could not validate user data.');
        $this->Users->register(compact('username', 'profile'));
    }
}
