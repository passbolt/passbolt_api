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
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsSetService;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionsSettingsFactory;

/**
 * @covers \Passbolt\SecretRevisions\Controller\SecretRevisionsSettingsPostController
 */
class SecretRevisionsSettingsPostControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SecretRevisionsPlugin::class);
    }

    public function testSecretRevisionsSettingsPostController_Success_Create(): void
    {
        $operator = $this->logInAsAdmin();

        $data = ['max_revisions' => 2, 'allow_sharing_revisions' => false];
        $this->postJson('/secret-revisions/settings.json', $data);

        $this->assertSuccess();
        // assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes(['max_revisions', 'allow_sharing_revisions'], $response);
        // assert database entries
        $orgSettings = SecretRevisionsSettingsFactory::find()->all()->toArray();
        $this->assertCount(1, $orgSettings);
        $orgSetting = $orgSettings[0];
        $this->assertSame(SecretRevisionsSettingsSetService::ORG_SETTING_PROPERTY, $orgSetting['property']);
        $this->assertSame($operator->id, $orgSetting['created_by']);
        $this->assertSame($operator->id, $orgSetting['modified_by']);
        $settings = json_decode($orgSetting['value'], true);
        $this->assertArrayEqualsCanonicalizing($data, $settings);
    }

    public function testSecretRevisionsSettingsPostController_Success_Update(): void
    {
        $existingSettings = [
            'max_revisions' => 1,
            'allow_sharing_revisions' => false,
        ];
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\OrganizationSetting $existingEntity */
        $existingEntity = SecretRevisionsSettingsFactory::make(['created_by' => $ada->id, 'modified_by' => $ada->id])
            ->value($existingSettings)
            ->persist();

        $betty = $this->logInAsAdmin();
        $data = ['max_revisions' => 5, 'allow_sharing_revisions' => false];
        $this->postJson('/secret-revisions/settings.json', $data);

        $this->assertSuccess();
        // assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes(['max_revisions', 'allow_sharing_revisions'], $response);
        // assert database entries
        $orgSetting = SecretRevisionsSettingsFactory::get($existingEntity->id);
        $this->assertSame(SecretRevisionsSettingsSetService::ORG_SETTING_PROPERTY, $orgSetting['property']);
        $this->assertSame($ada->id, $orgSetting['created_by']);
        $this->assertSame($betty->id, $orgSetting['modified_by']);
        $settings = json_decode($orgSetting['value'], true);
        $this->assertArrayEqualsCanonicalizing($data, $settings);
    }

    public function testSecretRevisionsSettingsPostController_Error_UserNotLoggedIn(): void
    {
        $this->postJson('/secret-revisions/settings.json');
        $this->assertAuthenticationError();
    }

    public function testSecretRevisionsSettingsPostController_Error_UserNotAdministrator(): void
    {
        $this->logInAsUser();
        $this->postJson('/secret-revisions/settings.json');
        $this->assertForbiddenError('Access restricted to administrators');
    }

    public function testSecretRevisionsSettingsPostController_Error_EmptyData(): void
    {
        $this->logInAsAdmin();
        $this->postJson('/secret-revisions/settings.json');
        $this->assertBadRequestError('The request data can not be empty');
    }

    public function testSecretRevisionsSettingsPostController_Error_InvalidValues(): void
    {
        $this->logInAsAdmin();
        $this->postJson('/secret-revisions/settings.json', [
            'max_revisions' => -1,
            'allow_sharing_revisions' => 'foo-bar',
        ]);
        $this->assertBadRequestError('Could not validate the settings');
    }

    public function testSecretRevisionsSettingsPostController_Error_ConfigurationLimitExceeds(): void
    {
        $this->logInAsAdmin();
        $this->postJson('/secret-revisions/settings.json', [
            'max_revisions' => -1,
            'allow_sharing_revisions' => 'foo-bar',
        ]);
        $this->assertBadRequestError('Could not validate the settings');
    }

    public function testSecretRevisionsSettingsPostController_Error_RequestNotJson(): void
    {
        $this->logInAsAdmin();
        $this->post('/secret-revisions/settings', [
            'max_revisions' => 1,
            'allow_sharing_revisions' => false,
        ]);
        $this->assertResponseCode(404, 'Please use .json extension in URL or accept application/json');
    }
}
