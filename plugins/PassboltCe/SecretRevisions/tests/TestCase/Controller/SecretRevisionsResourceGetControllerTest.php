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
use Passbolt\SecretRevisions\SecretRevisionsPlugin;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

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
    }

    public function testSecretRevisionsResourceGetController_Success(): void
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->withSecretRevisions(SecretRevisionFactory::make([
                ['created' => FrozenTime::yesterday()],
                ['created' => FrozenTime::today()->subDays(2)],
                ['created' => FrozenTime::today()->subDays(3)], // This one will be ignored as it won't have secrets associated to the user
            ]))
            ->persist();

        $secretRevision = $resource->secret_revisions[0];

        SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users', $user)
            ->with('SecretRevisions', $secretRevision)
            ->persist();

        SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users', $user)
            ->with('SecretRevisions', $resource->secret_revisions[1])
            ->persist();

        $this->getJson("/secret-revisions/resource/{$resource->id}.json");
        $this->assertResponseOk();

        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        $this->assertSame($secretRevision->id, $response[0]['id']);
        $this->assertSame($secretRevision->resource_id, $response[0]['resource_id']);
        $this->assertEquals($secretRevision->created->toAtomString(), $response[0]['created']);
        $this->assertSame($secretRevision->created_by, $response[0]['created_by']);
    }

    public function testSecretRevisionsResourceGetController_Success_Contain_Secret(): void
    {
        $user = $this->logInAsUser();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->withSecretRevisions(SecretRevisionFactory::make())
            ->persist();

        $secretRevision = $resource->secret_revisions[0];

        /** @var \App\Model\Entity\Secret $secret */
        $secret = SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users', $user)
            ->with('SecretRevisions', $secretRevision)
            ->persist();

        // Secret with the same secret revision and resource but another user should be ignored
        SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users')
            ->with('SecretRevisions', $secretRevision)
            ->persist();

        $this->getJson("/secret-revisions/resource/{$resource->id}.json?contain[secret]=1");

        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);
        $this->assertSame($secretRevision->id, $response[0]['id']);
        $this->assertSame($secretRevision->resource_id, $response[0]['resource_id']);
        $this->assertEquals($secretRevision->created->toAtomString(), $response[0]['created']);
        $this->assertSame($secretRevision->created_by, $response[0]['created_by']);

        // Assert on secrets
        $this->assertCount(1, $response[0]['secrets']);
        $this->assertSame($secret->id, $response[0]['secrets'][0]['id']);
        $this->assertSame($secretRevision->id, $response[0]['secrets'][0]['secret_revision_id']);
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
