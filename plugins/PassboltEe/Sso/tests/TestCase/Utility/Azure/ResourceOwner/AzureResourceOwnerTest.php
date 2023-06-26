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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Test\TestCase\Utility\Azure\ResourceOwner;

use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\TestSuite\TestCase;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Utility\Azure\ResourceOwner\AzureResourceOwner;

/**
 * @covers \Passbolt\Sso\Utility\Azure\ResourceOwner\AzureResourceOwner
 */
class AzureResourceOwnerTest extends TestCase
{
    public function testAzureResourceOwner_Success(): void
    {
        $data = [
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.test',
            'nonce' => SsoState::generate(),
            'auth_time' => time(),
        ];

        $result = new AzureResourceOwner($data, SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL);

        $this->assertEqualsCanonicalizing($data, $result->toArray());
        $this->assertSame($data['email'], $result->getEmail());
        $this->assertSame($data['oid'], $result->getId());
        $this->assertSame($data['nonce'], $result->getNonce());
        $this->assertSame($data['auth_time'], $result->getAuthTime());
    }

    public function testAzureResourceOwner_Error_ClaimFieldNotPresent(): void
    {
        $data = [
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.test',
            'nonce' => SsoState::generate(),
            'auth_time' => time(),
        ];

        $this->expectException(BadRequestException::class);

        (new AzureResourceOwner($data, SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_UPN))->getEmail();
    }

    public function testAzureResourceOwner_Error_ClaimFieldIsNull(): void
    {
        $emailAliasField = SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_PREFERRED_USERNAME;

        $data = [
            'oid' => UuidFactory::uuid(),
            $emailAliasField => null,
            'nonce' => SsoState::generate(),
            'auth_time' => time(),
        ];

        $this->expectException(BadRequestException::class);

        (new AzureResourceOwner($data, $emailAliasField))->getEmail();
    }
}
