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
 * @since         4.3.0
 */

namespace App\Test\TestCase\Controller\Notifications;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionsSettingsFactory;

class ResourcesUpdateNotificationWithFactoriesTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadNotificationSettings();
        $this->enableFeaturePlugin(SecretRevisionsPlugin::class);
    }

    public function tearDown(): void
    {
        $this->unloadNotificationSettings();
        parent::tearDown();
    }

    public function testResourcesUpdateNotificationSuccess_Secrets(): void
    {
        SecretRevisionsSettingsFactory::make()->setMaxRevisions(2)->persist();
        $this->setEmailNotificationSetting('send.password.update', true);
        // Create users
        RoleFactory::make()->guest()->persist();
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->withValidGpgKey()->persist();
        // Get and update resource
        ResourceTypeFactory::make()->default()->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC, $userD])
            ->withSecretsFor([$userA, $userB, $userC, $userD])
            ->withSecretRevisions()
            ->persist();
        // Prepare secrets
        $userBEncryptedSecret = $this->encryptMessageFor($userB->id, 'R1 secret updated');
        $userCEncryptedSecret = $this->encryptMessageFor($userC->id, 'R1 secret updated');
        $userDEncryptedSecret = $this->encryptMessageFor($userD->id, 'R1 secret updated');
        $userAEncryptedSecret = $this->encryptMessageFor($userA->id, 'R1 secret updated');
        $data = [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
            'secrets' => [
                ['user_id' => $userB->id, 'data' => $userBEncryptedSecret],
                ['user_id' => $userC->id, 'data' => $userCEncryptedSecret],
                ['user_id' => $userD->id, 'data' => $userDEncryptedSecret],
                ['user_id' => $userA->id, 'data' => $userAEncryptedSecret],
            ],
        ];
        $this->logInAs($userB);

        $this->putJson("/resources/{$resource->id}.json", $data);

        $this->assertSuccess();
        // Assert email contents
        $this->assertEmailInBatchContains('edited the resource', $userA->username);
        $this->assertEmailInBatchContains('edited the resource', $userB->username);
        $this->assertEmailInBatchContains('edited the resource', $userC->username);
        $this->assertEmailInBatchContains('edited the resource', $userD->username);
    }
}
