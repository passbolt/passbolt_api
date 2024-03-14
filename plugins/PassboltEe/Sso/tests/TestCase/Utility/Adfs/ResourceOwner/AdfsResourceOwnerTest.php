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
 * @since         4.6.0
 */

namespace Passbolt\Sso\Test\TestCase\Utility\Adfs\ResourceOwner;

use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\TestSuite\TestCase;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Utility\Adfs\ResourceOwner\AdfsResourceOwner;

/**
 * @covers \Passbolt\Sso\Utility\Adfs\ResourceOwner\AdfsResourceOwner
 */
class AdfsResourceOwnerTest extends TestCase
{
    public function testAdfsResourceOwner_Success(): void
    {
        $data = [
            'oid' => UuidFactory::uuid(),
            'upn' => 'ada@passbolt.test',
            'nonce' => SsoState::generate(),
        ];

        $result = new AdfsResourceOwner($data, SsoSetting::ADFS_EMAIL_CLAIM_UPN);

        $this->assertEqualsCanonicalizing($data, $result->toArray());
        $this->assertSame($data['upn'], $result->getEmail());
        $this->assertSame($data['oid'], $result->getId());
        $this->assertSame($data['nonce'], $result->getNonce());
    }

    public function testAdfsResourceOwner_Success_DefaultClaimIsUpn(): void
    {
        $data = [
            'oid' => UuidFactory::uuid(),
            'upn' => 'ada@passbolt.test',
            'nonce' => SsoState::generate(),
        ];

        $result = new AdfsResourceOwner($data);

        $this->assertEqualsCanonicalizing($data, $result->toArray());
        $this->assertSame($data['upn'], $result->getEmail());
        $this->assertSame($data['oid'], $result->getId());
        $this->assertSame($data['nonce'], $result->getNonce());
    }

    public function testAdfsResourceOwner_Error_ClaimFieldNotPresent(): void
    {
        $data = [
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.test', // upn is expected
            'nonce' => SsoState::generate(),
        ];

        $this->expectException(BadRequestException::class);

        (new AdfsResourceOwner($data, SsoSetting::ADFS_EMAIL_CLAIM_UPN))->getEmail();
    }

    public function testAdfsResourceOwner_Error_ClaimFieldIsNull(): void
    {
        $emailClaimField = SsoSetting::ADFS_EMAIL_CLAIM_UPN;

        $data = [
            'oid' => UuidFactory::uuid(),
            $emailClaimField => null,
            'nonce' => SsoState::generate(),
            'auth_time' => time(),
        ];

        $this->expectException(BadRequestException::class);

        (new AdfsResourceOwner($data, $emailClaimField))->getEmail();
    }
}
