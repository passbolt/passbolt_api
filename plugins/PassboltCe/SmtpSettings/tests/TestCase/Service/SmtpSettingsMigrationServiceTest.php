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
 * @since         3.8.0
 */

namespace Passbolt\SmtpSettings\Test\TestCase\Service;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\UserFactory;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Service\SmtpSettingsMigrationService;
use Passbolt\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsMigrationService
 */
class SmtpSettingsMigrationServiceTest extends TestCase
{
    use FeaturePluginAwareTrait;
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    public function setUp(): void
    {
        parent::setUp();
        $this->gpgSetup();
        UserFactory::make()->admin()->persist();
    }

    public function tearDown(): void
    {
        $this->deletePassboltDummyFile();
        parent::tearDown();
    }

    public function testSmtpSettingsMigrationServiceTest_Plugin_Disabled()
    {
        $this->disableFeaturePlugin('SmtpSettings');

        $service = new SmtpSettingsMigrationService($this->dummyPassboltFile);
        $settings = $service->migrateSmtpSettingsToDb();

        $this->assertSame(0, SmtpSettingFactory::count());
        $this->assertNull($settings);
        $this->enableFeaturePlugin('SmtpSettings');
    }

    /**
     * The case will happen on instance where an administrator has not yet completed its registration (extension setup).
     */
    public function testSmtpSettingsMigrationServiceTest_Valid_File_Source_No_Admin()
    {
        // Remove the user in setUp.
        UserFactory::make()->getTable()->deleteAll([]);
        $this->setTransportConfig();
        $this->makeDummyPassboltFile([
            'EmailTransport' => 'Foo',
            'Email' => 'Bar',
        ]);

        $service = new SmtpSettingsMigrationService($this->dummyPassboltFile);
        $settings = $service->migrateSmtpSettingsToDb();

        $this->assertSame(0, SmtpSettingFactory::count());
        $this->assertNull($settings);
    }

    public function testSmtpSettingsMigrationServiceTest_Valid_File_Source()
    {
        $this->setTransportConfig();
        $this->makeDummyPassboltFile([
            'EmailTransport' => 'Foo',
            'Email' => 'Bar',
        ]);

        $service = new SmtpSettingsMigrationService($this->dummyPassboltFile);
        $settings = $service->migrateSmtpSettingsToDb();

        $this->assertSame(1, SmtpSettingFactory::count());
        $this->assertInstanceOf(OrganizationSetting::class, $settings);
        $this->assertSourceInSettingsIs('file', $settings);
    }

    /**
     * If the passbolt SMTP Settings are not completely defined in passbolt.php
     * Then the file is ignored, the source is considered as env and the settings
     * are not saved in the DB
     */
    public function testSmtpSettingsMigrationServiceTest_Incomplete_File_Source()
    {
        $this->setTransportConfig();
        $this->makeDummyPassboltFile([
            'EmailTransport' => 'Foo',
        ]);

        $service = new SmtpSettingsMigrationService($this->dummyPassboltFile);
        $settings = $service->migrateSmtpSettingsToDb();

        $this->assertSame(0, SmtpSettingFactory::count());
        $this->assertNull($settings);
    }

    public function testSmtpSettingsMigrationServiceTest_Valid_Env_Source()
    {
        $this->setTransportConfig();

        $service = new SmtpSettingsMigrationService($this->dummyPassboltFile);
        $settings = $service->migrateSmtpSettingsToDb();

        $this->assertSame(0, SmtpSettingFactory::count());
        $this->assertNull($settings);
    }

    private function assertSourceInSettingsIs(string $source, ?OrganizationSetting $settings): void
    {
        $this->assertSame($source, $settings['source'] ?? null);
    }
}
