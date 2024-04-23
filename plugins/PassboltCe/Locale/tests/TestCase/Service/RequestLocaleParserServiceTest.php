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
 * @since         3.2.0
 */

namespace Passbolt\Locale\Test\TestCase\Service;

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use Authentication\Authenticator\Result;
use Cake\Http\Exception\BadRequestException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Locale\Service\GetOrgLocaleService;
use Passbolt\Locale\Service\RequestLocaleParserService;
use Passbolt\Locale\Test\Factory\LocaleSettingFactory;
use Passbolt\Locale\Test\Lib\DummySystemLocaleTestTrait;
use Psr\Http\Message\ServerRequestInterface;

class RequestLocaleParserServiceTest extends TestCase
{
    use DummySystemLocaleTestTrait;
    use TruncateDirtyTables;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $this->addFooSystemLocale();
    }

    public function tearDown(): void
    {
        GetOrgLocaleService::clearOrganisationLocale();
        $this->removeFooSystemLocale();
        parent::tearDown();
    }

    public function dataForTestRequestLocaleParserServiceGetLocale(): array
    {
        return [
            ['fr-FR', 'fr-FR'],
            ['fr_FR', 'fr-FR'],
            ['xx-YY', 'foo'],
            ['', 'foo'],
            [null, 'foo'],
        ];
    }

    /**
     * @Given I pass a locale in the url, and my organization is german
     * @When I query the locale
     * @Then I should get the locale in the url if valid, or the organization one.
     * @param string|null $localeInTheUrl
     * @param string|null $expected
     * @throws \Exception
     * @dataProvider dataForTestRequestLocaleParserServiceGetLocale
     */
    public function testRequestLocaleParserServiceGetLocaleWithUrl(?string $localeInTheUrl, ?string $expected): void
    {
        $organizationLocale = 'foo';
        LocaleSettingFactory::make()->locale($organizationLocale)->persist();

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn([RequestLocaleParserService::QUERY_KEY => $localeInTheUrl]);

        $service = new RequestLocaleParserService($request);

        $this->assertSame(
            $expected,
            $service->getLocale()
        );
    }

    public function testRequestLocaleParserService_Get_Authenticated_User_Locale()
    {
        $organizationLocale = 'foo';
        OrganizationSettingFactory::make()->locale($organizationLocale)->persist();

        $userLocale = 'fr-FR';
        $user = UserFactory::make()->withLocale($userLocale)->persist();

        // Not authenticated
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->willReturn(null);
        $service = new RequestLocaleParserService($request);
        $this->assertSame(
            $organizationLocale,
            $service->getLocale()
        );

        // Session authenticated
        $result = new Result(compact('user'), Result::SUCCESS);
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->willReturn($result);
        $service = new RequestLocaleParserService($request);
        $this->assertSame(
            $userLocale,
            $service->getLocale()
        );

        // Jwt authenticated
        $result = new Result($user, Result::SUCCESS);
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->willReturn($result);
        $service = new RequestLocaleParserService($request);
        $this->assertSame(
            $userLocale,
            $service->getLocale()
        );
    }

    public function testRequestLocaleParserServiceGetLocaleWithArrayLocaleFormatShouldThrow400(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn([RequestLocaleParserService::QUERY_KEY => ['foo']]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The locale should be a string.');
        (new RequestLocaleParserService($request))->getLocale();
    }
}
