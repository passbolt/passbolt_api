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

namespace Passbolt\MfaPolicies\Test\TestCase\Service;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\ExtendedUserAccessControlTestTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings;
use Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting;
use Passbolt\MfaPolicies\Service\MfaPoliciesSetSettingsService;
use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;

/**
 * @see \Passbolt\MfaPolicies\Service\MfaPoliciesSetSettingsService
 */
class MfaPoliciesSetSettingsServiceTest extends AppTestCase
{
    use ExtendedUserAccessControlTestTrait;

    /**
     * @var MfaPoliciesSetSettingsService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MfaPoliciesSetSettingsService();
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

    public function testCreateOrUpdate_Error_Unauthorized()
    {
        $uac = $this->mockExtendedUserAccessControl();

        $this->expectException(ForbiddenException::class);
        $this->expectErrorMessage('administrators are allowed to create/update MFA policies settings');

        $this->service->createOrUpdate($uac, MfaPolicySettings::createFromArray([
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ]));
    }

    public function testCreateOrUpdate_Success_CreateWithDefaultValues()
    {
        $uac = $this->mockExtendedAdminAccessControl();

        $result = $this->service->createOrUpdate($uac, MfaPolicySettings::createFromArray([
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ]));

        $this->assertInstanceOf(MfaPolicySettings::class, $result);
        $this->assertSame(MfaPoliciesSetting::POLICY_OPT_IN, $result->policy);
        $this->assertTrue($result->remember_me_for_a_month);
        $this->assertNotNull($result->id);
        $this->assertNotNull($result->created);
        $this->assertNotNull($result->created_by);
        $this->assertNotNull($result->modified);
        $this->assertNotNull($result->modified_by);
        $settings = MfaPoliciesSettingFactory::find()->toArray();
        $this->assertCount(1, $settings);
        $this->assertSame([
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ], $settings[0]->value);
        $this->assertSame($uac->getId(), $settings[0]->created_by);
        $this->assertSame($uac->getId(), $settings[0]->modified_by);
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(
            MfaPoliciesSetSettingsService::EVENT_SETTINGS_UPDATED,
            'mfaPoliciesSetting',
            $result
        );
        $this->assertEventFiredWith(MfaPoliciesSetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testCreateOrUpdate_Success_CreateWithNonDefaultValues()
    {
        $uac = $this->mockExtendedAdminAccessControl();

        $result = $this->service->createOrUpdate($uac, MfaPolicySettings::createFromArray([
            'policy' => MfaPoliciesSetting::POLICY_MANDATORY,
            'remember_me_for_a_month' => false,
        ]));

        $this->assertInstanceOf(MfaPolicySettings::class, $result);
        $this->assertSame(MfaPoliciesSetting::POLICY_MANDATORY, $result->policy);
        $this->assertFalse($result->remember_me_for_a_month);
        $this->assertNotNull($result->id);
        $this->assertNotNull($result->created);
        $this->assertNotNull($result->created_by);
        $this->assertNotNull($result->modified);
        $this->assertNotNull($result->modified_by);
        $settings = MfaPoliciesSettingFactory::find()->toArray();
        $this->assertCount(1, $settings);
        $this->assertSame([
            'policy' => MfaPoliciesSetting::POLICY_MANDATORY,
            'remember_me_for_a_month' => false,
        ], $settings[0]->value);
        $this->assertSame($uac->getId(), $settings[0]->created_by);
        $this->assertSame($uac->getId(), $settings[0]->modified_by);
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(
            MfaPoliciesSetSettingsService::EVENT_SETTINGS_UPDATED,
            'mfaPoliciesSetting',
            $result
        );
        $this->assertEventFiredWith(MfaPoliciesSetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testCreateOrUpdate_Success_UpdateWithDefaultValues()
    {
        MfaPoliciesSettingFactory::make()
            ->setPolicy(MfaPoliciesSetting::POLICY_MANDATORY)
            ->setRememberMeForAMonth(false)
            ->persist();
        $uac = $this->mockExtendedAdminAccessControl();

        $result = $this->service->createOrUpdate($uac, MfaPolicySettings::createFromArray([
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ]));

        $this->assertInstanceOf(MfaPolicySettings::class, $result);
        $this->assertSame(MfaPoliciesSetting::POLICY_OPT_IN, $result->policy);
        $this->assertTrue($result->remember_me_for_a_month);
        $this->assertNotNull($result->id);
        $this->assertNotNull($result->created);
        $this->assertNotNull($result->created_by);
        $this->assertNotNull($result->modified);
        $this->assertNotNull($result->modified_by);
        $settings = MfaPoliciesSettingFactory::find()->toArray();
        $this->assertCount(1, $settings);
        $this->assertSame([
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ], $settings[0]->value);
        $this->assertSame($uac->getId(), $settings[0]->modified_by);
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(
            MfaPoliciesSetSettingsService::EVENT_SETTINGS_UPDATED,
            'mfaPoliciesSetting',
            $result
        );
        $this->assertEventFiredWith(MfaPoliciesSetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testCreateOrUpdate_Success_UpdateWithNonDefaultValues()
    {
        MfaPoliciesSettingFactory::make()
            ->setPolicy(MfaPoliciesSetting::POLICY_OPT_IN)
            ->setRememberMeForAMonth(true)
            ->persist();
        $uac = $this->mockExtendedAdminAccessControl();

        $result = $this->service->createOrUpdate($uac, MfaPolicySettings::createFromArray([
            'policy' => MfaPoliciesSetting::POLICY_MANDATORY,
            'remember_me_for_a_month' => false,
        ]));

        $this->assertInstanceOf(MfaPolicySettings::class, $result);
        $this->assertSame(MfaPoliciesSetting::POLICY_MANDATORY, $result->policy);
        $this->assertFalse($result->remember_me_for_a_month);
        $this->assertNotNull($result->id);
        $this->assertNotNull($result->created);
        $this->assertNotNull($result->created_by);
        $this->assertNotNull($result->modified);
        $this->assertNotNull($result->modified_by);
        $settings = MfaPoliciesSettingFactory::find()->toArray();
        $this->assertCount(1, $settings);
        $this->assertSame([
            'policy' => MfaPoliciesSetting::POLICY_MANDATORY,
            'remember_me_for_a_month' => false,
        ], $settings[0]->value);
        $this->assertSame($uac->getId(), $settings[0]->modified_by);
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(
            MfaPoliciesSetSettingsService::EVENT_SETTINGS_UPDATED,
            'mfaPoliciesSetting',
            $result
        );
        $this->assertEventFiredWith(MfaPoliciesSetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }
}
