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

namespace Passbolt\SecretRevisions\Test\TestCase\Controller;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use Passbolt\Log\LogPlugin;
use Passbolt\Log\Test\Factory\EntitiesHistoryFactory;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsGetService;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionsSettingsFactory;

/**
 * @covers \Passbolt\SecretRevisions\Controller\SecretRevisionsResourceGetController
 */
class SecretRevisionsResourceGetControllerTest extends AppIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SecretRevisionsPlugin::class);
        $this->enableFeaturePlugin(LogPlugin::class);
        SecretRevisionsSettingsGetService::clear();
    }

    public function tearDown(): void
    {
        SecretRevisionsSettingsGetService::clear();
        parent::tearDown();
    }

    public function testSecretRevisionsResourceGetController_Success(): void
    {
        $maxRevision = 2;
        SecretRevisionsSettingsFactory::make()->setMaxRevisions($maxRevision)->persist();

        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->withSecretRevisions(SecretRevisionFactory::make([
                [], // Active secret revision
                ['deleted' => FrozenTime::yesterday()], // This one will be ignored as it won't have secrets associated to the user
                ['deleted' => FrozenTime::today()->subDays(1)],
                ['deleted' => FrozenTime::today()->subDays(2)], // This one wil be ignored as the secret revisions history returns only 2 deleted secret revision
            ]))
            ->persist();

        $activeSecretRevision = $resource->secret_revisions[0];
        $deletedSecretRevisionServed = $resource->secret_revisions[2];

        $secretFactory = SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users', $user);

        $secretFactory->with('SecretRevisions', $activeSecretRevision)->persist();
        $secretFactory->with('SecretRevisions', $resource->secret_revisions[2])->persist();
        $secretFactory->with('SecretRevisions', $resource->secret_revisions[3])->persist();

        $this->getJson("/secret-revisions/resource/{$resource->id}.json");
        $this->assertResponseOk();

        $response = $this->getResponseBodyAsArray();
        $this->assertCount($maxRevision, $response);
        $this->assertSame($activeSecretRevision->id, $response[0]['id']);
        $this->assertSame($activeSecretRevision->resource_id, $response[0]['resource_id']);
        $this->assertEquals($activeSecretRevision->created->toAtomString(), $response[0]['created']);
        $this->assertSame($activeSecretRevision->created_by, $response[0]['created_by']);

        $this->assertSame($deletedSecretRevisionServed->id, $response[1]['id']);
        $this->assertSame($deletedSecretRevisionServed->resource_id, $response[1]['resource_id']);
        $this->assertEquals($deletedSecretRevisionServed->created->toAtomString(), $response[1]['created']);
        $this->assertSame($deletedSecretRevisionServed->created_by, $response[1]['created_by']);

        // Assert that no secret access were persisted
        $this->assertSame(0, SecretAccessFactory::count());
    }

    public function testSecretRevisionsResourceGetController_Without_Setting_On_Threshold(): void
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->withSecretRevisions(SecretRevisionFactory::make([
                [],
                ['deleted' => FrozenTime::today()->subDays(1)],
                ['deleted' => FrozenTime::today()->subDays(3)], // This one will be ignored as it won't have secrets associated to the user
            ]))
            ->persist();

        $secretRevision = $resource->secret_revisions[0];

        $secretFactory = SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users', $user);

        $secretFactory->with('SecretRevisions', $secretRevision)->persist();
        $secretFactory->with('SecretRevisions', $resource->secret_revisions[1])->persist();

        $this->getJson("/secret-revisions/resource/{$resource->id}.json");
        $this->assertResponseOk();

        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);
        $this->assertSame($secretRevision->id, $response[0]['id']);
        $this->assertSame($secretRevision->resource_id, $response[0]['resource_id']);
        $this->assertEquals($secretRevision->created->toAtomString(), $response[0]['created']);
        $this->assertSame($secretRevision->created_by, $response[0]['created_by']);

        // Assert that no secret access were persisted
        $this->assertSame(0, SecretAccessFactory::count());
    }

    public function testSecretRevisionsResourceGetController_Success_Contain_Secret(): void
    {
        $maxRevision = 2;
        SecretRevisionsSettingsFactory::make()->setMaxRevisions($maxRevision)->persist();
        $user = $this->logInAsUser();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->withSecretRevisions(SecretRevisionFactory::make())
            ->persist();

        $secretRevision = $resource->secret_revisions[0];

        // Deleted secret revision
        /** @var \Passbolt\SecretRevisions\Model\Entity\SecretRevision $deletedSecretRevision */
        $deletedSecretRevision = SecretRevisionFactory::make()
            ->with('Resources', $resource)
            ->with('Secrets', SecretFactory::make()->deleted())
            ->deleted()
            ->persist();

        $secretFactory = SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users', $user);

        /** @var \App\Model\Entity\Secret $secret */
        $secret = $secretFactory->with('SecretRevisions', $secretRevision)->persist();
        /** @var \App\Model\Entity\Secret $pastSecret */
        $pastSecret = $secretFactory->with('SecretRevisions', $deletedSecretRevision)->deleted()->persist();

        // Secret with the same secret revision and resource but another user should be ignored
        SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users')
            ->with('SecretRevisions', $secretRevision)
            ->persist();

        $this->getJson("/secret-revisions/resource/{$resource->id}.json?contain[secret]=1");

        $response = $this->getResponseBodyAsArray();
        $this->assertCount($maxRevision, $response);

        // Assert on the active secret revision
        $this->assertSame($secretRevision->id, $response[0]['id']);
        $this->assertSame($secretRevision->resource_id, $response[0]['resource_id']);
        $this->assertEquals($secretRevision->created->toAtomString(), $response[0]['created']);
        $this->assertSame($secretRevision->created_by, $response[0]['created_by']);

        // Assert on the past secret revision
        $this->assertSame($deletedSecretRevision->id, $response[1]['id']);
        $this->assertSame($deletedSecretRevision->resource_id, $response[1]['resource_id']);
        $this->assertEquals($deletedSecretRevision->created->toAtomString(), $response[1]['created']);
        $this->assertSame($deletedSecretRevision->created_by, $response[1]['created_by']);

        // Assert on the active secret
        $this->assertCount(1, $response[0]['secrets']);
        $this->assertSame($secret->id, $response[0]['secrets'][0]['id']);
        $this->assertSame($secretRevision->id, $response[0]['secrets'][0]['secret_revision_id']);

        // Assert on the past secret
        $this->assertCount(1, $response[1]['secrets']);
        $this->assertSame($pastSecret->id, $response[1]['secrets'][0]['id']);
        $this->assertSame($deletedSecretRevision->id, $response[1]['secrets'][0]['secret_revision_id']);

        // Assert that 1 secret access is persisted (only for the active secret)
        $this->assertSame(1, SecretAccessFactory::count());
        $this->assertSame(1, EntitiesHistoryFactory::count());
        $this->assertSame(1, SecretFactory::find()->where(['user_id' => $user->id, 'secret_revision_id' => $secretRevision->id, 'resource_id' => $resource->id])->count());
        $this->assertSame(1, SecretFactory::find()->where(['user_id' => $user->id, 'secret_revision_id' => $deletedSecretRevision->id, 'resource_id' => $resource->id])->count());
    }

    public function testSecretRevisionsResourceGetController_Success_Contain_Creator(): void
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\User $creator */
        $creator = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->withSecretRevisions(SecretRevisionFactory::make(['created_by' => $creator->id]))
            ->persist();

        $secretRevision = $resource->secret_revisions[0];

        SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users', $user)
            ->with('SecretRevisions', $secretRevision)
            ->persist();

        $this->getJson("/secret-revisions/resource/{$resource->id}.json?contain[creator]=1");

        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);
        $this->assertSame($secretRevision->id, $response[0]['id']);
        $this->assertSame($secretRevision->resource_id, $response[0]['resource_id']);
        $this->assertEquals($secretRevision->created->toAtomString(), $response[0]['created']);
        $this->assertSame($secretRevision->created_by, $response[0]['created_by']);

        // Assert on creator
        $this->assertSame($creator->id, $response[0]['creator']['id']);
    }

    public function testSecretRevisionsResourceGetController_Success_Contain_Creator_Profile(): void
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\User $creator */
        $creator = UserFactory::make()->withAvatar()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->withSecretRevisions(SecretRevisionFactory::make(['created_by' => $creator->id]))
            ->persist();

        $secretRevision = $resource->secret_revisions[0];

        SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users', $user)
            ->with('SecretRevisions', $secretRevision)
            ->persist();

        $this->getJson("/secret-revisions/resource/{$resource->id}.json?contain[creator.profile]=1");

        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);
        $this->assertSame($secretRevision->id, $response[0]['id']);
        $this->assertSame($secretRevision->resource_id, $response[0]['resource_id']);
        $this->assertEquals($secretRevision->created->toAtomString(), $response[0]['created']);
        $this->assertSame($secretRevision->created_by, $response[0]['created_by']);

        // Assert on creator
        $this->assertSame($creator->id, $response[0]['creator']['id']);
        $this->assertSame($creator->profile->id, $response[0]['creator']['profile']['id']);
        $this->assertSame($creator->profile->avatar->id, $response[0]['creator']['profile']['avatar']['id']);
    }

    public function testSecretRevisionsResourceGetController_Error_No_Permission(): void
    {
        $this->logInAsUser();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();

        $this->getJson("/secret-revisions/resource/{$resource->id}.json");
        $this->assertNotFoundError('The resource does not exist.');
    }

    public function testSecretRevisionsResourceGetController_Error_Resource_Soft_Deleted(): void
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->deleted()
            ->persist();

        $this->getJson("/secret-revisions/resource/{$resource->id}.json");
        $this->assertNotFoundError('The resource does not exist.');
    }

    public function testSecretRevisionsResourceGetController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->get('/secret-revisions/resource/' . UuidFactory::uuid());
        $this->assertNotJsonError();
    }

    public function testSecretRevisionsResourceGetController_Error_Not_Logged_In(): void
    {
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();
        $this->getJson("/secret-revisions/resource/{$resource->id}.json");
        $this->assertAuthenticationError();
    }
}
