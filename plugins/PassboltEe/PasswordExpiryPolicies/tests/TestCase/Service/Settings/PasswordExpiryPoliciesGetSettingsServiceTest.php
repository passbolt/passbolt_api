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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiryPolicies\Test\TestCase\Service\Settings;

use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;
use Passbolt\PasswordExpiry\Test\Lib\PasswordExpiryTestTrait;
use Passbolt\PasswordExpiryPolicies\Service\Settings\PasswordExpiryPoliciesGetSettingsService;
use Passbolt\PasswordExpiryPolicies\Test\Factory\PasswordExpiryPoliciesSettingFactory;

class PasswordExpiryPoliciesGetSettingsServiceTest extends TestCase
{
    use PasswordExpiryTestTrait;
    use TruncateDirtyTables;

    private PasswordExpiryPoliciesGetSettingsService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new PasswordExpiryPoliciesGetSettingsService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testPasswordExpiryPoliciesGetSettingsService_Success_SettingsNotInDB()
    {
        $this->assertSame([
            PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => false,
            PasswordExpirySettingsDto::AUTOMATIC_UPDATE => false,
            PasswordExpirySettingsDto::POLICY_OVERRIDE => false,
            PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => null,
            PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => null,
        ], $this->service->get()->toArray());
    }

    public function testPasswordExpiryPoliciesGetSettingsService_Success_SettingsInDB()
    {
        $settingInDB = PasswordExpiryPoliciesSettingFactory::make()->persist();
        $result = $this->service->get()->toArray();
        $this->assertSame(
            $settingInDB->get('value')[PasswordExpirySettingsDto::POLICY_OVERRIDE],
            $result[PasswordExpirySettingsDto::AUTOMATIC_EXPIRY]
        );
        $this->assertSame(
            $settingInDB->get('value')[PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD],
            $result[PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD]
        );
        $this->assertSame(
            $settingInDB->get('value')[PasswordExpirySettingsDto::EXPIRY_NOTIFICATION],
            $result[PasswordExpirySettingsDto::EXPIRY_NOTIFICATION]
        );
        $this->assertPasswordExpirySettingsMatchesEntity($settingInDB, $result);
    }

    public function testPasswordExpiryPoliciesGetSettingsService_Error_SettingsInDB_Not_Array()
    {
        PasswordExpirySettingFactory::make()->setField('value', 'foo')->persist();
        $this->expectException(InternalErrorException::class);
        $this->service->get();
    }
}
