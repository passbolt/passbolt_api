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
 * @since         5.11.0
 */

namespace Passbolt\Sso\Test\TestCase\Utility\PingOne\ResourceOwner;

use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\TestSuite\TestCase;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Utility\PingOne\ResourceOwner\PingOneResourceOwner;

/**
 * @covers \Passbolt\Sso\Utility\PingOne\ResourceOwner\PingOneResourceOwner
 */
class PingOneResourceOwnerTest extends TestCase
{
    public function testPingOneResourceOwner_Success_DefaultClaimIsEmail(): void
    {
        $data = [
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.test',
            'nonce' => SsoState::generate(),
        ];

        $result = new PingOneResourceOwner($data);

        $this->assertEqualsCanonicalizing($data, $result->toArray());
        $this->assertSame($data['email'], $result->getEmail());
        $this->assertSame($data['oid'], $result->getId());
        $this->assertSame($data['nonce'], $result->getNonce());
    }

    public function testPingOneResourceOwner_Success_CustomClaimField(): void
    {
        $data = [
            'oid' => UuidFactory::uuid(),
            'upn' => 'ada@passbolt.test',
            'nonce' => SsoState::generate(),
        ];

        $result = new PingOneResourceOwner($data, 'upn');

        $this->assertEqualsCanonicalizing($data, $result->toArray());
        $this->assertSame($data['upn'], $result->getEmail());
        $this->assertSame($data['oid'], $result->getId());
        $this->assertSame($data['nonce'], $result->getNonce());
    }

    public function testPingOneResourceOwner_Error_ClaimFieldNotPresent(): void
    {
        $data = [
            'oid' => UuidFactory::uuid(),
            'upn' => 'ada@passbolt.test', // email is expected
            'nonce' => SsoState::generate(),
        ];

        $this->expectException(BadRequestException::class);

        (new PingOneResourceOwner($data, SsoSetting::PINGONE_EMAIL_CLAIM_EMAIL))->getEmail();
    }

    public function testPingOneResourceOwner_Error_ClaimFieldIsNull(): void
    {
        $emailClaimField = SsoSetting::PINGONE_EMAIL_CLAIM_EMAIL;

        $data = [
            'oid' => UuidFactory::uuid(),
            $emailClaimField => null,
            'nonce' => SsoState::generate(),
        ];

        $this->expectException(BadRequestException::class);

        (new PingOneResourceOwner($data, $emailClaimField))->getEmail();
    }
}
