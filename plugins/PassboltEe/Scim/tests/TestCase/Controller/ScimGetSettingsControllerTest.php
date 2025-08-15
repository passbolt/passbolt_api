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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Test\TestCase\Controller;

use App\Model\Entity\OrganizationSetting;
use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Passbolt\Scim\ScimPlugin;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;
use Passbolt\Scim\Test\Utility\ScimSettingsIntegrationTestCase;
use Throwable;

/**
 * @covers \Passbolt\Scim\Controller\ScimGetSettingsController
 */
class ScimGetSettingsControllerTest extends ScimSettingsIntegrationTestCase
{
    use OpenPGPCommonServerOperationsTrait;

    protected OrganizationSetting $current;

    public function setUp(): void
    {
        parent::setUp();
        $this->current = ScimSettingFactory::make()->default()->persist();
    }

    /**
     * Test getSettings method: plugin disabled
     *
     * @return void
     */
    public function testDeleteSettings_Error_PluginDisabled(): void
    {
        $this->disableFeaturePlugin(ScimPlugin::class);

        try {
            $this->getJson('/scim/settings.json');
        } catch (Throwable $t) {
        }

        $this->assertResponseCode(404);
    }

    /**
     * Test getSettings method: guest forbidden
     *
     * @return void
     */
    public function testGetSettings_Error_GuestForbidden(): void
    {
        $this->logInAsUser();

        $this->getJson('/scim/settings.json');

        $this->assertResponseCode(403);
    }

    /**
     * Test getSettings method: unauthenticated
     *
     * @return void
     */
    public function testGetSettings_Error_Unauthenticated()
    {
        $this->getJson('/scim/settings.json');

        $this->assertResponseCode(401);
    }

    /**
     * Test getSettings method: not a json request
     *
     * @return void
     */
    public function testGetSettings_Error_NotJson()
    {
        $this->logInAsAdmin();
        $this->get("/scim/settings");
        $this->assertResponseCode(404);
    }

    /**
     * Test getSettings method: success
     *
     * @return void
     */
    public function testGetSettings_Success(): void
    {
        $this->logInAsAdmin();

        $this->getJson('/scim/settings.json');

        $response = $this->_responseJsonBody;
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $data = json_decode($gpg->decrypt($this->current->value), associative: true);
        $this->assertSuccess();
        $this->assertSame($data['setting_id'], $response->setting_id);
        $this->assertSame($data['scim_user_id'], $response->scim_user_id);
        $this->assertEmpty($response->secret_token ?? null);
        $this->assertObjectHasAttribute('id', $response);
    }
}
