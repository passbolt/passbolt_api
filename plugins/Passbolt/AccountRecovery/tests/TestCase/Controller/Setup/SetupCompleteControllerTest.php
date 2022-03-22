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
 * @since         3.6.0
 */
namespace Passbolt\AccountRecovery\Test\TestCase\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class SetupCompleteControllerTest extends AccountRecoveryIntegrationTestCase
{
    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testAccountRecoverySetupComplete_Success()
    {
        $user = UserFactory::make()->inactive()->persist();
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->userId($user->id)
            ->persist();

        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
        $data = [
            'authenticationtoken' => [
                'token' => $token->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
            'user' => [
                'locale' => 'fr_FR', // Putting on purpose an underscore, though convention is dashed.
            ],
            'locale' => 'fr_FR',
        ];
        $this->postJson('/setup/complete/' . $user->id . '.json', $data);
        $this->assertSuccess();
    }

    public function testAccountRecoverySetupComplete_Error_InvalidToken()
    {
        $user = UserFactory::make()->inactive()->persist();

        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
        $data = [
            'authenticationtoken' => [
                'token' => UuidFactory::uuid(),
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
        ];
        $this->postJson('/setup/complete/' . $user->id . '.json', $data);
        $this->assertError();
    }

    public function testAccountRecoverySetupComplete_Error_InvalidKey()
    {
        $user = UserFactory::make()->inactive()->persist();
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->userId($user->id)
            ->persist();

        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'sofia_public.key');
        $data = [
            'authenticationtoken' => [
                'token' => $token->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
        ];
        $this->postJson('/setup/complete/' . $user->id . '.json', $data);
        $this->assertError(400);
    }

    public function testAccountRecoverySetupComplete_Error_InvalidUser()
    {
        $user = UserFactory::make()->active()->persist();
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->userId($user->id)
            ->persist();

        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');
        $data = [
            'authenticationtoken' => [
                'token' => $token->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
        ];
        $this->postJson('/setup/complete/' . $user->id . '.json', $data);
        $this->assertError(400, 'The user does not exist, is already active or has been deleted.');
    }
}
