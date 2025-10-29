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
 * @since         5.7.0
 */

namespace App\Test\TestCase\Service\Secrets;

use App\Service\Secrets\PopulateCreatedByAndModifiedByInSecretsService;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Factory\EntitiesHistoryFactory;
use Passbolt\Log\Test\Factory\SecretsHistoryFactory;

/**
 * @covers \App\Service\Secrets\PopulateCreatedByAndModifiedByInSecretsService
 */
class PopulateCreatedByAndModifiedByInSecretsServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public PopulateCreatedByAndModifiedByInSecretsService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PopulateCreatedByAndModifiedByInSecretsService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    /**
     * Private resource, updated twice (1 metadata, 1 secret update).
     *
     * @return void
     */
    public function testPopulateCreatedByAndModifiedByInSecretsService_PrivateResourceUpdatedTwice(): void
    {
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->with('Secrets', [
                'user_id' => $user->id,
                // set null for testing
                'created_by' => null,
                'modified_by' => null,
            ])
            ->persist();
        // secret history
        $secret = $resource->secrets[0];
        $secretsHistory = SecretsHistoryFactory::make(['id' => $secret->id])
            ->with('Users', $user)
            ->with('Resources', $resource)
            ->persist();
        // action log
        $actionLog = ActionLogFactory::make()
            ->setActionId('ResourcesUpdate.update')
            ->userId($user->id);
        // entities history
        EntitiesHistoryFactory::make(['created' => DateTime::now()])
            ->withActionLog($actionLog)
            ->withSecretsHistory($secretsHistory)
            ->update()
            ->persist();

        $this->assertNull($secret->created_by);
        $this->assertNull($secret->modified_by);

        $this->service->populate();

        $expectedSecret = SecretFactory::get($secret->id);
        $this->assertSame($user->id, $expectedSecret->created_by);
        $this->assertSame($user->id, $expectedSecret->modified_by);
    }

    /**
     * Shared once with 1 user, and shared user updated it once.
     *
     * @return void
     */
    public function testPopulateCreatedByAndModifiedByInSecretsService_SharedResourceWithOneUserAndSecretUpdatedOnce(): void
    {
        $ada = UserFactory::make()->withValidGpgKey()->persist();
        $betty = UserFactory::make()->withValidGpgKey()->persist();
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($ada)
            ->with('Secrets', [
                [
                    'user_id' => $ada->id,
                    // set null for testing
                    'created_by' => null,
                    'modified_by' => null,
                ],
                [
                    'user_id' => $betty->id,
                    // set null for testing
                    'created_by' => null,
                    'modified_by' => null,
                ],
            ])
            ->persist();
        PermissionFactory::make()->acoResource($resource)->aroUser($betty)->typeOwner()->persist();
        // secret history
        $secretOfAda = $resource->secrets[0];
        $secretOfBetty = $resource->secrets[1];
        $secretsHistoryOfAda = SecretsHistoryFactory::make(['id' => $secretOfAda->id])
            ->with('Users', $ada)
            ->with('Resources', $resource)
            ->persist();
        $secretsHistoryOfBetty = SecretsHistoryFactory::make(['id' => $secretOfBetty->id])
            ->with('Users', $betty)
            ->with('Resources', $resource)
            ->persist();
        // action logs
        $actionLogAdaUpdatesResource = ActionLogFactory::make()
            ->setActionId('ResourcesUpdate.update')
            ->userId($ada->id);
        $actionLogAdaSharesResource = ActionLogFactory::make()
            ->setActionId('Share.share')
            ->userId($ada->id);
        $actionLogBettyUpdatesAdaResource = ActionLogFactory::make()
            ->setActionId('ResourcesUpdate.update')
            ->userId($betty->id);
        $actionLogBettyUpdatesBettyResource = ActionLogFactory::make()
            ->setActionId('ResourcesUpdate.update')
            ->userId($betty->id);
        // entities history entries
        EntitiesHistoryFactory::make(['created' => DateTime::now()])
            ->withActionLog($actionLogAdaUpdatesResource)
            ->withSecretsHistory($secretsHistoryOfAda)
            ->update()
            ->persist();
        EntitiesHistoryFactory::make(['created' => DateTime::now()->addMinutes(1)])
            ->withActionLog($actionLogAdaSharesResource)
            ->withSecretsHistory($secretsHistoryOfBetty)
            ->create()
            ->persist();
        EntitiesHistoryFactory::make(['created' => DateTime::now()->addMinutes(3)])
            ->withActionLog($actionLogBettyUpdatesAdaResource)
            ->withSecretsHistory($secretsHistoryOfAda)
            ->update()
            ->persist();
        EntitiesHistoryFactory::make(['created' => DateTime::now()->addMinutes(5)])
            ->withActionLog($actionLogBettyUpdatesBettyResource)
            ->withSecretsHistory($secretsHistoryOfBetty)
            ->update()
            ->persist();

        $this->service->populate();

        $expectedSecretOfAda = SecretFactory::get($secretOfAda->id);
        $this->assertSame($ada->id, $expectedSecretOfAda->created_by);
        $this->assertSame($betty->id, $expectedSecretOfAda->modified_by);
        $expectedSecretOfBetty = SecretFactory::get($secretOfBetty->id);
        $this->assertSame($ada->id, $expectedSecretOfBetty->created_by);
        $this->assertSame($betty->id, $expectedSecretOfBetty->modified_by);
    }

    /**
     * Shared once with 2 users, never updated.
     *
     * @return void
     */
    public function testPopulateCreatedByAndModifiedByInSecretsService_SharedResourceWithAnotherTwoUsers(): void
    {
        $ada = UserFactory::make()->withValidGpgKey()->persist();
        $betty = UserFactory::make()->withValidGpgKey()->persist();
        $john = UserFactory::make()->withValidGpgKey()->persist();
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($ada)
            ->with('Secrets', [
                [
                    'user_id' => $ada->id,
                    // set null for testing
                    'created_by' => null,
                    'modified_by' => null,
                ],
                [
                    'user_id' => $betty->id,
                    // set null for testing
                    'created_by' => null,
                    'modified_by' => null,
                ],
                [
                    'user_id' => $john->id,
                    // set null for testing
                    'created_by' => null,
                    'modified_by' => null,
                ],
            ])
            ->persist();
        PermissionFactory::make()->acoResource($resource)->aroUser($betty)->typeOwner()->persist();
        PermissionFactory::make()->acoResource($resource)->aroUser($john)->typeOwner()->persist();
        // secret history
        $secretOfAda = $resource->secrets[0];
        $secretOfBetty = $resource->secrets[1];
        $secretOfJohn = $resource->secrets[2];
        $secretsHistoryOfBetty = SecretsHistoryFactory::make(['id' => $secretOfBetty->id])
            ->with('Users', $betty)
            ->with('Resources', $resource)
            ->persist();
        $secretsHistoryOfJohn = SecretsHistoryFactory::make(['id' => $secretOfJohn->id])
            ->with('Users', $john)
            ->with('Resources', $resource)
            ->persist();
        // action logs
        $actionLogAdaSharesResourceWithBetty = ActionLogFactory::make()
            ->setActionId('Share.share')
            ->userId($ada->id);
        $actionLogAdaSharesResourceWithJohn = ActionLogFactory::make()
            ->setActionId('Share.share')
            ->userId($ada->id);
        // entities history entries
        EntitiesHistoryFactory::make(['created' => DateTime::now()])
            ->withActionLog($actionLogAdaSharesResourceWithBetty)
            ->withSecretsHistory($secretsHistoryOfBetty)
            ->update()
            ->persist();
        EntitiesHistoryFactory::make(['created' => DateTime::now()->addMinutes(2)])
            ->withActionLog($actionLogAdaSharesResourceWithJohn)
            ->withSecretsHistory($secretsHistoryOfJohn)
            ->create()
            ->persist();

        $this->service->populate();

        $expectedSecretOfAda = SecretFactory::get($secretOfAda->id);
        $this->assertSame($ada->id, $expectedSecretOfAda->created_by);
        $this->assertSame($ada->id, $expectedSecretOfAda->modified_by);
        $expectedSecretOfBetty = SecretFactory::get($secretOfBetty->id);
        $this->assertSame($ada->id, $expectedSecretOfBetty->created_by);
        $this->assertSame($ada->id, $expectedSecretOfBetty->modified_by);
        $expectedSecretOfJohn = SecretFactory::get($secretOfJohn->id);
        $this->assertSame($ada->id, $expectedSecretOfJohn->created_by);
        $this->assertSame($ada->id, $expectedSecretOfJohn->modified_by);
    }

    /**
     * Private resource, but it wasn't private before.
     *
     * @return void
     */
    public function testPopulateCreatedByAndModifiedByInSecretsService_PrivateResourceAfterChangeOfOwnership(): void
    {
        $ada = UserFactory::make()->withValidGpgKey()->persist();
        $betty = UserFactory::make()->withValidGpgKey()->persist();
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($betty)
            ->with('Secrets', [
                'user_id' => $betty->id,
                // set null for testing
                'created_by' => null,
                'modified_by' => null,
            ])
            ->persist();
        // secret history
        $secretOfBetty = $resource->secrets[0];
        $secretsHistoryOfBetty = SecretsHistoryFactory::make(['id' => $secretOfBetty->id])
            ->with('Users', $betty)
            ->with('Resources', $resource)
            ->persist();
        // action logs
        $actionLogAdaSharesResourceWithBetty = ActionLogFactory::make()
            ->setActionId('Share.share')
            ->userId($ada->id);
        // entities history entries
        EntitiesHistoryFactory::make(['created' => DateTime::now()])
            ->withActionLog($actionLogAdaSharesResourceWithBetty)
            ->withSecretsHistory($secretsHistoryOfBetty)
            ->create()
            ->persist();

        $this->service->populate();

        $expectedSecretOfBetty = SecretFactory::get($secretOfBetty->id);
        $this->assertSame($ada->id, $expectedSecretOfBetty->created_by);
        $this->assertSame($ada->id, $expectedSecretOfBetty->modified_by);
    }

    /**
     * Private resource, never updated ever.
     *
     * @return void
     */
    public function testPopulateCreatedByAndModifiedByInSecretsService_PrivateResourceNeverUpdated(): void
    {
        $ada = UserFactory::make()->withValidGpgKey()->persist();
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($ada)
            ->with('Secrets', [
                'user_id' => $ada->id,
                // set null for testing
                'created_by' => null,
                'modified_by' => null,
            ])
            ->persist();
        // secret history
        $secretOfAda = $resource->secrets[0];

        $this->service->populate();

        $expectedSecretOfAda = SecretFactory::get($secretOfAda->id);
        $this->assertSame($ada->id, $expectedSecretOfAda->created_by);
        $this->assertSame($ada->id, $expectedSecretOfAda->modified_by);
    }
}
