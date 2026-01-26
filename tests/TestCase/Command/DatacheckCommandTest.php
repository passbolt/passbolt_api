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
 * @since         3.1.0
 */
namespace App\Test\TestCase\Command;

use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

/**
 * App\Command\DatacheckCommand Test Case
 *
 * @covers \App\Command\DatacheckCommand
 * @uses \App\Command\MigrateCommand
 */
class DatacheckCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use TruncateDirtyTables;

    /**
     * Basic help test
     */
    public function testDatacheckCommandHelp()
    {
        $this->exec('passbolt datacheck -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Re-validate the data of this installation.');
        $this->assertOutputContains('cake passbolt datacheck');
    }

    /**
     * Basic check with a bit of data.
     */
    public function testDatacheckCommandNoOptions()
    {
        UserFactory::make()
            ->with('Gpgkeys')
            ->withAvatar()
            ->user()
            ->persist();
        ResourceFactory::make()->persist();
        AuthenticationTokenFactory::make()->persist();
        FavoriteFactory::make()->persist();
        $this->exec('passbolt datacheck');
        $this->assertExitSuccess();
        $this->assertOutputContains('PASS');
        $this->assertOutputContains('FAIL');

        $checks = [
            'AuthenticationTokens',
            'Comments',
            'Favorites',
            'Gpgkeys',
            'Groups',
            'Profiles',
            'Resources',
            'Secrets',
            'Users',
        ];
        foreach ($checks as $check) {
            $this->assertOutputContains("Data integrity for $check.");
        }

        $this->assertOutputNotContains('Validation failed for secret');
    }

    public function testDatacheckCommand_Users_Username_Validation()
    {
        // Create two users with the same username
        $username = 'foo@passbolt.com';
        $duplicateUsernames = UserFactory::make(compact('username'), 2)->persist();

        // Create user with username not a valid email
        $username = 'foo';
        $noValidEmail = UserFactory::make(compact('username'))->persist();

        // Create two users with the same invalid username
        $username = 'bar';
        $duplicateInvalidUsernames = UserFactory::make(compact('username'), 2)->persist();

        $this->exec('passbolt datacheck');

        $this->assertOutputContains('[FAIL] Validation failed for user ' . $noValidEmail->id . '. {"username":{"email":"The username should be a valid email address."}}');
        $this->assertOutputContains('[FAIL] Validation failed for user ' . $duplicateUsernames[0]->id . '. {"username":{"uniqueUsername":"The username ' . $duplicateUsernames[0]->username . ' is a duplicate."}}');
        $this->assertOutputContains('[FAIL] Validation failed for user ' . $duplicateUsernames[1]->id . '. {"username":{"uniqueUsername":"The username ' . $duplicateUsernames[1]->username . ' is a duplicate."}}');
        $this->assertOutputContains('[FAIL] Validation failed for user ' . $duplicateInvalidUsernames[0]->id);
        $this->assertOutputContains('[FAIL] Validation failed for user ' . $duplicateInvalidUsernames[1]->id);
        $this->assertOutputContains('{"username":{"email":"The username should be a valid email address.","uniqueUsername":"The username ' . $duplicateInvalidUsernames[0]->username . ' is a duplicate."}}');
    }

    public function testDatacheckCommand_Secrets_Check()
    {
        SecretFactory::make()->withSecretRevision()->persist();
        SecretFactory::make()->withSecretRevision()->deleted()->persist();
        $this->exec('passbolt datacheck');
        $this->assertExitSuccess();
        $this->assertOutputContains('PASS');
        $this->assertOutputNotContains('FAIL');

        $this->assertOutputContains('Data integrity for Secrets.');
    }
}
