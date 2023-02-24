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
 * @since         3.11.0
 */

namespace App\Test\TestCase\Service\AuthenticationTokens;

use App\Service\AuthenticationTokens\AuthenticationTokenConsumeService;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Lib\AppTestCase;
use Cake\Http\Exception\BadRequestException;

/**
 * Class AuthenticationTokenConsumeServiceTest
 *
 * @package App\Test\TestCase\Service\AuthenticationTokens
 */
class AuthenticationTokenConsumeServiceTest extends AppTestCase
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

    public function testAuthenticationTokenGetService_Success(): void
    {
        $t0 = $this->tokenFactory->active()->persist();
        $t1 = (new AuthenticationTokenConsumeService())
            ->consumeActiveNotExpiredOrFail($t0->token, $t0->user_id, $t0->type);
        $this->assertNotEmpty($t1);
        $this->assertEquals($t0->id, $t1->id);
        $this->assertEquals($t0->token, $t1->token);
        $this->assertEquals($t0->type, $t1->type);
        $this->assertEquals($t0->user_id, $t1->user_id);
        $this->assertCount(0, AuthenticationTokenFactory::find()->where(['active' => true]));
    }

    public function testAuthenticationTokenGetService_Error_InvalidID(): void
    {
        $t0 = $this->tokenFactory->active()->persist();
        $this->expectException(BadRequestException::class);
        (new AuthenticationTokenConsumeService())
            ->consumeActiveNotExpiredOrFail('nope', $t0->user_id, $t0->type);
    }
}
