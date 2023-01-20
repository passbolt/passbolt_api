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

namespace Passbolt\SelfRegistration\Test\TestCase\Service\DryRun;

use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDefaultDryRunService;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

/**
 * @covers \Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDefaultDryRunService
 */
class SelfRegistrationDefaultDryRunServiceTest extends TestCase
{
    use SelfRegistrationTestTrait;

    /**
     * @var SelfRegistrationDefaultDryRunService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SelfRegistrationDefaultDryRunService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSelfRegistrationDefaultDryService_canGuestSelfRegister_Should_Throw_A_Not_Found_Exception()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The self registration plugin is not enabled.');
        $this->assertFalse($this->service->canGuestSelfRegister([]));
    }

    public function testSelfRegistrationDefaultDryService_isSelfRegistrationOpen_Should_Always_Return_False()
    {
        $this->assertFalse($this->service->isSelfRegistrationOpen());
    }
}
