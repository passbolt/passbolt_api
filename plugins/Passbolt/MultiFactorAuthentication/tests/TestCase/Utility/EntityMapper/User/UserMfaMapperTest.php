<?php
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
 * @since         2.14.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Model\Mapper;

use App\Model\Entity\User;
use Passbolt\MultiFactorAuthentication\Service\IsMfaEnabledService;
use Passbolt\MultiFactorAuthentication\Utility\EntityMapper\User\MfaEntityMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MfaEntityMapperTest extends TestCase
{
    /**
     * @var MockObject|IsMfaEnabledService
     */
    private $isMfaEnabledServiceMock;

    /**
     * @var MfaEntityMapper
     */
    private $sut;

    public function setUp()
    {
        $this->isMfaEnabledServiceMock = $this->createMock(IsMfaEnabledService::class);

        $this->sut = new MfaEntityMapper($this->isMfaEnabledServiceMock);

        parent::setUp();
    }

    public function testThatUserHasMfaSettingsPropertyIsHidden()
    {
        $user = new User();

        call_user_func($this->sut, $user);

        $this->assertContains(MfaEntityMapper::MFA_SETTINGS_PROPERTY, $user->getHidden());
    }

    public function testThatIsMfaEnabledPropertyIsVirtual()
    {
        $user = new User();

        call_user_func($this->sut, $user);

        $this->assertContains(MfaEntityMapper::IS_MFA_ENABLED_PROPERTY, $user->getVirtual());
    }

    public function testThatIsMfaEnabledPropertyHasCorrectValueDefined()
    {
        $user = new User();

        $this->isMfaEnabledServiceMock
            ->expects($this->exactly(2))
            ->method('isEnabledForUser')
            ->willReturnOnConsecutiveCalls(false, true);

        call_user_func($this->sut, $user);
        $this->assertEquals($user->get(MfaEntityMapper::IS_MFA_ENABLED_PROPERTY), false);

        call_user_func($this->sut, $user);
        $this->assertEquals($user->get(MfaEntityMapper::IS_MFA_ENABLED_PROPERTY), true);
    }
}
