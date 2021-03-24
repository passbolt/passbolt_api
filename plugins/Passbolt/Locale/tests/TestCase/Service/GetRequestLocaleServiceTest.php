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
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Passbolt\Locale\Service\GetRequestLocaleService;
use Passbolt\Locale\Utility\LocaleUtility;
use Psr\Http\Message\ServerRequestInterface;

class GetRequestLocaleServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->loadPlugins(['Passbolt/Locale']);
    }

    public function tearDown(): void
    {
        LocaleUtility::clearOrganisationLocale();
    }

    public function dataForTestGetRequestLocaleServiceGetLocale(): array
    {
        return [
            ['fr-FR', 'fr-FR'],
            ['fr_FR', 'fr-FR'],
            ['xx-YY', 'de'],
            ['', 'de'],
            [null, 'de'],
        ];
    }

    /**
     * @Given I pass a locale in the url, and my organization is german
     * @When I query the locale
     * @Then I should get the locale in the url if valid, or the organization one.
     * @param string|null $localeInTheUrl
     * @param string|null $expected
     * @throws \Exception
     * @dataProvider dataForTestGetRequestLocaleServiceGetLocale
     */
    public function testGetRequestLocaleServiceGetLocaleWithUrl(?string $localeInTheUrl, ?string $expected = 'de'): void
    {
        Configure::write('passbolt.plugins.locale.options', LocaleUtility::getAvailableLocales() + ['de' => 'German']);

        $organizationLocale = 'de';
        OrganizationSettingFactory::make()->locale($organizationLocale)->persist();

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn([GetRequestLocaleService::QUERY_KEY => $localeInTheUrl]);

        $service = new GetRequestLocaleService($request);

        $this->assertSame(
            $expected,
            $service->getLocale()
        );
    }
}
