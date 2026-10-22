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
 * @since         5.17.0
 */
namespace Passbolt\Scim\Test\TestCase\Service\Cache;

use Cake\TestSuite\TestCase;
use Passbolt\Scim\Service\Cache\ScimTokenExpiredNotificationCacheService;
use Passbolt\Scim\Service\Cache\ScimTokenNearExpiryNotificationCacheService;

/**
 * @covers \Passbolt\Scim\Service\Cache\AbstractScimCacheService
 */
class ScimCacheServiceTest extends TestCase
{
    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        ScimTokenExpiredNotificationCacheService::reset();
        ScimTokenNearExpiryNotificationCacheService::reset();
        parent::tearDown();
    }

    public function testScimCacheService_Success(): void
    {
        $expiredService = new ScimTokenExpiredNotificationCacheService();
        $nearExpiryService = new ScimTokenNearExpiryNotificationCacheService();

        $expiredService->markNotificationPerformed();
        $this->assertTrue($expiredService->isNotificationPerformed());
        // assert cache engine configured
        $this->assertTrue(ScimTokenExpiredNotificationCacheService::isEngineConfigured());

        $this->assertFalse($nearExpiryService->isNotificationPerformed());
        $nearExpiryService->markNotificationPerformed();
        $this->assertTrue($nearExpiryService->isNotificationPerformed());
        // assert cache engine configured
        $this->assertTrue(ScimTokenNearExpiryNotificationCacheService::isEngineConfigured());
    }
}
