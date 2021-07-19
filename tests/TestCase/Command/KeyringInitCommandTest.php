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

use App\Command\KeyringInitCommand;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Core\Configure;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class KeyringInitCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;

    /**
     * @var string
     */
    public $key;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        KeyringInitCommand::$isUserRoot = false;
        $this->key = Configure::read('passbolt.gpg.serverKey.private');
    }

    /**
     * Basic help test
     */
    public function testKeyringInitCommandHelp()
    {
        $this->exec('passbolt keyring_init -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('GnuPG Keyring init shell for the passbolt application.');
        $this->assertOutputContains('cake passbolt keyring_init');
    }

    /**
     * @Given I am root
     * @When I run "passbolt keyring_init"
     * @Then the command cannot be run.
     */
    public function testKeyringInitCommandAsRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser(KeyringInitCommand::class);
    }

    /**
     * @Given I am not root
     * @When I run "passbolt keyring_init"
     * @Then it is all O.K..
     */
    public function testKeyringInitCommandWithCorrectKey()
    {
        $this->exec('passbolt keyring_init');
        $this->assertExitSuccess();
        $this->assertOutputContains('Importing ' . $this->key);
        $this->assertOutputContains('Keyring init OK');
    }

    /**
     * Init an non existing key
     */
    public function testKeyringInitCommandWithNonCorrectKey()
    {
        $wrongFile = 'Blah';
        Configure::write('passbolt.gpg.serverKey.private', $wrongFile);
        $this->exec('passbolt keyring_init');
        $this->assertExitError();
        $this->assertOutputContains("The file does not exist: $wrongFile");
        Configure::write('passbolt.gpg.serverKey.private', $this->key);
    }
}
