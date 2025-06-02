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
 * @since         5.2.0
 */

namespace Passbolt\UserKeyPolicies\Test\TestCase\Service;

use App\Test\Lib\AppTestCase;
use Passbolt\UserKeyPolicies\Model\Dto\UserKeyPoliciesSettingsDto;
use Passbolt\UserKeyPolicies\Service\UserKeyPoliciesGetSettingsService;

/**
 * @covers \Passbolt\UserKeyPolicies\Service\UserKeyPoliciesGetSettingsService
 */
class UserKeyPoliciesGetSettingsServiceTest extends AppTestCase
{
    private ?UserKeyPoliciesGetSettingsService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new UserKeyPoliciesGetSettingsService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    /**
     * Default settings returned when nothing is set.
     *
     * @return void
     */
    public function testUserKeyPoliciesGetSettingsService_Default(): void
    {
        $result = $this->service->get();

        $this->assertInstanceOf(UserKeyPoliciesSettingsDto::class, $result);
        $expectedSettings = UserKeyPoliciesSettingsDto::createFromDefault()->toArray();
        $this->assertArrayEqualsCanonicalizing($expectedSettings, $result->toArray());
    }
}
