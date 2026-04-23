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
 * @since         5.11.0
 */

namespace Passbolt\Sso\Test\TestCase\Service\Sso\PingOne;

use App\Service\Cookie\DefaultSecureCookieService;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Service\Sso\OAuth2\SsoOAuth2Service;
use Passbolt\Sso\Service\Sso\PingOne\SsoPingOneService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;

/**
 * @covers \Passbolt\Sso\Service\Sso\PingOne\SsoPingOneService
 */
class SsoPingOneServiceTest extends SsoTestCase
{
    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        SsoProviderFactory::clear();
        parent::tearDown();
    }

    public function testSsoPingOneService_Success(): void
    {
        SsoSettingsFactory::make()->pingone()->active()->persist();

        $service = new SsoPingOneService(new DefaultSecureCookieService(), null);

        $this->assertInstanceOf(SsoPingOneService::class, $service);
        $this->assertInstanceOf(SsoOAuth2Service::class, $service);
    }

    public function testSsoPingOneService_Error_DraftSettings(): void
    {
        // Draft (non-active) settings should not be found by getActiveOrFail
        SsoSettingsFactory::make()->pingone()->persist();

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('No valid SSO settings found.');

        new SsoPingOneService(new DefaultSecureCookieService(), null);
    }

    public function testSsoPingOneService_Error_NoActiveSettings(): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('No valid SSO settings found.');

        new SsoPingOneService(new DefaultSecureCookieService(), null);
    }

    public function testSsoPingOneService_Error_WrongProviderAzure(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('No valid SSO settings found.');

        new SsoPingOneService(new DefaultSecureCookieService(), null);
    }
}
