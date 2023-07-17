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
namespace Passbolt\Sso\Test\TestCase\Controller\Settings;

use App\Utility\UuidFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

class SsoSettingsDeleteControllerTest extends SsoIntegrationTestCase
{
    public function testSsoSettingsDeleteController_SuccessAzure(): void
    {
        $ssoSetting = SsoSettingsFactory::make()->persist();
        $this->logInAsAdmin();
        $this->deleteJson('/sso/settings/' . $ssoSetting->id . '.json');
        $this->assertSuccess();
    }

    public function testSsoSettingsDeleteController_ErrorNotLoggedIn(): void
    {
        $ssoSetting = SsoSettingsFactory::make()->persist();
        $this->deleteJson('/sso/settings/' . $ssoSetting->id . '.json');
        $this->assertAuthenticationError();
    }

    public function testSsoSettingsDeleteController_ErrorNotAdmin(): void
    {
        $this->logInAsUser();
        $ssoSetting = SsoSettingsFactory::make()->persist();
        $this->deleteJson('/sso/settings/' . $ssoSetting->id . '.json');
        $this->assertError(403);
    }

    public function testSsoSettingsDeleteController_ErrorNotFound(): void
    {
        $this->logInAsAdmin();
        $this->deleteJson('/sso/settings/' . UuidFactory::uuid() . '.json');
        $this->assertError(404);
    }

    public function testSsoSettingsDeleteController_ErrorNotUUID(): void
    {
        $this->logInAsAdmin();
        $this->deleteJson('/sso/settings/nope.json');
        $this->assertError(400);
    }
}
