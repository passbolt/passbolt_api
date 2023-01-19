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
 * @since         3.10.0
 */

namespace Passbolt\SelfRegistration\Test\TestCase\Service;

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SelfRegistration\Service\SelfRegistrationDeleteSettingsService;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

/**
 * @covers \Passbolt\SelfRegistration\Service\SelfRegistrationDeleteSettingsService
 */
class SelfRegistrationDeleteSettingsServiceTest extends TestCase
{
    use SelfRegistrationTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SelfRegistrationDeleteSettingsService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SelfRegistrationDeleteSettingsService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSelfRegistrationDeleteSettingsService_Valid()
    {
        $settingInDB = $this->setSelfRegistrationSettingsData();
        $result = $this->service->deleteSettings(
            UserFactory::make()->admin()->nonPersistedUAC(),
            $settingInDB->get('id')
        );
        $this->assertTrue($result);
        $this->assertSame(0, OrganizationSettingFactory::count());
    }

    public function testSelfRegistrationDeleteSettingsService_Wrong_ID()
    {
        $this->setSelfRegistrationSettingsData();
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The self registration setting does not exist.');
        $this->service->deleteSettings(
            UserFactory::make()->admin()->nonPersistedUAC(),
            'foo'
        );
    }

    public function testSelfRegistrationDeleteSettingsService_Empty_ID()
    {
        $this->setSelfRegistrationSettingsData();
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The self registration setting does not exist.');
        $this->service->deleteSettings(
            UserFactory::make()->admin()->nonPersistedUAC(),
            ''
        );
    }
}
