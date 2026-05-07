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
use Passbolt\MfaPolicies\Service\RememberAMonthSettingService;
use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;

/**
 * @covers \Passbolt\MfaPolicies\Service\RememberAMonthSettingService
 */
class RememberAMonthSettingServiceTest extends AppTestCase
{
    /**
     * @var \Passbolt\MfaPolicies\Service\RememberAMonthSettingService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new RememberAMonthSettingService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testIsEnabledReturnsTrueFromDb()
    {
        MfaPoliciesSettingFactory::make()->persist();

        $result = $this->service->isEnabled();

        $this->assertTrue($result);
    }

    public function testIsEnabledReturnsFalseFromDb()
    {
        MfaPoliciesSettingFactory::make()->setRememberMeForAMonth(false)->persist();

        $result = $this->service->isEnabled();

        $this->assertFalse($result);
    }

    public function testIsEnabledReturnsTrueDefaultValueWhenNotPresentInDb()
    {
        $result = $this->service->isEnabled();

        $this->assertTrue($result);
    }

    public function testInvalidDataInDb()
    {
        MfaPoliciesSettingFactory::make(['value' => 'invalid'])->persist();

        $result = $this->service->isEnabled();

        $this->assertFalse($result);
    }
}
