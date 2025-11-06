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

namespace Passbolt\AuditLog\Test\TestCase\Controller\Resources;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsGetService;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionsFactory;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionsSettingsFactory;

/**
 * @covers \App\Controller\Resources\ResourcesUpdateController
 */
class ResourcesUpdateControllerTest extends LogIntegrationTestCase
{
    use GroupsModelTrait;
    use SecretsModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SecretRevisionsPlugin::class);
    }

    public function tearDown(): void
    {
        SecretRevisionsSettingsGetService::clear();
        parent::tearDown();
    }

    public function testUpdateResourcesController_Success_UpdateResourceSecrets(): void
    {
        SecretRevisionsSettingsFactory::make()->setMaxRevisions(2)->persist();
        RoleFactory::make()->guest()->persist();
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // G1 is OWNER of resource R1
        // ---
        // R1 (Ada:O, Betty:O, G1:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->withValidGpgKey()->persist();
        ResourceTypeFactory::make()->default()->persist();
        /** @var \App\Model\Entity\Resource $r1 */
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withSecretsFor([$userA, $userB, $userC])
            ->withSecretRevisions()
            ->persist();

        $this->logInAs($userA);
        $r1EncryptedSecretA = $this->encryptMessageFor($userA->id, 'R1 secret updated');
        $r1EncryptedSecretB = $this->encryptMessageFor($userB->id, 'R1 secret updated');
        $r1EncryptedSecretC = $this->encryptMessageFor($userC->id, 'R1 secret updated');
        $data = [
            'secrets' => [
                ['user_id' => $userA->id, 'data' => $r1EncryptedSecretA],
                ['user_id' => $userB->id, 'data' => $r1EncryptedSecretB],
                ['user_id' => $userC->id, 'data' => $r1EncryptedSecretC],
            ],
        ];
        $this->putJson("/resources/$r1->id.json", $data);
        $this->assertSuccess();

        $this->assertSame(2, SecretRevisionsFactory::count());

        // Assert User Action Logs
        $this->getJson("/actionlog/resource/{$r1->get('id')}.json");
        $this->assertResponseOk();
        $body = $this->getResponseBodyAsArray();

        $this->assertCount(2, $body);

        // Assert resource updated
        $actionLog1 = $body[0];
        $this->assertSame('Resources.updated', $actionLog1['type']);
        $this->assertSame([
            'resource' => [
                'id' => $r1->id,
                'name' => $r1->name,
            ],
        ], $actionLog1['data']);

        // Assert secret update
        $actionLog2 = $body[1];
        $this->assertSame('Resource.Secrets.updated', $actionLog2['type']);
        $this->assertSame(['id' => $r1->id, 'name' => $r1->name], $actionLog2['data']['resource']);
        $this->assertSame(3, count($actionLog2['data']['secrets']));
        $retrievedSecrets = $actionLog2['data']['secrets'];
        foreach ($retrievedSecrets as $secretInResponse) {
            /** @var \App\Model\Entity\Secret $secretInDB */
            $secretInDB = SecretFactory::find()
                ->where(['Secrets.id' => $secretInResponse['id']])
                ->contain('Users')
                ->firstOrFail();
            $this->assertSame([
                'id' => $secretInDB->id,
                'secrets_history_resource' => [
                    'id' => $r1->id,
                    'name' => $r1->name,
                ],
                'secrets_history_user' => [
                    'id' => $secretInDB->user_id,
                    'username' => $secretInDB->user->username,
                ],
            ], $secretInResponse);
        }
    }
}
