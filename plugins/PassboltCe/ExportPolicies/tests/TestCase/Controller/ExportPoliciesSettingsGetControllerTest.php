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
 * @since         5.10.0
 */

namespace Passbolt\ExportPolicies\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Passbolt\ExportPolicies\ExportPoliciesPlugin;
use Passbolt\ExportPolicies\Model\Dto\ExportPoliciesSettingsDto;

/**
 * @covers \Passbolt\ExportPolicies\Controller\ExportPoliciesSettingsGetController
 */
class ExportPoliciesSettingsGetControllerTest extends AppIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(ExportPoliciesPlugin::class);
    }

    public function testExportPoliciesSettingsGetController_Success_Default(): void
    {
        $this->logInAsUser();

        $this->getJson('/export-policies/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $expectedResponseKeys = ['allow_csv_format', 'source'];
        $this->assertArrayHasAttributes($expectedResponseKeys, $response);
        $expectedResponse = [
            'allow_csv_format' => false,
            'source' => ExportPoliciesSettingsDto::SOURCE_DEFAULT,
        ];
        $this->assertArrayEqualsCanonicalizing($expectedResponse, $response);
    }

    public function testExportPoliciesSettingsGetController_Success_FromEnv(): void
    {
        putenv('PASSBOLT_PLUGINS_EXPORT_POLICIES_ALLOW_CSV_FORMAT=true');
        $this->logInAsUser();

        $this->getJson('/export-policies/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertSame(ExportPoliciesSettingsDto::SOURCE_ENV, $response['source']);
        $this->assertTrue($response['allow_csv_format']);

        // reset env state
        putenv('PASSBOLT_PLUGINS_EXPORT_POLICIES_ALLOW_CSV_FORMAT');
    }

    public function testExportPoliciesSettingsGetController_Success_FromFile(): void
    {
        Configure::write(ExportPoliciesPlugin::EXPORT_POLICIES_ALLOW_CSV_FORMAT_CONFIG_KEY, true);
        $this->logInAsUser();

        $this->getJson('/export-policies/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertSame(ExportPoliciesSettingsDto::SOURCE_FILE, $response['source']);
        $this->assertTrue($response['allow_csv_format']);

        // reset env state
        putenv('PASSBOLT_PLUGINS_EXPORT_POLICIES_ALLOW_CSV_FORMAT');
    }

    public function testExportPoliciesSettingsGetController_Success_FallbackToDefaultsIfInvalidValuesSet(): void
    {
        Configure::write(ExportPoliciesPlugin::EXPORT_POLICIES_ALLOW_CSV_FORMAT_CONFIG_KEY, 'invalid');
        $this->logInAsUser();

        $this->getJson('/export-policies/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertSame(ExportPoliciesSettingsDto::SOURCE_DEFAULT, $response['source']);
        $this->assertFalse($response['allow_csv_format']);
    }

    public function testExportPoliciesSettingsGetController_Error_PluginDisabled(): void
    {
        $this->disableFeaturePlugin(ExportPoliciesPlugin::class);
        $this->get('/export-policies/settings.json');
        $this->assertResponseCode(404);
    }

    public function testExportPoliciesSettingsGetController_Error_Unauthorized(): void
    {
        $this->getJson('/export-policies/settings.json');
        $this->assertAuthenticationError();
    }
}
