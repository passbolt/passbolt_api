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
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;

/**
 * @covers \Passbolt\SecretRevisions\Command\PopulateCreatedByAndModifiedByInSecretsCommand
 */
class PopulateCreatedByAndModifiedByInSecretsCommandTest extends AppIntegrationTestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->mockProcessUserService('www-data');
        $this->enableFeaturePlugin(SecretRevisionsPlugin::class);
    }

    public function testPopulateCreatedByAndModifiedByInSecretsCommand_Help(): void
    {
        $this->exec('passbolt populate_created_by_and_modified_by_in_secrets -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Populate created_by and modified_by fields in secrets table.');
    }

    public function testPopulateCreatedByAndModifiedByInSecretsCommand_Success(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->with('Secrets', [
                'user_id' => $user->id,
                'created_by' => null,
                'modified_by' => null,
            ])
            ->persist();
        $secret = $resource->secrets[0];

        $this->assertNull($secret->created_by);
        $this->assertNull($secret->modified_by);

        $this->exec('passbolt populate_created_by_and_modified_by_in_secrets');

        $this->assertExitSuccess();
        $this->assertOutputContains('Populating created_by and modified_by fields in secrets table...');
        $this->assertOutputContains('Secrets table populated successfully.');

        $updatedSecret = SecretFactory::get($secret->id);
        $this->assertSame($user->id, $updatedSecret->created_by);
        $this->assertSame($user->id, $updatedSecret->modified_by);
    }
}
