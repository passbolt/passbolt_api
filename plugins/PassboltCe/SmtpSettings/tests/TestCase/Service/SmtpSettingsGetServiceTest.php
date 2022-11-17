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

use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Service\SmtpSettingsGetService;
use Passbolt\SmtpSettings\Service\SmtpSettingsSetService;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsGetService
 */
class SmtpSettingsGetServiceTest extends TestCase
{
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    /**
     * @var SmtpSettingsGetService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SmtpSettingsGetService();
        $this->loadPlugins(['Passbolt/SmtpSettings' => []]);
    }

    public function tearDown(): void
    {
        unset($this->service);
        $this->deletePassboltDummyFile();
        parent::tearDown();
    }

    public function testSmtpSettingsGetServiceTest_Valid_File_Source()
    {
        $this->setTransportConfig();
        $this->makeDummyPassboltFile([
            'EmailTransport' => 'Foo',
            'Email' => 'Bar',
        ]);

        $service = new SmtpSettingsGetService($this->dummyPassboltFile);
        $settings = $service->getSettings();

        $this->assertSame('file', $settings['source']);
        unset($settings['source']);
        $this->assertFileSettingsHaveTheRightKeys($settings);
    }

    public function testSmtpSettingsGetServiceTest_Incomplete_File_Source()
    {
        $this->setTransportConfig();
        $this->makeDummyPassboltFile([
            'EmailTransport' => 'Foo',
        ]);

        $service = new SmtpSettingsGetService($this->dummyPassboltFile);
        $settings = $service->getSettings();

        $this->assertSame('env', $settings['source']);
        unset($settings['source']);
        $this->assertFileSettingsHaveTheRightKeys($settings);
    }

    public function testSmtpSettingsGetServiceTest_Valid_Env_Source()
    {
        $this->setTransportConfig();

        $service = new SmtpSettingsGetService($this->dummyPassboltFile);
        $settings = $service->getSettings();

        $this->assertSame('env', $settings['source']);
        unset($settings['source']);
        $this->assertFileSettingsHaveTheRightKeys($settings);
    }

    public function testSmtpSettingsGetServiceTest_Valid_DB_Source()
    {
        $data = $this->getSmtpSettingsData();
        $this->encryptAndPersistSmtpSettings($data);

        $settings = $this->service->getSettings();

        $this->assertSame('db', $settings['source']);
        unset($settings['source']);
        $this->assertDBSettingsHaveTheRightKeys($settings);
    }

    public function testSmtpSettingsGetServiceTest_Valid_DB_Source_With_Invalid_Sender_Email_Should_Not_Fail()
    {
        $data = $this->getSmtpSettingsData('sender_email', 'foo');
        $this->encryptAndPersistSmtpSettings($data);

        $settings = $this->service->getSettings();

        $this->assertSame('db', $settings['source']);
        unset($settings['source']);
        $this->assertDBSettingsHaveTheRightKeys($settings);
    }

    public function testSmtpSettingsGetServiceTest_Valid_Integration_With_SetService()
    {
        $this->gpgSetup();
        $data = $this->getSmtpSettingsData();

        $setService = new SmtpSettingsSetService($this->mockAdminAccessControl());
        $savedSettings = $setService->saveSettings($data);

        $service = new SmtpSettingsGetService();
        $settings = $service->getSettings();

        $this->assertEquals($savedSettings->created->format('yy-m-d H:m:s'), $settings['created']->format('yy-m-d H:m:s'));
        $this->assertEquals($savedSettings->modified->format('yy-m-d H:m:s'), $settings['modified']->format('yy-m-d H:m:s'));
        unset($settings['created']);
        unset($settings['modified']);

        $expectedSettings = array_merge(
            ['id' => $savedSettings->id,],
            $data,
            [
                'created_by' => $savedSettings->created_by,
                'modified_by' => $savedSettings->modified_by,
            ],
            ['source' => 'db']
        );

        $this->assertEquals($expectedSettings, $settings);
    }
}
