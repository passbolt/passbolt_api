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

namespace Passbolt\ExportPolicies\Test\TestCase\Service;

use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Passbolt\ExportPolicies\ExportPoliciesPlugin;
use Passbolt\ExportPolicies\Model\Dto\ExportPoliciesSettingsDto;
use Passbolt\ExportPolicies\Service\ExportPoliciesGetSettingsService;

/**
 * @covers \Passbolt\ExportPolicies\Service\ExportPoliciesGetSettingsService
 */
class ExportPoliciesGetSettingsServiceTest extends AppTestCase
{
    private ?ExportPoliciesGetSettingsService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ExportPoliciesGetSettingsService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    /**
     * Default settings returned when nothing is set.
     *
     * @return void
     */
    public function testExportPoliciesGetSettingsService_Default(): void
    {
        $result = $this->service->get();

        $this->assertInstanceOf(ExportPoliciesSettingsDto::class, $result);
        $expectedSettings = [
            'allow_csv_format' => false,
            'source' => ExportPoliciesSettingsDto::SOURCE_DEFAULT,
        ];
        $this->assertArrayEqualsCanonicalizing($expectedSettings, $result->toArray());
    }

    /**
     * Settings from config file.
     *
     * @return void
     */
    public function testExportPoliciesGetSettingsService_FromFile(): void
    {
        Configure::write(ExportPoliciesPlugin::EXPORT_POLICIES_ALLOW_CSV_FORMAT_CONFIG_KEY, true);

        $result = $this->service->get();

        $this->assertInstanceOf(ExportPoliciesSettingsDto::class, $result);
        $this->assertSame(ExportPoliciesSettingsDto::SOURCE_FILE, $result->source);
        $this->assertTrue($result->allow_csv_format);
    }

    /**
     * Settings from environment variables.
     *
     * @return void
     */
    public function testExportPoliciesGetSettingsService_FromEnv(): void
    {
        putenv('PASSBOLT_PLUGINS_EXPORT_POLICIES_ALLOW_CSV_FORMAT=true');

        $result = $this->service->get();

        $this->assertInstanceOf(ExportPoliciesSettingsDto::class, $result);
        $this->assertSame(ExportPoliciesSettingsDto::SOURCE_ENV, $result->source);
        $this->assertTrue($result->allow_csv_format);

        // reset env state
        putenv('PASSBOLT_PLUGINS_EXPORT_POLICIES_ALLOW_CSV_FORMAT');
    }

    /**
     * File config takes priority over env.
     *
     * @return void
     */
    public function testExportPoliciesGetSettingsService_FilePriorityOverEnv(): void
    {
        Configure::write(ExportPoliciesPlugin::EXPORT_POLICIES_ALLOW_CSV_FORMAT_CONFIG_KEY, true);

        putenv('PASSBOLT_PLUGINS_EXPORT_POLICIES_ALLOW_CSV_FORMAT=false');

        $result = $this->service->get();

        $this->assertSame(ExportPoliciesSettingsDto::SOURCE_FILE, $result->source);
        $this->assertTrue($result->allow_csv_format);

        // reset env state
        putenv('PASSBOLT_PLUGINS_EXPORT_POLICIES_ALLOW_CSV_FORMAT');
    }

    /**
     * Invalid values fall back to defaults.
     *
     * @return void
     */
    public function testExportPoliciesGetSettingsService_InvalidValueFallbackToDefault(): void
    {
        putenv('PASSBOLT_PLUGINS_EXPORT_POLICIES_ALLOW_CSV_FORMAT=foo-bar');

        $result = $this->service->get();

        // Should fall back to defaults due to validation failure
        $expectedSettings = ExportPoliciesSettingsDto::createFromDefault()->toArray();
        $this->assertArrayEqualsCanonicalizing($expectedSettings, $result->toArray());

        // reset env state
        putenv('PASSBOLT_PLUGINS_EXPORT_POLICIES_ALLOW_CSV_FORMAT');
    }
}
