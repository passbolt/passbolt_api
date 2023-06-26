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
 * @since         4.1.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\ActionLogs;

use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\MultiFactorAuthentication\MultiFactorAuthenticationPlugin;
use Passbolt\MultiFactorAuthentication\Service\ActionLogs\MfaSortWithLastUsedProviderFirstService;

class MfaSortWithLastUsedProviderFirstServiceTest extends TestCase
{
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    private MfaSortWithLastUsedProviderFirstService $service;

    private UserAccessControl $uac;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MfaSortWithLastUsedProviderFirstService();
        $this->uac = $this->mockUserAccessControl();
        $this->loadPlugins([MultiFactorAuthenticationPlugin::class => []]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
        unset($this->uac);
    }

    public function testMfaSortWithLastUsedProviderFirstService_On_Empty_Argument()
    {
        $providers = [];
        $result = $this->service->sortWithLastUsedProviderFirst($this->uac, $providers);
        $this->assertSame($providers, $result);
    }

    public function testMfaSortWithLastUsedProviderFirstService_On_No_Mfa_Action_Logs()
    {
        $providers = ['a', 'b'];
        ActionLogFactory::make(2)->persist();
        $result = $this->service->sortWithLastUsedProviderFirst($this->uac, $providers);
        $this->assertSame($providers, $result);
    }

    public function testMfaSortWithLastUsedProviderFirstService_With_Multiple_Past_Mfa_Verify()
    {
        // Random action from today
        ActionLogFactory::make(2)->userId($this->uac->getId())->created('today')->persist();
        // Totp from today for other user (should be ignored)
        ActionLogFactory::make()->setActionId('DuoVerifyGet.get')->created('today')->persist();
        // Duo from today with status 0 (should be ignored)
        ActionLogFactory::make()->userId($this->uac->getId())->error()->setActionId('DuoVerifyGet.get')->created('yesterday')->persist();
        // Duo from yesterday (should be returned)
        ActionLogFactory::make()->userId($this->uac->getId())->setActionId('DuoVerifyGet.get')->created('yesterday')->persist();
        // Totp from yesterday (should be ignored)
        ActionLogFactory::make()->userId($this->uac->getId())->setActionId('TotpVerifyGet.get')->created('last week')->persist();
        $result = $this->service->sortWithLastUsedProviderFirst($this->uac, ['totp', 'duo']);
        $this->assertSame(['duo', 'totp'], $result);

        // Calling the method a second time to ensure that the cache is working
        $result = $this->service->sortWithLastUsedProviderFirst($this->uac, ['totp', 'duo']);
        $this->assertSame(['duo', 'totp'], $result);
    }

    public function testMfaSortWithLastUsedProviderFirstService_With_Multiple_Past_Mfa_Verify_Flag_Disabled()
    {
        // With the flag disabled...
        Configure::write(MfaSortWithLastUsedProviderFirstService::SORT_BY_LAST_USAGE_CONFIG_NAME, false);
        // Random action from today
        ActionLogFactory::make(2)->userId($this->uac->getId())->created('today')->persist();
        // Totp from today for other user (should be ignored)
        ActionLogFactory::make()->setActionId('DuoVerifyGet.get')->created('today')->persist();
        // Duo from today with status 0 (should be ignored)
        ActionLogFactory::make()->userId($this->uac->getId())->error()->setActionId('DuoVerifyGet.get')->created('yesterday')->persist();
        // Duo from yesterday (should be returned)
        ActionLogFactory::make()->userId($this->uac->getId())->setActionId('DuoVerifyGet.get')->created('yesterday')->persist();
        // Totp from yesterday (should be ignored)
        ActionLogFactory::make()->userId($this->uac->getId())->setActionId('TotpVerifyGet.get')->created('last week')->persist();
        $result = $this->service->sortWithLastUsedProviderFirst($this->uac, ['totp', 'duo']);
        // ... not sorting is happening
        $this->assertSame(['totp', 'duo'], $result);
    }
}
