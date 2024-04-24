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

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\MultiFactorAuthentication\MultiFactorAuthenticationPlugin;
use Passbolt\MultiFactorAuthentication\Service\MfaRateLimiterService;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\MfaRateLimiterService
 */
class MfaRateLimiterServiceTest extends AppTestCase
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\MfaRateLimiterService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MultiFactorAuthenticationPlugin::class);
        $this->service = new MfaRateLimiterService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMfaRateLimiterService_SessionAuthWithDefaultConfigValue_FailedAttemptsExceeded()
    {
        $user = UserFactory::make()->user()->persist();
        // login action
        ActionLogFactory::make(['created' => FrozenTime::now()->subMinutes(2)])
            ->userId($user->id)
            ->loginAction()
            ->persist();
        // 5 failed attempts
        ActionLogFactory::make(['created' => FrozenTime::now()], 5)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $result = $this->service->isFailedAttemptsExceeded($user->id, false);

        $this->assertTrue($result);
    }

    public function testMfaRateLimiterService_SessionAuthWithDefaultConfigValue_FailedAttemptsNotExceeded()
    {
        $user = UserFactory::make()->user()->persist();
        // login action
        ActionLogFactory::make(['created' => FrozenTime::now()->subMinutes(2)])
            ->userId($user->id)
            ->loginAction()
            ->persist();
        // 3 failed attempts
        ActionLogFactory::make(['created' => FrozenTime::now()], 3)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $result = $this->service->isFailedAttemptsExceeded($user->id, false);

        $this->assertFalse($result);
    }

    public function testMfaRateLimiterService_SessionAuthWithDefaultConfigValue_OldEntriesDoesnotCountAsFailedAttempts()
    {
        $user = UserFactory::make()->user()->persist();
        // Old actions
        ActionLogFactory::make(['created' => FrozenTime::now()->subMinutes(2)])
            ->userId($user->id)
            ->loginAction()
            ->persist();
        ActionLogFactory::make(['created' => FrozenTime::now()], 3)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();
        // login action again
        ActionLogFactory::make(['created' => FrozenTime::now()])
            ->userId($user->id)
            ->loginAction()
            ->persist();
        ActionLogFactory::make(['created' => FrozenTime::now()], 2)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $result = $this->service->isFailedAttemptsExceeded($user->id, false);

        $this->assertFalse($result);
    }

    public function testMfaRateLimiterService_SessionAuthWithSpecifiedValue_FailedAttemptsExceeded()
    {
        $user = UserFactory::make()->user()->persist();
        // Set max attempts to 1
        Configure::write('passbolt.security.mfa.maxAttempts', 1);
        // login action
        ActionLogFactory::make(['created' => FrozenTime::now()->subMinutes(2)])
            ->userId($user->id)
            ->loginAction()
            ->persist();
        // 2 failed attempts
        ActionLogFactory::make(['created' => FrozenTime::now()], 2)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $result = $this->service->isFailedAttemptsExceeded($user->id, false);

        $this->assertTrue($result);
    }

    public function testMfaRateLimiterService_SessionAuthWithZeroInfiniteMaxAttempts()
    {
        $user = UserFactory::make()->user()->persist();
        // Set max attempts to 0 (that means no limit for failed attempts), GO CRAZY!
        Configure::write('passbolt.security.mfa.maxAttempts', 0);
        // login action
        ActionLogFactory::make(['created' => FrozenTime::now()->subMinutes(2)])
            ->userId($user->id)
            ->loginAction()
            ->persist();
        // any number of failed attempts
        ActionLogFactory::make(['created' => FrozenTime::now()], 100)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $result = $this->service->isFailedAttemptsExceeded($user->id, false, false);

        $this->assertFalse($result);
    }

    public function testMfaRateLimiterService_JwtAuthWithDefaultConfigValue_FailedAttemptsExceeded()
    {
        $user = UserFactory::make()->user()->persist();
        // login action
        ActionLogFactory::make(['created' => FrozenTime::now()->subMinutes(2)])
            ->setActionId('JwtLogin.loginPost')
            ->userId($user->id)
            ->persist();
        // 5 failed attempts
        ActionLogFactory::make(['created' => FrozenTime::now(), 'status' => 0], 5)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $result = $this->service->isFailedAttemptsExceeded($user->id, true, true);

        $this->assertTrue($result);
    }

    public function testMfaRateLimiterService_JwtAuthWithDefaultConfigValue_FailedAttemptsNotExceeded()
    {
        $user = UserFactory::make()->user()->persist();
        // login action
        ActionLogFactory::make(['created' => FrozenTime::now()->subMinutes(2)])
            ->setActionId('JwtLogin.loginPost')
            ->userId($user->id)
            ->persist();
        // 3 failed attempts
        ActionLogFactory::make(['created' => FrozenTime::now()], 3)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $result = $this->service->isFailedAttemptsExceeded($user->id, true, true);

        $this->assertFalse($result);
    }

    public function withSpecifiedValueFailedAttemptsNotExceededProvider(): array
    {
        return [
            // session auth
            [true, false, true], // With incremented. Same number of failed entries will be incremented.
            [false, false, false],
            // JWT auth
            [true, true, true], // With incremented. Same number of failed entries will be incremented.
            [false, true, false],
        ];
    }

    /**
     * @dataProvider withSpecifiedValueFailedAttemptsNotExceededProvider
     */
    public function testMfaRateLimiterService_WithSpecifiedValue_FailedAttemptsNotExceeded(
        $shouldIncrement,
        $isJwtAuth,
        $expected
    ) {
        $user = UserFactory::make()->user()->persist();
        // Set max attempts to 2
        Configure::write('passbolt.security.mfa.maxAttempts', 2);
        // login action
        $actionId = $isJwtAuth ? 'JwtLogin.loginPost' : 'AuthLogin.loginPost';
        ActionLogFactory::make(['created' => FrozenTime::now()->subMinutes(2)])
            ->userId($user->id)
            ->setActionId($actionId)
            ->persist();
        // 2 failed attempts
        $status = $isJwtAuth ? 0 : 1;
        ActionLogFactory::make(['created' => FrozenTime::now(), 'status' => $status], 2)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $result = $this->service->isFailedAttemptsExceeded($user->id, $isJwtAuth, $shouldIncrement);

        $this->assertSame($expected, $result);
    }
}
