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
use App\Service\Setup\RecoverAbortService;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;

class RecoverAbortServiceTest extends AppTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        // Enable event tracking, required to test events.
        EventManager::instance()->setEventList(new EventList());
    }

    public function testRecoverAbortService_Success(): void
    {
        $authToken = AuthenticationTokenFactory::make()->active()
            ->with('Users', UserFactory::make()->user()->active())
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->persist();
        $user = $authToken->user;

        (new RecoverAbortService())->abort($user->id, $authToken->token);

        $updatedAuthToken = AuthenticationTokenFactory::get($authToken->id);
        $this->assertFalse($updatedAuthToken->active);

        $this->assertEventFired(RecoverAbortService::RECOVER_ABORT_EVENT_NAME);
    }

    public function testRecoverAbortService_FailureForInactiveUser(): void
    {
        $authToken = AuthenticationTokenFactory::make()
            ->active()
            ->with('Users', UserFactory::make()->user()->inactive())
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->persist();
        $user = $authToken->user;

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The user does not exist or is not active or is disabled.');

        (new RecoverAbortService())->abort($user->id, $authToken->token);
    }

    public function testRecoverAbortService_FailureForUserNotExisting(): void
    {
        $authToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->persist();

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The user does not exist, has not completed the setup or was deleted.');

        (new RecoverAbortService())->abort(UuidFactory::uuid(), $authToken->token);
    }

    public function testRecoverAbortService_FailureForTokenExpired(): void
    {
        $authToken = AuthenticationTokenFactory::make()
            ->active()
            ->with('Users', UserFactory::make()->user()->active())
            ->expired()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->persist();
        $user = $authToken->user;

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('The authentication token is not valid.');

        (new RecoverAbortService())->abort($user->id, $authToken->token);
    }
}
