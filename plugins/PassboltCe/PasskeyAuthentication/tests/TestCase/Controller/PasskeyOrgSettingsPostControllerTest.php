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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Test\TestCase\Controller;

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\PasskeyAuthentication\Service\PasskeyOrganizationSettingsService;

/**
 * @group passkey
 */
class PasskeyOrgSettingsPostControllerTest extends AppIntegrationTestCase
{
    /**
     * POST a raw JSON body so typed values (a real boolean) survive: the array based postJson()
     * casts every scalar to a string, which the controller (strict is_bool check) would reject.
     *
     * A raw string body skips the integration helper's automatic CSRF token injection, so the CSRF
     * cookie and matching header are set explicitly here (passbolt accepts any matching pair).
     *
     * @param string $url Endpoint
     * @param array $data Payload
     * @return void
     */
    protected function postJsonBody(string $url, array $data): void
    {
        $token = 'csrf-test-token';
        $this->cookie('csrfToken', $token);
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-CSRF-Token' => $token,
            ],
        ]);
        $this->postJson($url, (string)json_encode($data));
    }

    public function testPasskeyOrgSettingsPost_NotAuthenticated(): void
    {
        $this->postJsonBody('/passkey/organization/settings.json?api-version=v2', ['enabled' => false]);
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testPasskeyOrgSettingsPost_NonAdminForbidden(): void
    {
        $this->logInAsUser();

        $this->postJsonBody('/passkey/organization/settings.json?api-version=v2', ['enabled' => false]);

        $this->assertForbiddenError('Access restricted to administrators.');
    }

    public function testPasskeyOrgSettingsPost_RequiresBoolean(): void
    {
        $this->logInAsAdmin();

        $this->postJsonBody('/passkey/organization/settings.json?api-version=v2', ['enabled' => 'yes']);

        $this->assertBadRequestError('The "enabled" field must be a boolean.');
    }

    public function testPasskeyOrgSettingsPost_AdminPersistsDisabled(): void
    {
        $this->logInAsAdmin();

        $this->postJsonBody('/passkey/organization/settings.json?api-version=v2', ['enabled' => false]);

        $this->assertResponseSuccess();
        $this->assertFalse($this->_responseJsonBody->enabled);
        // The setting is persisted so a subsequent (unauthenticated) GET stays disabled.
        $setting = OrganizationSettingFactory::find()
            ->where(['property' => PasskeyOrganizationSettingsService::SETTINGS_PROPERTY])
            ->firstOrFail();
        $this->assertStringContainsString('"enabled":false', (string)$setting->get('value'));
    }

    public function testPasskeyOrgSettingsPost_AdminCanReEnable(): void
    {
        $this->logInAsAdmin();
        OrganizationSettingFactory::make()
            ->setPropertyAndValue(
                PasskeyOrganizationSettingsService::SETTINGS_PROPERTY,
                ['enabled' => false]
            )
            ->persist();

        $this->postJsonBody('/passkey/organization/settings.json?api-version=v2', ['enabled' => true]);

        $this->assertResponseSuccess();
        $this->assertTrue($this->_responseJsonBody->enabled);
    }
}
