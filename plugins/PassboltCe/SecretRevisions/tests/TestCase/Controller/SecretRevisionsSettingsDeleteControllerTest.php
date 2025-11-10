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
 * @covers \Passbolt\SecretRevisions\Controller\SecretRevisionsSettingsDeleteController
 */
class SecretRevisionsSettingsDeleteControllerTest extends AppIntegrationTestCase
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

    public function testSecretRevisionsSettingsDeleteController_Success(): void
    {
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()->admin()->persist();
        SecretRevisionsSettingsFactory::make(['created_by' => $ada->id, 'modified_by' => $ada->id])
            ->value(SecretRevisionsSettingsGetService::getDefaultSettings()->toArray())
            ->persist();

        $this->logInAsAdmin();
        $this->deleteJson('/secret-revisions/settings.json');

        $this->assertSuccess();
        // assert settings are removed from the database
        $this->assertCount(0, SecretRevisionsSettingsFactory::find()->all()->toArray());
    }

    public function testSecretRevisionsSettingsDeleteController_Success_NoSettingsPresentReturnsSuccess(): void
    {
        $this->logInAsAdmin();
        $this->deleteJson('/secret-revisions/settings.json');
        $this->assertSuccess();
    }

    public function testSecretRevisionsSettingsDeleteController_Error_UserNotLoggedIn(): void
    {
        $this->deleteJson('/secret-revisions/settings.json');
        $this->assertAuthenticationError();
    }

    public function testSecretRevisionsSettingsDeleteController_Error_UserNotAdministrator(): void
    {
        $this->logInAsUser();
        $this->deleteJson('/secret-revisions/settings.json');
        $this->assertForbiddenError('Access restricted to administrators');
    }

    public function testSecretRevisionsSettingsDeleteController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->delete('/secret-revisions/settings');
        $this->assertNotJsonError();
    }
}
