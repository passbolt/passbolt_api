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
use App\Test\Factory\UserFactory;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Command\MigrateCommand Test Case
 *
 * @uses \App\Command\MigrateCommand
 */
class DatacheckCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
    }

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
    }
}
