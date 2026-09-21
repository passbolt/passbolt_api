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
 * @since         5.13.0
 */

namespace Passbolt\Sso\Test\TestCase\Service\Edition;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Edition\SsoDowngradeCleanupService;
use Passbolt\Sso\Test\Factory\SsoKeysFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;

/**
 * @covers \Passbolt\Sso\Service\Edition\SsoDowngradeCleanupService
 */
class SsoDowngradeCleanupServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public function testSsoDowngradeCleanupService_Cleanup_TruncatesPluginTables(): void
    {
        SsoStateFactory::make(2)->persist();
        SsoKeysFactory::make(2)->persist();
        SsoSettingsFactory::make(2)->persist();

        (new SsoDowngradeCleanupService())->cleanup();

        $this->assertSame(0, SsoStateFactory::count());
        $this->assertSame(0, SsoKeysFactory::count());
        $this->assertSame(0, SsoSettingsFactory::count());
    }

    public function testSsoDowngradeCleanupService_Cleanup_DeletesSsoAuthTokensButPreservesOthers(): void
    {
        AuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_GET_KEY)->persist();
        AuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_SET_SETTINGS)->persist();
        AuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_RECOVER)->persist();
        AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_LOGIN)->persist();
        AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_REFRESH_TOKEN)->persist();
        AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_REGISTER)->persist();

        (new SsoDowngradeCleanupService())->cleanup();

        $this->assertSame(3, AuthenticationTokenFactory::count());
        $remainingTypes = AuthenticationTokenFactory::find()
            ->all()
            ->extract('type')
            ->toList();
        $this->assertEqualsCanonicalizing(
            [
                AuthenticationToken::TYPE_LOGIN,
                AuthenticationToken::TYPE_REFRESH_TOKEN,
                AuthenticationToken::TYPE_REGISTER,
            ],
            $remainingTypes,
        );
    }

    public function testSsoDowngradeCleanupService_Cleanup_IsIdempotentWhenNoDataExists(): void
    {
        (new SsoDowngradeCleanupService())->cleanup();

        $this->assertSame(0, SsoStateFactory::count());
    }
}
