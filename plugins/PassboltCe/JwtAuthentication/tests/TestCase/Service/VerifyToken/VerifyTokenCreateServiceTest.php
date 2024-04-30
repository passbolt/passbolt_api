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
 * @since         3.3.0
 */

namespace Passbolt\JwtAuthentication\Test\TestCase\Service\VerifyToken;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\JwtAuthentication\Service\VerifyToken\VerifyTokenCreateService;
use Passbolt\JwtAuthentication\Service\VerifyToken\VerifyTokenValidationService;

/**
 * @covers \Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService
 */
class VerifyTokenCreateServiceTest extends TestCase
{
    use LocatorAwareTrait;
    use TruncateDirtyTables;

    /**
     * @var \Passbolt\JwtAuthentication\Service\VerifyToken\VerifyTokenCreateService
     */
    public $service;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    public static function setUpBeforeClass(): void
    {
        Configure::write(VerifyTokenValidationService::VERIFY_TOKEN_EXPIRY_CONFIG_KEY, '1 hour');
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new VerifyTokenCreateService();
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    public function testVerifyTokenCreateService_Valid()
    {
        $userId = UserFactory::make()->user()->persist()->id;

        // Old token for that user: should be deleted
        AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_VERIFY_TOKEN)
            ->created(FrozenTime::now()->subHours(1)->subSeconds(1))
            ->userId($userId)
            ->persist();

        // Old token for another user: should be deleted
        AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_VERIFY_TOKEN)
            ->created(FrozenTime::now()->subHours(1)->subSeconds(1))
            ->userId(UuidFactory::uuid())
            ->persist();

        // Valid token for that user of another type: should not be deleted
        AuthenticationTokenFactory::make()
            ->type('Foo')
            ->created(FrozenTime::now()->addMinutes(1))
            ->userId($userId)
            ->persist();

        $token = UuidFactory::uuid();
        $this->service->createToken($token, $userId);

        $this->assertSame(2, $this->AuthenticationTokens->find()->count());
        $this->assertTrue($this->AuthenticationTokens->exists(compact('token')));
        $this->assertTrue($this->AuthenticationTokens->exists(['type' => 'Foo']));
    }
}
