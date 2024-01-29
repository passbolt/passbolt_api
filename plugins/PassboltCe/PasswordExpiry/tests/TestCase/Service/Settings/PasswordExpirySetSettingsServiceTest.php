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

namespace Passbolt\PasswordExpiry\Test\TestCase\Service\Settings;

use App\Error\Exception\FormValidationException;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\ExtendedUserAccessControlTestTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpirySetSettingsService;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;
use Passbolt\PasswordExpiry\Test\Lib\PasswordExpiryTestTrait;

/**
 * @see \Passbolt\PasswordExpiry\Service\Settings\PasswordExpirySetSettingsService
 */
class PasswordExpirySetSettingsServiceTest extends AppTestCase
{
    use ExtendedUserAccessControlTestTrait;
    use PasswordExpiryTestTrait;

    private PasswordExpirySetSettingsService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new PasswordExpirySetSettingsService();
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

    public function testPasswordExpirySetSettingsServiceCreateOrUpdate_Success_CreateWithDefaultValues()
    {
        $uac = $this->mockExtendedAdminAccessControl();

        $result = $this->service->createOrUpdate($uac, $this->getValidPasswordExpiryPayload());

        $this->assertInstanceOf(PasswordExpirySettingsDto::class, $result);
        $this->assertNotNull($result->id);
        $this->assertNotNull($result->created);
        $this->assertNotNull($result->created_by);
        $this->assertNotNull($result->modified);
        $this->assertNotNull($result->modified_by);
        $settings = PasswordExpirySettingFactory::find()->toArray();
        $this->assertCount(1, $settings);
        $this->assertSame($this->getDefaultPasswordExpirySettings(), $settings[0]->value);
        $this->assertSame($uac->getId(), $settings[0]->created_by);
        $this->assertSame($uac->getId(), $settings[0]->modified_by);
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(PasswordExpirySetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testPasswordExpirySetSettingsServiceCreateOrUpdate_Success_Update()
    {
        $oldValue = 'foo';
        $existingSetting = PasswordExpirySettingFactory::make()->value($oldValue)->persist();
        $uac = $this->mockExtendedAdminAccessControl();

        $result = $this->service->createOrUpdate($uac, $this->getValidPasswordExpiryPayload());
        $this->assertSame($existingSetting->get('id'), $result->id);
        $this->assertNotEquals($oldValue, $result->getValue());
        $this->assertSame(1, PasswordExpirySettingFactory::count());
    }

    public function testPasswordExpirySetSettingsServiceCreateOrUpdate_DataError()
    {
        $uac = $this->mockExtendedAdminAccessControl();

        $this->expectException(FormValidationException::class);
        $this->expectExceptionMessage('Could not validate the password expiry settings.');
        $this->service->createOrUpdate($uac, [
            PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
            PasswordExpirySettingsDto::AUTOMATIC_UPDATE => false,
        ]);
    }
}
