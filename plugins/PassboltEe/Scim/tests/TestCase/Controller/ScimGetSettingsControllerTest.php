<?php
declare(strict_types=1);

namespace Passbolt\Scim\Test\TestCase\Controller;

use App\Model\Entity\OrganizationSetting;
use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Scim\ScimPlugin;
use Passbolt\Scim\Test\Factory\ScimOrgSettingFactory;
use Throwable;

/**
 * Passbolt\Scim\Controller\ScimGetSettingsController Test Case
 */
class ScimGetSettingsControllerTest extends AppIntegrationTestCase
{
    use OpenPGPCommonServerOperationsTrait;

    protected OrganizationSetting $current;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(ScimPlugin::class);
        ScimOrgSettingFactory::make()->default()->persist();
        $this->current = TableRegistry::getTableLocator()->get('OrganizationSettings')->find()->first();
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
