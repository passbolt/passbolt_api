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
 * @since         5.17.0
 */
namespace App\Test\TestCase\Database\Type;

use App\Database\Type\WebauthnCredentialRecordType;
use App\Test\Factory\WebauthnCredentialFactory;
use Cake\Database\Driver\Mysql;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use Webauthn\CredentialRecord;

/**
 * @covers \App\Database\Type\WebauthnCredentialRecordType
 */
class WebauthnCredentialRecordTypeTest extends TestCase
{
    private WebauthnCredentialRecordType $type;
    private Mysql $driver;

    public function setUp(): void
    {
        parent::setUp();
        $this->type = new WebauthnCredentialRecordType('webauthn_credential_record');
        $this->driver = $this->createMock(Mysql::class);
    }

    public function tearDown(): void
    {
        unset($this->type, $this->driver);
        parent::tearDown();
    }

    public function testWebauthnCredentialRecordType_Roundtrip(): void
    {
        $record = WebauthnCredentialFactory::makeCredentialRecord('roundtrip-id');
        $json = $this->type->toDatabase($record, $this->driver);
        $this->assertIsString($json);

        $restored = $this->type->toPHP($json, $this->driver);
        $this->assertInstanceOf(CredentialRecord::class, $restored);
        $this->assertSame('roundtrip-id', $restored->publicKeyCredentialId);
    }

    public function testWebauthnCredentialRecordType_ToDatabase_NullPassesThrough(): void
    {
        $this->assertNull($this->type->toDatabase(null, $this->driver));
    }

    public function testWebauthnCredentialRecordType_ToDatabase_NonRecord_Throws(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->type->toDatabase(['not' => 'a record'], $this->driver);
    }

    public function testWebauthnCredentialRecordType_ToPHP_NullPassesThrough(): void
    {
        $this->assertNull($this->type->toPHP(null, $this->driver));
    }

    public function testWebauthnCredentialRecordType_Marshal_AcceptsCredentialRecord(): void
    {
        $record = WebauthnCredentialFactory::makeCredentialRecord('marshal-id');
        $this->assertSame($record, $this->type->marshal($record));
    }

    public function testWebauthnCredentialRecordType_Marshal_AcceptsJsonString(): void
    {
        $record = WebauthnCredentialFactory::makeCredentialRecord('marshal-string-id');
        $json = $this->type->toDatabase($record, $this->driver);
        $back = $this->type->marshal($json);
        $this->assertInstanceOf(CredentialRecord::class, $back);
        $this->assertSame('marshal-string-id', $back->publicKeyCredentialId);
    }

    public function testWebauthnCredentialRecordType_Marshal_NullReturnsNull(): void
    {
        $this->assertNull($this->type->marshal(null));
        $this->assertNull($this->type->marshal(''));
    }
}
