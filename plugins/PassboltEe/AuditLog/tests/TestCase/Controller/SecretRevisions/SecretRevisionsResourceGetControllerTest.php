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

namespace Passbolt\AuditLog\Test\TestCase\Controller\SecretRevisions;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

/**
 * @covers \Passbolt\SecretRevisions\Controller\SecretRevisionsResourceGetController
 */
class SecretRevisionsResourceGetControllerTest extends LogIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SecretRevisionsPlugin::class);
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

        SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users', $user)
            ->with('SecretRevisions', $secretRevision)
            ->persist();

        // Deleted secret revision
        /** @var \Passbolt\SecretRevisions\Model\Entity\SecretRevision $deletedSecretRevision */
        $deletedSecretRevision = SecretRevisionFactory::make()
            ->with('Resources', $resource)
            ->with('Secrets', SecretFactory::make()->deleted())
            ->deleted()
            ->persist();
        SecretFactory::make()
            ->with('Resources', $resource)
            ->with('Users', $user)
            ->with('SecretRevisions', $deletedSecretRevision)
            ->deleted()
            ->persist();

        $this->getJson("/secret-revisions/resource/{$resource->id}.json?contain[secret]=1");

        // Assert User Action Logs
        $this->getJson("/actionlog/resource/{$resource->get('id')}.json");
        $this->assertResponseOk();
        $body = $this->getResponseBodyAsArray();

        // Assert that no secret access got persisted for the deleted secret
        $this->assertSame(1, SecretAccessFactory::count());
        $this->assertCount(1, $body);
        $log = $body[0];
        $this->assertSame('Resource.Secrets.read', $log['type']);
        $this->assertSame([
            'resource' => [
                'id' => $resource->id,
                'name' => $resource->get('name'),
            ],
        ], $log['data']);
        $this->assertSame($user->id, $log['creator']['id']);
    }
}
