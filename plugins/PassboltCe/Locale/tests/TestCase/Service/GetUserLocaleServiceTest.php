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

use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Locale\Service\GetOrgLocaleService;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Test\Factory\LocaleSettingFactory;
use Passbolt\Locale\Test\Lib\DummySystemLocaleTestTrait;

class GetUserLocaleServiceTest extends TestCase
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

    public function dataForTestGetUserLocaleServiceGetLocale(): array
    {
        return [
            ['hasLocaleSetting@test.test', 'fr-FR'],
            ['hasNoSetting@test.test', 'foo'],
            ['i_am_not_an_email', 'foo'],
            ['', 'foo'],
        ];
    }

    /**
     * @param string $recipient The email's recipient
     * @param string $expected
     * @throws \Exception
     * @dataProvider dataForTestGetUserLocaleServiceGetLocale
     */
    public function testGetUserLocaleServiceGetLocaleInEmail(string $recipient, string $expected): void
    {
        UserFactory::make(['username' => $recipient])
            ->withLocale('fr-FR')
            ->persist();

        LocaleSettingFactory::make()->locale('foo')->persist();

        $service = new GetUserLocaleService();

        $this->assertSame(
            $expected,
            $service->getLocale('hasLocaleSetting@test.test')
        );
    }
}
