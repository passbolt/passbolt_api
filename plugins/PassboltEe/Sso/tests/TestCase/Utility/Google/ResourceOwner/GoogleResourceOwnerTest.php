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
 * @since         4.0.0
 */

namespace Passbolt\Sso\Test\TestCase\Utility\Google\ResourceOwner;

use App\Utility\UuidFactory;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Utility\Google\ResourceOwner\GoogleResourceOwner;
use Passbolt\Sso\Utility\OpenId\SsoResourceOwnerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Passbolt\Sso\Utility\Google\ResourceOwner\GoogleResourceOwner
 */
class GoogleResourceOwnerTest extends TestCase
{
    public function testGoogleResourceOwner_ObjectImplementsResourceOwnerInterfaces(): void
    {
        $result = new GoogleResourceOwner([]);

        $this->assertInstanceOf(ResourceOwnerInterface::class, $result);
        $this->assertInstanceOf(SsoResourceOwnerInterface::class, $result);
    }

    public function testGoogleResourceOwner_ToArray(): void
    {
        $data = ['oid' => UuidFactory::uuid(), 'email' => 'ada@passbolt.com'];
        $result = (new GoogleResourceOwner($data))->toArray();
        $this->assertEqualsCanonicalizing($data, $result);
    }

    public function testGoogleResourceOwner_GetId(): void
    {
        $data = ['oid' => UuidFactory::uuid(), 'email' => 'ada@passbolt.com'];
        $result = (new GoogleResourceOwner($data))->getId();
        $this->assertSame($data['oid'], $result);
    }

    public function testGoogleResourceOwner_GetEmailNotEmpty(): void
    {
        $data = ['oid' => UuidFactory::uuid(), 'email' => 'ada@passbolt.com'];
        $result = (new GoogleResourceOwner($data))->getEmail();
        $this->assertSame($data['email'], $result);
    }

    public function testGoogleResourceOwner_GetEmailEmpty(): void
    {
        $data = ['oid' => UuidFactory::uuid()];
        $result = (new GoogleResourceOwner($data))->getEmail();
        $this->assertNull($result);
    }

    public function testGoogleResourceOwner_GetNonceNotEmpty(): void
    {
        $data = ['oid' => UuidFactory::uuid(), 'nonce' => SsoState::generate()];
        $result = (new GoogleResourceOwner($data))->getNonce();
        $this->assertSame($data['nonce'], $result);
    }

    public function testGoogleResourceOwner_GetNonceEmpty(): void
    {
        $data = ['oid' => UuidFactory::uuid()];
        $result = (new GoogleResourceOwner($data))->getNonce();
        $this->assertNull($result);
    }
}
