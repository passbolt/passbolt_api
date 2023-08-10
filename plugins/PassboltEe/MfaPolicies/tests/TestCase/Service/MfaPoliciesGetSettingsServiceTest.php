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

use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings;
use Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting;
use Passbolt\MfaPolicies\Service\MfaPoliciesGetSettingsService;
use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;

class MfaPoliciesGetSettingsServiceTest extends TestCase
{
    use UserAccessControlTrait;
    use TruncateDirtyTables;

    /**
     * @var MfaPoliciesGetSettingsService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MfaPoliciesGetSettingsService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testGet_SuccessDefaultValues_SettingsNotInDB()
    {
        $result = $this->service->get();

        $this->assertInstanceOf(MfaPolicySettings::class, $result);
        $this->assertSame(MfaPoliciesSetting::POLICY_OPT_IN, $result->policy);
        $this->assertTrue($result->remember_me_for_a_month);
        $this->assertNull($result->id);
        $this->assertNull($result->created);
        $this->assertNull($result->created_by);
        $this->assertNull($result->modified);
        $this->assertNull($result->modified_by);
    }

    public function testGet_SuccessOptIn_SettingsInDB()
    {
        MfaPoliciesSettingFactory::make()->persist();

        $result = $this->service->get();

        $this->assertInstanceOf(MfaPolicySettings::class, $result);
        $this->assertSame(MfaPoliciesSetting::POLICY_OPT_IN, $result->policy);
        $this->assertTrue($result->remember_me_for_a_month);
        $this->assertNotNull($result->id);
        $this->assertNotNull($result->created);
        $this->assertNotNull($result->created_by);
        $this->assertNotNull($result->modified);
        $this->assertNotNull($result->modified_by);
    }

    public function testGet_SuccessMandatory_SettingsInDB()
    {
        MfaPoliciesSettingFactory::make()
            ->setPolicy(MfaPoliciesSetting::POLICY_MANDATORY)
            ->setRememberMeForAMonth(false)
            ->persist();

        $result = $this->service->get();

        $this->assertInstanceOf(MfaPolicySettings::class, $result);
        $this->assertSame(MfaPoliciesSetting::POLICY_MANDATORY, $result->policy);
        $this->assertFalse($result->remember_me_for_a_month);
        $this->assertNotNull($result->id);
        $this->assertNotNull($result->created);
        $this->assertNotNull($result->created_by);
        $this->assertNotNull($result->modified);
        $this->assertNotNull($result->modified_by);
    }

    public function testGet_Error_InvalidJsonInDB()
    {
        MfaPoliciesSettingFactory::make(['value' => '{"policy":"opt-in","remember_me_for_a_month"'])->persist();

        $this->expectException(InternalErrorException::class);

        $result = $this->service->get();
    }
}
