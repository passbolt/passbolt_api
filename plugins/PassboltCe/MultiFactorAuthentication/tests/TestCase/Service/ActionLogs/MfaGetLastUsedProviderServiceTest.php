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
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\MultiFactorAuthentication\Service\ActionLogs\MfaGetLastUsedProviderService;

class MfaGetLastUsedProviderServiceTest extends TestCase
{
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    private MfaGetLastUsedProviderService $service;

    private UserAccessControl $uac;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MfaGetLastUsedProviderService();
        $this->uac = $this->mockUserAccessControl();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
        unset($this->uac);
    }

    public function testMfaGetLastUsedProviderServiceGetLastProvider_On_Enpty_Argument()
    {
        $provider = $this->service->getLastUsedOrDefaultProvider($this->uac, []);
        $this->assertNull($provider);
    }

    public function testMfaGetLastUsedProviderServiceGetLastProvider_On_No_Mfa_Action_Logs()
    {
        ActionLogFactory::make(2)->persist();
        $provider = $this->service->getLastUsedOrDefaultProvider($this->uac, ['foo', 'bar']);
        $this->assertNull($provider);
    }

    public function testMfaGetLastUsedProviderServiceGetLastProvider_With_Multiple_Past_Mfa_Verify()
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
        $provider = $this->service->getLastUsedOrDefaultProvider($this->uac, ['totp', 'duo']);
        $this->assertSame('duo', $provider);
    }
}
