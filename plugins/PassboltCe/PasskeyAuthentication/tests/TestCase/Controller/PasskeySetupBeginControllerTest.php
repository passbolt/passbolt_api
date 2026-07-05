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

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Lib\AppIntegrationTestCase;

/**
 * @group passkey
 */
class PasskeySetupBeginControllerTest extends AppIntegrationTestCase
{
    public function testPasskeySetupBegin_NotAuthenticated(): void
    {
        $this->postJson('/passkey/setup/begin.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testPasskeySetupBegin_Success(): void
    {
        $this->logInAsUser();

        $this->postJson('/passkey/setup/begin.json?api-version=v2');

        $this->assertResponseSuccess();
        // A single-use passkey_setup challenge token holding the ceremony options is created.
        $this->assertSame(1, AuthenticationTokenFactory::count());
        $token = AuthenticationTokenFactory::find()->firstOrFail();
        $this->assertSame(AuthenticationToken::TYPE_PASSKEY_SETUP, $token->type);
        $this->assertNotEmpty($this->_responseJsonBody->credentialCreationOptions);
    }

    public function testPasskeySetupBegin_RequiresJson(): void
    {
        $this->logInAsUser();

        $this->post('/passkey/setup/begin');

        $this->assertResponseError('This is not a valid Ajax/Json request.');
    }
}
