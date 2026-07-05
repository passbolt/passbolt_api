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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Test\TestCase\Controller;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\PasskeyAuthentication\Test\Factory\PasskeyCredentialFactory;

/**
 * @group passkey
 */
class PasskeyDeleteControllerTest extends AppIntegrationTestCase
{
    public function testPasskeyDelete_NotAuthenticated(): void
    {
        $this->deleteJson('/passkey/settings.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testPasskeyDelete_SingleSuccess(): void
    {
        $user = $this->logInAsUser();
        PasskeyCredentialFactory::make()
            ->withUser($user->id)
            ->patchData(['credential_id' => 'AAAABBBBCCCC'])
            ->persist();

        $this->deleteJson('/passkey/settings/AAAABBBBCCCC.json?api-version=v2');

        $this->assertResponseSuccess();
        $this->assertSame(0, PasskeyCredentialFactory::count());
    }

    public function testPasskeyDelete_SingleUnknownReturnsNotFound(): void
    {
        $this->logInAsUser();

        $this->deleteJson('/passkey/settings/unknown-credential-id.json?api-version=v2');

        $this->assertNotFoundError('The security key does not exist.');
    }

    public function testPasskeyDelete_CannotDeleteAnotherUsersCredential(): void
    {
        $this->logInAsUser();
        $other = UserFactory::make()->user()->persist();
        PasskeyCredentialFactory::make()
            ->withUser($other->id)
            ->patchData(['credential_id' => 'OTHERUSERKEY'])
            ->persist();

        $this->deleteJson('/passkey/settings/OTHERUSERKEY.json?api-version=v2');

        // Scoped to the current user: someone else's credential is indistinguishable from a missing one.
        $this->assertNotFoundError('The security key does not exist.');
        $this->assertSame(1, PasskeyCredentialFactory::count());
    }

    public function testPasskeyDelete_AllForUserOnly(): void
    {
        $user = $this->logInAsUser();
        PasskeyCredentialFactory::make(2)->withUser($user->id)->persist();
        $other = UserFactory::make()->user()->persist();
        PasskeyCredentialFactory::make()->withUser($other->id)->persist();

        $this->deleteJson('/passkey/settings.json?api-version=v2');

        $this->assertResponseSuccess();
        // Only the current user's credentials are removed; the other user's is kept.
        $this->assertSame(0, PasskeyCredentialFactory::find()->where(['user_id' => $user->id])->count());
        $this->assertSame(1, PasskeyCredentialFactory::find()->where(['user_id' => $other->id])->count());
    }
}
