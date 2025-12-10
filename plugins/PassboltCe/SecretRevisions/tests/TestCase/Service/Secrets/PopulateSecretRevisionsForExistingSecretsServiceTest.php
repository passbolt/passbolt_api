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

namespace Passbolt\SecretRevisions\Test\TestCase\Service\Secrets;

use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Factory\EntitiesHistoryFactory;
use Passbolt\Log\Test\Factory\SecretsHistoryFactory;
use Passbolt\SecretRevisions\Service\Secrets\PopulateSecretRevisionsForExistingSecretsService;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

/**
 * @covers \Passbolt\SecretRevisions\Service\Secrets\PopulateSecretRevisionsForExistingSecretsService
 */
class PopulateSecretRevisionsForExistingSecretsServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public PopulateSecretRevisionsForExistingSecretsService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PopulateSecretRevisionsForExistingSecretsService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testPopulateSecretRevisionsForExistingSecretsService_PrivateResourceUpdatedTwice(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->with('Secrets', ['user_id' => $user->id])
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
        // Fixturize data for scenario where secret revision is already present for the resource
        $user2 = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\Resource $resource2 */
        $resource2 = ResourceFactory::make()
            ->withCreatorAndPermission($user2)
            ->persist();
        $secretRevisionOfR2 = SecretRevisionFactory::make([
            'resource_id' => $resource2->id,
            'resource_type_id' => $resource2->resource_type_id,
            'created' => DateTime::now()->subDays(2),
        ])->persist();
        $secretOfUser2 = SecretFactory::make([
            'id' => UuidFactory::uuid(),
            'secret_revision_id' => $secretRevisionOfR2->get('id'),
            'user_id' => $user2->get('id'),
        ])->persist();

        $this->service->populate();

        $this->assertSame(2, SecretRevisionFactory::count());
        /** @var \Passbolt\SecretRevisions\Model\Entity\SecretRevision[] $secretRevisions */
        $secretRevisions = SecretRevisionFactory::find()->orderByDesc('created')->toArray();
        $secretRevisionCreated = $secretRevisions[0];
        $this->assertSame($resource->id, $secretRevisionCreated->resource_id);
        $this->assertSame($resource->resource_type_id, $secretRevisionCreated->resource_type_id);
        $this->assertSame($user->id, $secretRevisionCreated->created_by);
        $this->assertSame($user->id, $secretRevisionCreated->modified_by);
        $this->assertNull($secretRevisionCreated->deleted);
        // Assert secrets
        /** @var \App\Model\Entity\Secret $actualSecret */
        $actualSecret = SecretFactory::get($secret->get('id'));
        $this->assertSame($secretRevisionCreated->id, $actualSecret->secret_revision_id);
        $actualSecretOfUser2 = SecretFactory::get($secretOfUser2->get('id'));
        $this->assertSame($secretRevisionOfR2->get('id'), $actualSecretOfUser2->get('secret_revision_id'));
    }

    public function testPopulateSecretRevisionsForExistingSecretsService_SharedResourceWithOneUserAndSecretUpdatedOnce(): void
    {
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\User $betty */
        $betty = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($ada)
            ->with('Secrets', [
                [
                    'user_id' => $ada->id,
                ],
                [
                    'user_id' => $betty->id,
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

        /** @var \Passbolt\SecretRevisions\Model\Entity\SecretRevision[] $secretRevisions */
        $secretRevisions = SecretRevisionFactory::find()->all()->toArray();
        $this->assertCount(1, $secretRevisions);
        $secretRevision = $secretRevisions[0];
        $this->assertSame($resource->id, $secretRevision->resource_id);
        $this->assertSame($resource->resource_type_id, $secretRevision->resource_type_id);
        $this->assertSame($ada->id, $secretRevision->created_by);
        $this->assertSame($ada->id, $secretRevision->modified_by);
        $this->assertNull($secretRevision->deleted);
    }

    public function testPopulateSecretRevisionsForExistingSecretsService_SharedResourceWithAnotherTwoUsers(): void
    {
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\User $betty */
        $betty = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\User $john */
        $john = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($ada)
            ->with('Secrets', [
                [
                    'user_id' => $ada->id,
                ],
                [
                    'user_id' => $betty->id,
                ],
                [
                    'user_id' => $john->id,
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

        /** @var \Passbolt\SecretRevisions\Model\Entity\SecretRevision[] $secretRevisions */
        $secretRevisions = SecretRevisionFactory::find()->all()->toArray();
        $this->assertCount(1, $secretRevisions);
        $secretRevision = $secretRevisions[0];
        $this->assertSame($resource->id, $secretRevision->resource_id);
        $this->assertSame($resource->resource_type_id, $secretRevision->resource_type_id);
        $this->assertSame($ada->id, $secretRevision->created_by);
        $this->assertSame($ada->id, $secretRevision->modified_by);
        $this->assertNull($secretRevision->deleted);
        // Assert secrets
        /** @var \App\Model\Entity\Secret[] $secrets */
        $secrets = SecretFactory::find()->where(['id IN' => [$secretOfAda->id, $secretOfJohn->id, $secretOfBetty->id]])->all()->toArray();
        $this->assertSame($secretRevision->id, $secrets[0]->secret_revision_id);
        $this->assertSame($secretRevision->id, $secrets[1]->secret_revision_id);
        $this->assertSame($secretRevision->id, $secrets[2]->secret_revision_id);
    }

    public function testPopulateSecretRevisionsForExistingSecretsService_PrivateResourceAfterChangeOfOwnership(): void
    {
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\User $betty */
        $betty = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($betty)
            ->with('Secrets', ['user_id' => $betty->id])
            ->persist();
        // secret history
        $secretOfBetty = $resource->secrets[0];
        $secretsHistoryOfBetty = SecretsHistoryFactory::make(['id' => $secretOfBetty->id])
            ->with('Users', $betty)
            ->with('Resources', $resource)
            ->persist();
        // not present in secrets but kept in the history for audit purposes
        $secretsHistoryOfAda = SecretsHistoryFactory::make(['id' => UuidFactory::uuid()])
            ->with('Users', $betty)
            ->with('Resources', $resource)
            ->persist();
        // action logs
        $actionLogAdaSharesResourceWithBetty = ActionLogFactory::make()
            ->setActionId('Share.share')
            ->userId($ada->id);
        $actionLogAdaUpdatesResource = ActionLogFactory::make()
            ->setActionId('ResourcesUpdate.update')
            ->userId($betty->id);
        $actionLogBettyUpdatesResource = ActionLogFactory::make()
            ->setActionId('ResourcesUpdate.update')
            ->userId($betty->id);
        $actionLogBettyUpdatesResource2 = ActionLogFactory::make()
            ->setActionId('ResourcesUpdate.update')
            ->userId($betty->id);
        // entities history entries
        EntitiesHistoryFactory::make(['created' => DateTime::now()->subMinutes(30)])
            ->withActionLog($actionLogAdaSharesResourceWithBetty)
            ->withSecretsHistory($secretsHistoryOfBetty)
            ->create()
            ->persist();
        EntitiesHistoryFactory::make(['created' => DateTime::now()->subMinutes(25)])
            ->withActionLog($actionLogAdaUpdatesResource)
            ->withSecretsHistory($secretsHistoryOfAda)
            ->update()
            ->persist();
        EntitiesHistoryFactory::make(['created' => DateTime::now()->subMinutes(10)])
            ->withActionLog($actionLogBettyUpdatesResource)
            ->withSecretsHistory($secretsHistoryOfBetty)
            ->update()
            ->persist();
        EntitiesHistoryFactory::make(['created' => DateTime::now()->subMinutes(2)])
            ->withActionLog($actionLogBettyUpdatesResource2)
            ->withSecretsHistory($secretsHistoryOfBetty)
            ->update()
            ->persist();

        $this->service->populate();

        /** @var \Passbolt\SecretRevisions\Model\Entity\SecretRevision[] $secretRevisions */
        $secretRevisions = SecretRevisionFactory::find()->all()->toArray();
        $this->assertCount(1, $secretRevisions);
        $secretRevision = $secretRevisions[0];
        $this->assertSame($resource->id, $secretRevision->resource_id);
        $this->assertSame($resource->resource_type_id, $secretRevision->resource_type_id);
        $this->assertSame($betty->id, $secretRevision->created_by);
        $this->assertSame($betty->id, $secretRevision->modified_by);
        $this->assertNull($secretRevision->deleted);
        // Assert secrets
        /** @var \App\Model\Entity\Secret $secret */
        $secret = SecretFactory::find()->where(['id' => $secretOfBetty->id])->firstOrFail();
        $this->assertSame($secretRevision->id, $secret->secret_revision_id);
    }

    public function testPopulateSecretRevisionsForExistingSecretsService_PrivateResourceNeverUpdated(): void
    {
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($ada)
            ->with('Secrets', ['user_id' => $ada->id])
            ->persist();
        // secret history
        $secretOfAda = $resource->secrets[0];

        $this->service->populate();

        /** @var \Passbolt\SecretRevisions\Model\Entity\SecretRevision[] $secretRevisions */
        $secretRevisions = SecretRevisionFactory::find()->all()->toArray();
        $this->assertCount(1, $secretRevisions);
        $secretRevision = $secretRevisions[0];
        $this->assertSame($resource->id, $secretRevision->resource_id);
        $this->assertSame($resource->resource_type_id, $secretRevision->resource_type_id);
        $this->assertSame($ada->id, $secretRevision->created_by);
        $this->assertSame($ada->id, $secretRevision->modified_by);
        $this->assertNull($secretRevision->deleted);
        // Assert secrets
        /** @var \App\Model\Entity\Secret $secret */
        $secret = SecretFactory::find()->where(['id' => $secretOfAda->id])->firstOrFail();
        $this->assertSame($secretRevision->id, $secret->secret_revision_id);
    }

    /**
     * Test that batch processing works correctly when the number of resources
     * exceeds the batch size, ensuring all resources are processed across multiple batches.
     */
    public function testPopulateSecretRevisionsForExistingSecretsService_BatchProcessing(): void
    {
        // Create 5 resources to test batch processing with a batch size of 2
        $batchSize = 2;
        $totalResources = 5;

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();

        // Create multiple resources without secret revisions
        /** @var \App\Model\Entity\Resource[] $resources */
        $resources = ResourceFactory::make($totalResources)
            ->withCreatorAndPermission($user)
            ->with('Secrets', ['user_id' => $user->id])
            ->persist();

        // Use a small batch size to test multiple batch iterations
        $service = new PopulateSecretRevisionsForExistingSecretsService($batchSize);
        $service->populate();

        // Assert all resources have secret revisions created
        $this->assertSame($totalResources, SecretRevisionFactory::count());

        // Verify each resource has a corresponding secret revision
        foreach ($resources as $resource) {
            /** @var \Passbolt\SecretRevisions\Model\Entity\SecretRevision|null $secretRevision */
            $secretRevision = SecretRevisionFactory::find()
                ->where(['resource_id' => $resource->id])
                ->first();
            $this->assertNotNull($secretRevision, "Secret revision not found for resource {$resource->id}");
            $this->assertSame($resource->resource_type_id, $secretRevision->resource_type_id);
            $this->assertSame($user->id, $secretRevision->created_by);
            $this->assertSame($user->id, $secretRevision->modified_by);

            // Verify the secret is linked to the revision
            /** @var \App\Model\Entity\Secret $secret */
            $secret = SecretFactory::find()
                ->where(['resource_id' => $resource->id, 'user_id' => $user->id])
                ->firstOrFail();
            $this->assertSame($secretRevision->id, $secret->secret_revision_id);
        }
    }
}
