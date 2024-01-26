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

use App\Error\Exception\FormValidationException;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\ExtendedUserAccessControlTestTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpirySetSettingsService;
use Passbolt\PasswordExpiry\Test\Lib\PasswordExpiryTestTrait;
use Passbolt\PasswordExpiryPolicies\Service\Settings\PasswordExpiryPoliciesSetSettingsService;
use Passbolt\PasswordExpiryPolicies\Test\Factory\PasswordExpiryPoliciesSettingFactory;

/**
 * @see \Passbolt\PasswordExpiry\Service\Settings\PasswordExpirySetSettingsService
 */
class PasswordExpiryPoliciesSetSettingsServiceTest extends AppTestCase
{
    use ExtendedUserAccessControlTestTrait;
    use PasswordExpiryTestTrait;

    private PasswordExpiryPoliciesSetSettingsService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new PasswordExpiryPoliciesSetSettingsService();
        // Enable event tracking, required to test events.
        EventManager::instance()->setEventList(new EventList());
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testPasswordExpiryPoliciesSetSettingsServiceCreateOrUpdate_Success_CreateWithDefaultValues()
    {
        $uac = $this->mockExtendedAdminAccessControl();

        $payload = PasswordExpiryPoliciesSettingFactory::make()->getEntity()->value;
        $result = $this->service->createOrUpdate($uac, $payload);

        $this->assertInstanceOf(PasswordExpirySettingsDto::class, $result);
        $this->assertNotNull($result->id);
        $this->assertNotNull($result->created);
        $this->assertNotNull($result->created_by);
        $this->assertNotNull($result->modified);
        $this->assertNotNull($result->modified_by);
        $settings = PasswordExpiryPoliciesSettingFactory::firstOrFail();
        $this->assertSame(1, PasswordExpiryPoliciesSettingFactory::count());
        $this->assertSame($payload, $settings->value);
        $this->assertSame($uac->getId(), $settings->created_by);
        $this->assertSame($uac->getId(), $settings->modified_by);
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(PasswordExpirySetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testPasswordExpiryPoliciesSetSettingsServiceCreateOrUpdate_Success_Update()
    {
        $oldValue = 'foo';
        $existingSetting = PasswordExpiryPoliciesSettingFactory::make()->value($oldValue)->persist();
        $uac = $this->mockExtendedAdminAccessControl();

        $payload = PasswordExpiryPoliciesSettingFactory::make()->getEntity()->value;
        $result = $this->service->createOrUpdate($uac, $payload);
        $this->assertSame($existingSetting->get('id'), $result->id);
        $settings = PasswordExpiryPoliciesSettingFactory::firstOrFail();
        $this->assertSame(1, PasswordExpiryPoliciesSettingFactory::count());
        $this->assertSame($payload, $settings->value);
    }

    public function testPasswordExpiryPoliciesSetSettingsServiceCreateOrUpdate_DataError()
    {
        $uac = $this->mockExtendedAdminAccessControl();

        $this->expectException(FormValidationException::class);
        $this->expectExceptionMessage('Could not validate the password expiry settings.');
        $this->service->createOrUpdate($uac, [
            PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
            PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
            PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => 0,
        ]);
    }
}
