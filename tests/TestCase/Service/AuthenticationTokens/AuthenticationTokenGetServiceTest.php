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

namespace App\Test\TestCase\Service\AuthenticationTokens;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

/**
 * Class AuthenticationTokenGetServiceTest
 *
 * @package App\Test\TestCase\Service\AuthenticationTokens
 */
class AuthenticationTokenGetServiceTest extends AppTestCase
{
    /**
     * @var \App\Test\Factory\AuthenticationTokenFactory
     */
    private $tokenFactory;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenFactory = AuthenticationTokenFactory::make()->with('Users');
    }

    public function tearDown(): void
    {
        unset($this->tokenFactory);
        parent::tearDown();
    }

    public function testAuthenticationTokenGetService_getActiveNotExpiredOrFail_Success(): void
    {
        $t0 = $this->tokenFactory->active()->persist();
        $t1 = (new AuthenticationTokenGetService())
            ->getActiveNotExpiredOrFail($t0->token, $t0->user_id, $t0->type);
        $this->assertNotEmpty($t1);
        $this->assertEquals($t0->id, $t1->id);
        $this->assertEquals($t0->token, $t1->token);
        $this->assertEquals($t0->type, $t1->type);
        $this->assertEquals($t0->user_id, $t1->user_id);
    }

    public function testAuthenticationTokenGetService_getActiveNotExpiredOrFail_Error_InvalidID(): void
    {
        $t0 = $this->tokenFactory->active()->persist();
        $this->expectException(BadRequestException::class);
        (new AuthenticationTokenGetService())
            ->getActiveNotExpiredOrFail('nope', $t0->user_id, $t0->type);
    }

    public function testAuthenticationTokenGetService_getActiveNotExpiredOrFail_Error_NotFoundID(): void
    {
        $t0 = $this->tokenFactory->active()->persist();
        $this->expectException(NotFoundException::class);
        (new AuthenticationTokenGetService())
            ->getActiveNotExpiredOrFail(UuidFactory::uuid(), $t0->user_id, $t0->type);
    }

    public function testAuthenticationTokenGetService_getActiveNotExpiredOrFail_Error_DifferentType(): void
    {
        $t0 = $this->tokenFactory->inactive()->persist();
        $this->expectException(NotFoundException::class);
        (new AuthenticationTokenGetService())
            ->getActiveNotExpiredOrFail($t0->token, $t0->user_id, AuthenticationToken::TYPE_MOBILE_TRANSFER);
    }

    public function testAuthenticationTokenGetService_getActiveNotExpiredOrFail_Error_NotActive(): void
    {
        $t0 = $this->tokenFactory->inactive()->persist();
        $this->expectException(CustomValidationException::class);
        (new AuthenticationTokenGetService())
            ->getActiveNotExpiredOrFail($t0->token, $t0->user_id, $t0->type);
    }

    public function testAuthenticationTokenGetService_getActiveNotExpiredOrFail_Error_Expired(): void
    {
        $t0 = $this->tokenFactory->active()->expired()->persist();
        $this->expectException(CustomValidationException::class);
        (new AuthenticationTokenGetService())
            ->getActiveNotExpiredOrFail($t0->token, $t0->user_id, $t0->type);
    }

    public function testAuthenticationTokenGetService_getActiveOrFail_Success_ActiveExpired(): void
    {
        $t0 = $this->tokenFactory->active()->expired()->persist();
        $token = (new AuthenticationTokenGetService())->getActiveOrFail($t0->token, $t0->user_id, $t0->type);
        $this->assertTrue(AuthenticationTokenFactory::get($token->id)->isActive());
    }

    public function testAuthenticationTokenGetService_get_Success_ActiveNotExpired(): void
    {
        $t0 = $this->tokenFactory->active()->persist();
        $token = (new AuthenticationTokenGetService())->get($t0->token, $t0->user_id, $t0->type);
        $this->assertNotEmpty($token);
        $this->assertEquals($t0->id, $token->id);
        $this->assertEquals($t0->token, $token->token);
        $this->assertEquals($t0->type, $token->type);
        $this->assertEquals($t0->user_id, $token->user_id);
    }

    public function testAuthenticationTokenGetService_get_Success_ActiveExpired(): void
    {
        $t0 = $this->tokenFactory->active()->expired()->persist();
        $token = (new AuthenticationTokenGetService())->get($t0->token, $t0->user_id, $t0->type);
        $this->assertNotEmpty($token);
        $this->assertEquals($t0->id, $token->id);
        $this->assertEquals($t0->token, $token->token);
        $this->assertEquals($t0->type, $token->type);
        $this->assertEquals($t0->user_id, $token->user_id);
    }

    public function testAuthenticationTokenGetService_get_Success_InactiveNotExpired(): void
    {
        $t0 = $this->tokenFactory->inactive()->persist();
        $token = (new AuthenticationTokenGetService())->get($t0->token, $t0->user_id, $t0->type);
        $this->assertNotEmpty($token);
        $this->assertEquals($t0->id, $token->id);
        $this->assertEquals($t0->token, $token->token);
        $this->assertEquals($t0->type, $token->type);
        $this->assertEquals($t0->user_id, $token->user_id);
    }

    public function testAuthenticationTokenGetService_get_Success_InactiveExpired(): void
    {
        $t0 = $this->tokenFactory->inactive()->expired()->persist();
        $token = (new AuthenticationTokenGetService())->get($t0->token, $t0->user_id, $t0->type);
        $this->assertNotEmpty($token);
        $this->assertEquals($t0->id, $token->id);
        $this->assertEquals($t0->token, $token->token);
        $this->assertEquals($t0->type, $token->type);
        $this->assertEquals($t0->user_id, $token->user_id);
    }

    public function testAuthenticationTokenGetService_get_Success_NotFound(): void
    {
        $t0 = $this->tokenFactory->inactive()->expired()->persist();
        $token = (new AuthenticationTokenGetService())->get(UuidFactory::uuid(), $t0->user_id, $t0->type);
        $this->assertEmpty($token);
        $token = (new AuthenticationTokenGetService())->get($t0->token, UuidFactory::uuid(), $t0->type);
        $this->assertEmpty($token);
        $token = (new AuthenticationTokenGetService())->get($t0->token, $t0->user_id, 'not-valid-type');
        $this->assertEmpty($token);
    }

    public function testAuthenticationTokenGetService_get_Error_Invalidtoken(): void
    {
        $this->expectException(BadRequestException::class);
        (new AuthenticationTokenGetService())->get('not-uuid', UuidFactory::uuid(), UuidFactory::uuid());
    }
}
