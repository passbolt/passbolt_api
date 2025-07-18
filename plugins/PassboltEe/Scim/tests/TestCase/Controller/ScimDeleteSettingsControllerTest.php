<?php
declare(strict_types=1);

namespace Passbolt\Scim\Test\TestCase\Controller;

use App\Model\Entity\OrganizationSetting;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Scim\ScimPlugin;
use Passbolt\Scim\Test\Factory\ScimOrgSettingFactory;

/**
 * Passbolt\Scim\Controller\ScimDeleteSettingsController Test Case
 */
class ScimDeleteSettingsControllerTest extends AppIntegrationTestCase
{

    protected OrganizationSetting $current;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(ScimPlugin::class);
        ScimOrgSettingFactory::make()->default()->persist();
        $this->current = TableRegistry::getTableLocator()->get('OrganizationSettings')->find()->first();
    }

    /**
     * Test deleteSettings method: plugin disabled
     *
     * @return void
     */
    public function testDeleteSettings_Error_PluginDisabled(): void
    {
        $this->disableFeaturePlugin(ScimPlugin::class);

        try {
            $this->deleteJson("/scim/settings/{$this->current->id}.json");
        } catch (\Throwable $t) {}

        $this->assertResponseCode(404);
    }

    /**
     * Test deleteSettings method: guest forbidden
     *
     * @return void
     */
    public function testDeleteSettings_Error_GuestForbidden(): void
    {
        $this->logInAsUser();

        $this->deleteJson("/scim/settings/{$this->current->id}.json");

        $this->assertResponseCode(403);
    }

    /**
     * Test deleteSettings method: unauthenticated
     *
     * @return void
     */
    public function testDeleteSettings_Error_Unauthenticated()
    {
        $this->deleteJson("/scim/settings/{$this->current->id}.json");

        $this->assertResponseCode(401);
    }

    /**
     * Test deleteSettings method: wrong UUID
     *
     * @return void
     */
    public function  testDeleteSettings_WrongUUID()
    {
        $this->logInAsAdmin();

        $wrongUuid = UuidFactory::uuid();

        $this->deleteJson("/scim/settings/{$wrongUuid}.json");

        $this->assertResponseCode(404);
    }

    /**
     * Test deleteSettings method: success
     *
     * @return void
     */
    public function  testDeleteSettings_Success()
    {
        $this->logInAsAdmin();

        $this->deleteJson("/scim/settings/{$this->current->id}.json");

        $this->assertSuccess();
    }
}
