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

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsGetService;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionsSettingsFactory;

/**
 * @covers \Passbolt\SecretRevisions\Controller\SecretRevisionsSettingsGetController
 */
class SecretRevisionsSettingsGetControllerTest extends AppIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SecretRevisionsPlugin::class);
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        SecretRevisionsSettingsGetService::clear();
        parent::tearDown();
    }

    public function testSecretRevisionsSettingsGetController_Success_Default(): void
    {
        $this->logInAsUser();

        $this->getJson('/secret-revisions/settings.json');

        $this->assertSuccess();
        // assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes(['max_revisions', 'allow_sharing_revisions'], $response);
        $expectedSettings = SecretRevisionsSettingsGetService::getDefaultSettings()->toArray();
        $this->assertSame($expectedSettings['max_revisions'], $response['max_revisions']);
        $this->assertSame($expectedSettings['allow_sharing_revisions'], $response['allow_sharing_revisions']);
    }

    public function testSecretRevisionsSettingsGetController_Success_FromDatabase(): void
    {
        $existingSettings = [
            'max_revisions' => 4,
            'allow_sharing_revisions' => false,
        ];
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\OrganizationSetting $existingEntity */
        SecretRevisionsSettingsFactory::make(['created_by' => $ada->id, 'modified_by' => $ada->id])
            ->value($existingSettings)
            ->persist();

        $this->logInAsUser();
        $this->getJson('/secret-revisions/settings.json');

        $this->assertSuccess();
        // assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes(['max_revisions', 'allow_sharing_revisions'], $response);
        $this->assertSame($existingSettings['max_revisions'], $response['max_revisions']);
        $this->assertSame($existingSettings['allow_sharing_revisions'], $response['allow_sharing_revisions']);
    }

    public function testSecretRevisionsSettingsGetController_Error_UserNotLoggedIn(): void
    {
        $this->getJson('/secret-revisions/settings.json');
        $this->assertAuthenticationError();
    }

    public function testSecretRevisionsSettingsGetController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->get('/secret-revisions/settings');
        $this->assertNotJsonError();
    }
}
