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
 * @since         5.8.0
 */
namespace Passbolt\SecretRevisions\Test\TestCase\Command;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

/**
 * @covers \Passbolt\SecretRevisions\Command\PopulateSecretRevisionsForExistingSecretsCommand
 */
class PopulateSecretRevisionsForExistingSecretsCommandTest extends AppIntegrationTestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->mockProcessUserService('www-data');
        $this->enableFeaturePlugin(SecretRevisionsPlugin::class);
    }

    public function testPopulateSecretRevisionsForExistingSecretsCommand_Help(): void
    {
        $this->exec('passbolt populate_secret_revisions_for_existing_secrets -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Populate secret revisions for existing secrets.');
    }

    public function testPopulateSecretRevisionsForExistingSecretsCommand_Success(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->with('Secrets', ['user_id' => $user->id])
            ->persist();

        $this->assertSame(0, SecretRevisionFactory::count());

        $this->exec('passbolt populate_secret_revisions_for_existing_secrets');

        $this->assertExitSuccess();
        $this->assertOutputContains('Populating secret revisions for existing secrets...');
        $this->assertOutputContains('Secret revisions populated successfully.');
        $this->assertSame(1, SecretRevisionFactory::count());
    }
}
