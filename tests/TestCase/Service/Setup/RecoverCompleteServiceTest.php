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
 * @since         4.1.0
 */

namespace App\Test\TestCase\Service\Setup;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Service\Setup\RecoverCompleteService;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;

class RecoverCompleteServiceTest extends AppTestCase
{
    public function testRecoverCompleteService_Success(): void
    {
        $authToken = AuthenticationTokenFactory::make()->active()
            ->with('Users', UserFactory::make()->user()->active()->withValidGpgKey())
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->persist();
        $user = $authToken->user;

        $request = new ServerRequest([
            'post' => [
                'authentication_token' => $authToken->toArray(),
                'gpgkey' => [
                    'armored_key' => $user->gpgkey->armored_key,
                ],
            ],
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
        ]);

        (new RecoverCompleteService($request))->complete($user->id);

        $updatedAuthToken = AuthenticationTokenFactory::get($authToken->id);
        $this->assertFalse($updatedAuthToken->active);
    }

    public function testRecoverCompleteService_FailOnExpiredToken(): void
    {
        $authToken = AuthenticationTokenFactory::make()
            ->active()
            ->expired()
            ->with('Users', UserFactory::make()->user()->active()->withValidGpgKey())
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->persist();
        $user = $authToken->user;

        $request = new ServerRequest([
            'post' => [
                'authentication_token' => $authToken->toArray(),
                'gpgkey' => [
                    'armored_key' => $user->gpgkey->armored_key,
                ],
            ],
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
        ]);

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('The authentication token is not valid.');

        (new RecoverCompleteService($request))->complete($user->id);
    }

    public function testRecoverCompleteService_FailOnNotMatchingKey(): void
    {
        $authToken = AuthenticationTokenFactory::make()
            ->active()
            ->with('Users', UserFactory::make()->user()->active()->withValidGpgKey())
            ->type(AuthenticationToken::TYPE_RECOVER)
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

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The key provided does not belong to given user.');

        (new RecoverCompleteService($request))->complete($user->id);
    }
}
