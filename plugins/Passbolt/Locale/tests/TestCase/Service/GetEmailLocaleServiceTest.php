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
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Passbolt\Locale\Service\GetEmailLocaleService;
use Passbolt\Locale\Utility\LocaleUtility;

class GetEmailLocaleServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->loadPlugins(['Passbolt/Locale']);
    }

    public function tearDown(): void
    {
        LocaleUtility::clearOrganisationLocale();
    }

    public function dataForTestGetEmailLocaleServiceGetLocale(): array
    {
        return [
            ['hasLocaleSetting@test.test', 'fr-FR'],
            ['hasNoSetting@test.test', 'de'],
            ['foo', 'de'],
            ['', 'de'],
        ];
    }

    /**
     * @param string|null $recipient The email's recipient
     * @param string|null $expected
     * @throws \Exception
     * @dataProvider dataForTestGetEmailLocaleServiceGetLocale
     */
    public function testGetEmailLocaleServiceGetLocaleInEmail(?string $recipient, ?string $expected = null): void
    {
        Configure::write('passbolt.plugins.locale.options', LocaleUtility::getAvailableLocales() + ['de' => 'German']);

        UserFactory::make(['username' => $recipient])
            ->user()
            ->withLocale('fr-FR')
            ->persist();

        OrganizationSettingFactory::make()->locale('de')->persist();

        $service = new GetEmailLocaleService('hasLocaleSetting@test.test');

        $this->assertSame(
            $expected,
            $service->getLocale()
        );
    }
}
