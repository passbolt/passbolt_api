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
 * @since         4.3.0
 */

namespace Passbolt\UserPassphrasePolicies\Test\TestCase\Service;

use App\Error\Exception\FormValidationException;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\ExtendedUserAccessControlTestTrait;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\UserPassphrasePolicies\Model\Dto\UserPassphrasePoliciesSettingsDto;
use Passbolt\UserPassphrasePolicies\Service\UserPassphrasePoliciesSetSettingsService;
use Passbolt\UserPassphrasePolicies\Test\Factory\UserPassphrasePoliciesSettingFactory;
use Passbolt\UserPassphrasePolicies\UserPassphrasePoliciesPlugin;

/**
 * @covers \Passbolt\UserPassphrasePolicies\Service\UserPassphrasePoliciesSetSettingsService
 */
class UserPassphrasePoliciesSetSettingsServiceTest extends AppTestCase
{
    use ExtendedUserAccessControlTestTrait;

    /**
     * @var \Passbolt\UserPassphrasePolicies\Service\UserPassphrasePoliciesSetSettingsService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(UserPassphrasePoliciesPlugin::class);
        $this->service = new UserPassphrasePoliciesSetSettingsService();
        // Enable event tracking, required to test events.
        EventManager::instance()->setEventList(new EventList());
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        $this->disableFeaturePlugin(UserPassphrasePoliciesPlugin::class);

        parent::tearDown();
    }

    public function testPasswordPoliciesSetSettingsService_Error_GuestForbidden()
    {
        $user = UserFactory::make()->guest()->persist();
        $uac = $this->makeExtendedUac($user, '127.0.0.1', 'phpunit');

        $this->expectException(ForbiddenException::class);
        $this->expectErrorMessage('Only administrators are allowed');

        $this->service->createOrUpdate($uac, []);
    }

    public function testPasswordPoliciesSetSettingsService_Error_UserForbidden()
    {
        $uac = $this->mockExtendedUserAccessControl();

        $this->expectException(ForbiddenException::class);
        $this->expectErrorMessage('Only administrators are allowed');

        $this->service->createOrUpdate($uac, []);
    }

    public function testPasswordPoliciesSetSettingsService_Error_InvalidData()
    {
        UserPassphrasePoliciesSettingFactory::make()->persist();
        $uac = $this->mockExtendedAdminAccessControl();

        $this->expectException(FormValidationException::class);

        $this->service->createOrUpdate($uac, [
            'entropy_minimum' => 'foo-bar',
            'external_dictionary_check' => 99.99,
        ]);
    }

    public function testPasswordPoliciesSetSettingsService_Success_CreateWithDefault()
    {
        $uac = $this->mockExtendedAdminAccessControl();
        $data = [
            'entropy_minimum' => UserPassphrasePoliciesSettingsDto::ENTROPY_MINIMUM_DEFAULT,
            'external_dictionary_check' => true,
        ];

        $result = $this->service->createOrUpdate($uac, $data);

        $this->assertSame(1, UserPassphrasePoliciesSettingFactory::find()->count());
        $this->assertInstanceOf(UserPassphrasePoliciesSettingsDto::class, $result);
        $this->assertIsString($result->id);
        $this->assertSame($uac->getId(), $result->created_by);
        $this->assertSame($uac->getId(), $result->modified_by);
        $this->assertSame(UserPassphrasePoliciesSettingsDto::SOURCE_DATABASE, $result->source);
        $this->assertArrayEqualsCanonicalizing($data, $result->toOrganizationSettingValueArray());
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(
            UserPassphrasePoliciesSetSettingsService::EVENT_SETTINGS_UPDATED,
            'userPassphrasePoliciesSetting',
            $result
        );
        $this->assertEventFiredWith(UserPassphrasePoliciesSetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testPasswordPoliciesSetSettingsService_Success_CreateWithCustom()
    {
        $uac = $this->mockExtendedAdminAccessControl();
        $data = [
            'entropy_minimum' => 192,
            'external_dictionary_check' => false,
        ];

        $result = $this->service->createOrUpdate($uac, $data);

        $this->assertSame(1, UserPassphrasePoliciesSettingFactory::find()->count());
        $this->assertInstanceOf(UserPassphrasePoliciesSettingsDto::class, $result);
        $this->assertIsString($result->id);
        $this->assertSame($uac->getId(), $result->created_by);
        $this->assertSame($uac->getId(), $result->modified_by);
        $this->assertSame(UserPassphrasePoliciesSettingsDto::SOURCE_DATABASE, $result->source);
        $this->assertArrayEqualsCanonicalizing($data, $result->toOrganizationSettingValueArray());
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(
            UserPassphrasePoliciesSetSettingsService::EVENT_SETTINGS_UPDATED,
            'userPassphrasePoliciesSetting',
            $result
        );
        $this->assertEventFiredWith(UserPassphrasePoliciesSetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testPasswordPoliciesSetSettingsService_Success_Update()
    {
        UserPassphrasePoliciesSettingFactory::make()->persist();
        $uac = $this->mockExtendedAdminAccessControl();
        $data = [
            'entropy_minimum' => 160,
            'external_dictionary_check' => false,
        ];

        $result = $this->service->createOrUpdate($uac, $data);

        $this->assertInstanceOf(UserPassphrasePoliciesSettingsDto::class, $result);
        $this->assertSame(1, UserPassphrasePoliciesSettingFactory::find()->count());
        $this->assertIsString($result->id);
        $this->assertSame($uac->getId(), $result->modified_by);
        $this->assertSame(UserPassphrasePoliciesSettingsDto::SOURCE_DATABASE, $result->source);
        $this->assertArrayEqualsCanonicalizing($data, $result->toOrganizationSettingValueArray());
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(
            UserPassphrasePoliciesSetSettingsService::EVENT_SETTINGS_UPDATED,
            'userPassphrasePoliciesSetting',
            $result
        );
        $this->assertEventFiredWith(UserPassphrasePoliciesSetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testPasswordPoliciesSetSettingsService_Success_FilterOutUnwantedData()
    {
        UserPassphrasePoliciesSettingFactory::make()->persist();
        $uac = $this->mockExtendedAdminAccessControl();
        $data = [
            'entropy_minimum' => 160,
            'external_dictionary_check' => false,
            'unwanted_data' => 'garbage#";',
            'created_by' => UuidFactory::uuid(),
            'modified_by' => UuidFactory::uuid(),
        ];

        $result = $this->service->createOrUpdate($uac, $data);

        $this->assertInstanceOf(UserPassphrasePoliciesSettingsDto::class, $result);
        $this->assertObjectNotHasAttribute('unwanted_data', $result);
        $this->assertNotSame($data['created_by'], $result->created_by);
        $this->assertNotSame($data['modified_by'], $result->modified_by);
    }
}
