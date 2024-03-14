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
 * @since         4.6.0
 */

namespace Passbolt\Sso\Test\TestCase\Controller\Adfs;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

/**
 * @see \Passbolt\Sso\Controller\Adfs\SsoAdfsStage1DryRunController
 */
class SsoAdfsStage1DryRunControllerTest extends SsoIntegrationTestCase
{
    /**
     * 200 returns a URL for ADFS provider
     */
    public function testSsoAdfsStage1DryRunController_Success(): void
    {
        // Requires mocking ADFS service or working ADFS instance
        $this->markTestIncomplete();
    }

    /**
     * 401 user is not logged in
     */
    public function testSsoAdfsStage1DryRunController_ErrorNotLoggedIn(): void
    {
        $ssoSetting = SsoSettingsFactory::make()->adfs()->draft()->persist();

        $this->postJson('/sso/adfs/login/dry-run.json', ['sso_settings_id' => $ssoSetting->get('id')]);

        $this->assertError(401);
    }

    /**
     * 403 user is not an admin
     */
    public function testSsoAdfsStage1DryRunController_ErrorNotAdmin(): void
    {
        $user = UserFactory::make()->user()->persist();
        $settings = SsoSettingsFactory::make()->adfs()->draft()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/adfs/login/dry-run.json', ['sso_settings_id' => $settings->get('id')]);

        $this->assertError(403);
    }

    /**
     * 400 missing settings
     */
    public function testSsoAdfsStage1DryRunController_ErrorSettingsMissing(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->logInAs($admin);

        $this->postJson('/sso/adfs/login/dry-run.json', []);

        $this->assertError(400);
    }

    /**
     * 400 null settings
     */
    public function testSsoAdfsStage1DryRunController_ErrorNullSettings(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->adfs()->draft()->persist();
        $this->logInAs($admin);

        $this->postJson('/sso/adfs/login/dry-run.json', ['sso_settings_id' => null]);

        $this->assertError(400);
    }

    /**
     * 400 Invalid settings id
     */
    public function testSsoAdfsStage1DryRunController_ErrorSettingsInvalid(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->logInAs($admin);

        $this->postJson('/sso/adfs/login/dry-run.json', ['sso_settings_id' => 'nope']);

        $this->assertError(400);
    }

    /**
     * 404 settings not found
     */
    public function testSsoAdfsStage1DryRunController_ErrorSettingsNotFound(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->logInAs($admin);

        $this->postJson('/sso/adfs/login/dry-run.json', ['sso_settings_id' => UuidFactory::uuid()]);

        $this->assertError(404);
    }

    /**
     * 404 settings not found - not in draft state
     */
    public function testSsoAdfsStage1DryRunController_ErrorSettingsNotDraft(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $settings = SsoSettingsFactory::make()->adfs()->active()->persist();
        $this->logInAs($admin);

        $this->postJson('/sso/adfs/login/dry-run.json', ['sso_settings_id' => $settings->get('id')]);

        $this->assertError(404);
    }
}
