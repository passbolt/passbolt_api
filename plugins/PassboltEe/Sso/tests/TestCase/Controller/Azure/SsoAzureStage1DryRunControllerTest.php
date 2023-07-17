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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Test\TestCase\Controller\Azure;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

class SsoAzureStage1DryRunControllerTest extends SsoIntegrationTestCase
{
    /**
     * 200 returns a URL
     */
    public function testSsoAzureStage1DryRunController_Success_PromptLogin(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $settings = $this->createAzureSettingsFromConfig($user, SsoSetting::STATUS_DRAFT);

        $this->logInAs($user);
        $this->postJson('/sso/azure/login/dry-run.json', ['sso_settings_id' => $settings->id]);

        $this->assertSuccess();
        $this->assertStringContainsString('microsoft', $this->_responseJsonBody->url);
        $this->assertStringContainsString('prompt=login', $this->_responseJsonBody->url);
    }

    /**
     * 200 returns a URL
     */
    public function testSsoAzureStage1DryRunController_Success_PromptNoneIsNotPresent(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $settings = $this->createAzureSettingsFromConfig(
            $user,
            SsoSetting::STATUS_DRAFT,
            ['prompt' => SsoSettingsAzureDataForm::PROMPT_NONE]
        );

        $this->logInAs($user);
        $this->postJson('/sso/azure/login/dry-run.json', ['sso_settings_id' => $settings->id]);

        $this->assertSuccess();
        $this->assertStringContainsString('microsoft', $this->_responseJsonBody->url);
        $this->assertStringNotContainsString('prompt', $this->_responseJsonBody->url);
    }

    /**
     * 400 user is logged in
     */
    public function testSsoAzureStage1DryRunController_ErrorNotLoggedIn(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->azure()->draft()->persist();

        $this->postJson('/sso/azure/login/dry-run.json', ['user_id' => $user->id]);
        $this->assertError(401);
    }

    /**
     * 403 user is not an admin
     */
    public function testSsoAzureStage1DryRunController_ErrorNotAdmin(): void
    {
        $user = UserFactory::make()->user()->persist();
        $settings = SsoSettingsFactory::make()->azure()->draft()->persist();

        $this->logInAs($user);
        $this->postJson('/sso/azure/login/dry-run.json', ['sso_settings_id' => $settings->id]);
        $this->assertError(403);
    }

    /**
     * 403 settings missing too
     */
    public function testSsoAzureStage1DryRunController_ErrorSettingsMissing(): void
    {
        $user = UserFactory::make()->admin()->persist();

        $this->logInAs($user);
        $this->postJson('/sso/azure/login/dry-run.json', []);
        $this->assertError(400);
    }

    /**
     * 400 settings missing too
     */
    public function testSsoAzureStage1DryRunController_ErrorSettingsMissing2(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->azure()->draft()->persist();

        $this->logInAs($user);
        $this->postJson('/sso/azure/login/dry-run.json', ['sso_settings_id' => null]);
        $this->assertError(400);
    }

    /**
     * 400 settings id invalid
     */
    public function testSsoAzureStage1DryRunController_ErrorSettingsInvalid(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->azure()->draft()->persist();

        $this->logInAs($user);
        $this->postJson('/sso/azure/login/dry-run.json', ['sso_settings_id' => 'nope']);
        $this->assertError(400);
    }

    /**
     * 404 settings not found
     */
    public function testSsoAzureStage1DryRunController_ErrorSettingsNotFound(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->azure()->draft()->persist();

        $this->logInAs($user);
        $this->postJson('/sso/azure/login/dry-run.json', ['sso_settings_id' => UuidFactory::uuid()]);
        $this->assertError(404);
    }

    /**
     * 404 settings not found - not in draft state
     */
    public function testSsoAzureStage1DryRunController_ErrorSettingsNotDraft(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();

        $this->logInAs($user);
        $this->postJson('/sso/azure/login/dry-run.json', ['sso_settings_id' => $settings->id]);
        $this->assertError(404);
    }
}
