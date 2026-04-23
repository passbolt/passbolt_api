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
 * @since         4.0.0
 */
namespace Passbolt\SsoRecover\Test\TestCase\Service;

use App\Error\Exception\ValidationException;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Routing\Router;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;
use Passbolt\SsoRecover\Service\SsoRecoverStartService;

/**
 * @covers \Passbolt\SsoRecover\Service\SsoRecoverStartService
 */
class SsoRecoverStartServiceTest extends AppTestCase
{
    use SelfRegistrationTestTrait;

    /**
     * @var \Passbolt\SsoRecover\Service\SsoRecoverStartService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SsoRecoverStartService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testGenerateAndGetRecoverUrl_Error_UserNotFound(): void
    {
        $this->expectException(RecordNotFoundException::class);

        $this->service->generateAndGetRecoverUrl(UuidFactory::uuid());
    }

    public function testGenerateAndGetRecoverUrl_Error_UserDeleted(): void
    {
        $user = UserFactory::make()->user()->deleted()->persist();

        $this->expectException(ValidationException::class);

        $this->service->generateAndGetRecoverUrl($user->id);
    }

    public function testGenerateAndGetRecoverUrl_Success_Register(): void
    {
        $user = UserFactory::make()->user()->inactive()->persist();

        $result = $this->service->generateAndGetRecoverUrl($user->id);

        /** @var \App\Model\Entity\AuthenticationToken $authToken */
        $authToken = AuthenticationTokenFactory::find()->firstOrFail();
        $this->assertSame(
            Router::url("/setup/start/{$user->id}/{$authToken->token}", true),
            $result
        );
    }

    public function testGenerateAndGetRecoverUrl_Success_Recover(): void
    {
        $user = UserFactory::make()->user()->active()->persist();

        $result = $this->service->generateAndGetRecoverUrl($user->id);

        /** @var \App\Model\Entity\AuthenticationToken $authToken */
        $authToken = AuthenticationTokenFactory::find()->firstOrFail();
        $this->assertSame(
            Router::url("/setup/recover/{$user->id}/{$authToken->token}", true),
            $result
        );
    }
}
