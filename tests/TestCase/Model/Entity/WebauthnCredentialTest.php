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
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\WebauthnCredential;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;
use Webauthn\CredentialRecord;

/**
 * @covers \App\Model\Entity\WebauthnCredential
 */
class WebauthnCredentialTest extends AppTestCase
{
    public function testWebauthnCredential_NewEntity_HasNoAccessibleFieldsByDefault(): void
    {
        $entity = new WebauthnCredential();
        $this->assertFalse($entity->isAccessible('user_id'));
        $this->assertFalse($entity->isAccessible('type'));
        $this->assertFalse($entity->isAccessible('credential_id'));
        $this->assertFalse($entity->isAccessible('sign_count'));
        $this->assertFalse($entity->isAccessible('data'));
    }

    public function testWebauthnCredential_DataBlob_IsExcludedFromToArray(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->persist();

        $this->assertInstanceOf(CredentialRecord::class, $credential->data);
        $this->assertArrayNotHasKey('data', $credential->toArray());
    }
}
