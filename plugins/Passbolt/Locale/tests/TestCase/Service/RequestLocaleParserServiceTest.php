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
use Cake\TestSuite\TestCase;
use Passbolt\Locale\Service\GetOrgLocaleService;
use Passbolt\Locale\Service\RequestLocaleParserService;
use Passbolt\Locale\Test\Lib\DummySystemLocaleTestTrait;
use Psr\Http\Message\ServerRequestInterface;

class RequestLocaleParserServiceTest extends TestCase
{
    use DummySystemLocaleTestTrait;

    public function setUp(): void
    {
        $this->loadPlugins(['Passbolt/Locale']);
        $this->addFooSystemLocale();
    }

    public function tearDown(): void
    {
        GetOrgLocaleService::clearOrganisationLocale();
        $this->removeFooSystemLocale();
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
        OrganizationSettingFactory::make()->locale($organizationLocale)->persist();

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn([RequestLocaleParserService::QUERY_KEY => $localeInTheUrl]);

        $service = new RequestLocaleParserService($request);

        $this->assertSame(
            $expected,
            $service->getLocale()
        );
    }
}
