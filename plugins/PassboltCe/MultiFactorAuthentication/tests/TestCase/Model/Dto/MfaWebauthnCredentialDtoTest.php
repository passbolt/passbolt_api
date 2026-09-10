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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Model\Dto;

use App\Model\Entity\WebauthnCredential;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Model\Dto\MfaWebauthnCredentialDto;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Model\Dto\MfaWebauthnCredentialDto
 */
class MfaWebauthnCredentialDtoTest extends TestCase
{
    public function testMfaWebauthnCredentialDto_FromEntity_MapsEveryFieldWithMatchingJsonKey(): void
    {
        $lastUsed = new DateTime('2026-07-27 01:19:21');
        $created = new DateTime('2026-07-27 00:57:56');
        $modified = new DateTime('2026-07-27 00:57:56');

        $entity = new WebauthnCredential();
        $entity->set('id', '53019072-571f-4523-8a74-76644721b6f7');
        $entity->set('credential_id', 'rNBHwaMQLa7ghkiTu-LpV-HGmvY');
        $entity->set('name', 'UAT Yubikey');
        $entity->set('sign_count', 7);
        $entity->set('last_used', $lastUsed);
        $entity->set('created', $created);
        $entity->set('modified', $modified);

        $expected = [
            'id' => '53019072-571f-4523-8a74-76644721b6f7',
            'credential_id' => 'rNBHwaMQLa7ghkiTu-LpV-HGmvY',
            'name' => 'UAT Yubikey',
            'sign_count' => 7,
            'last_used' => $lastUsed,
            'created' => $created,
            'modified' => $modified,
        ];

        $this->assertSame($expected, MfaWebauthnCredentialDto::fromEntity($entity)->toArray());
    }

    public function testMfaWebauthnCredentialDto_FromEntity_PreservesNullableFields(): void
    {
        $entity = new WebauthnCredential();
        $entity->set('id', '53019072-571f-4523-8a74-76644721b6f7');
        $entity->set('credential_id', 'rNBHwaMQLa7ghkiTu-LpV-HGmvY');
        $entity->set('name', null);
        $entity->set('sign_count', 0);
        $entity->set('last_used', null);
        $entity->set('created', null);
        $entity->set('modified', null);

        $body = MfaWebauthnCredentialDto::fromEntity($entity)->toArray();
        $this->assertNull($body['name']);
        $this->assertNull($body['last_used']);
        $this->assertNull($body['created']);
        $this->assertNull($body['modified']);
    }

    public function testMfaWebauthnCredentialDto_ToArray_DoesNotExposeCredentialRecordData(): void
    {
        $entity = new WebauthnCredential();
        $entity->set('id', '53019072-571f-4523-8a74-76644721b6f7');
        $entity->set('credential_id', 'rNBHwaMQLa7ghkiTu-LpV-HGmvY');
        $entity->set('name', 'Yubikey');
        $entity->set('sign_count', 0);

        $body = MfaWebauthnCredentialDto::fromEntity($entity)->toArray();
        $this->assertArrayNotHasKey('data', $body);
    }
}
