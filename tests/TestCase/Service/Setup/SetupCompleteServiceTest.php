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
 * @since         4.10.0
 */

namespace App\Test\TestCase\Service\Setup;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Service\Setup\SetupCompleteService;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Http\ServerRequest;

class SetupCompleteServiceTest extends AppTestCase
{
    public function testSetupCompleteService_Success(): void
    {
        $authToken = AuthenticationTokenFactory::make()->active()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->persist();
        $user = $authToken->user;

        $key = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
        $request = new ServerRequest([
            'post' => [
                'authentication_token' => $authToken->toArray(),
                'gpgkey' => [
                    'armored_key' => $key,
                ],
            ],
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
        ]);

        (new SetupCompleteService($request))->complete($user->id);

        $updatedUser = UserFactory::get($user->id, ['contain' => ['Gpgkeys']]);
        $this->assertTrue($updatedUser->active);
        $this->assertSame($key, $updatedUser->gpgkey->armored_key);

        $updatedAuthToken = AuthenticationTokenFactory::get($authToken->id);
        $this->assertFalse($updatedAuthToken->active);
    }

    public function testSetupCompleteService_FailOnExpiredToken(): void
    {
        $authToken = AuthenticationTokenFactory::make()->active()
            ->expired()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->persist();
        $user = $authToken->user;

        $key = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
        $request = new ServerRequest([
            'post' => [
                'authentication_token' => $authToken->toArray(),
                'gpgkey' => [
                    'armored_key' => $key,
                ],
            ],
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
        ]);

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('The authentication token is not valid.');

        (new SetupCompleteService($request))->complete($user->id);
    }

    public function testSetupCompleteService_FailOnBadKey(): void
    {
        $authToken = AuthenticationTokenFactory::make()->active()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->persist();
        $user = $authToken->user;

        $key = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'old_revoked_public.key');
        $request = new ServerRequest([
            'post' => [
                'authentication_token' => $authToken->toArray(),
                'gpgkey' => [
                    'armored_key' => $key,
                ],
            ],
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
        ]);

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('The public key could not be validated.');

        (new SetupCompleteService($request))->complete($user->id);
    }
}
