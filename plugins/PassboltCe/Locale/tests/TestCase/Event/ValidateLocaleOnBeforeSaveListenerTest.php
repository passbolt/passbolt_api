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

namespace Passbolt\Locale\Test\TestCase\Event;

use App\Error\Exception\ValidationException;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Locale\Service\GetOrgLocaleService;
use Passbolt\Locale\Service\LocaleService;

class ValidateLocaleOnBeforeSaveListenerTest extends TestCase
{
    use TruncateDirtyTables;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
    }

    public function tearDown(): void
    {
        GetOrgLocaleService::clearOrganisationLocale();
        parent::tearDown();
    }

    public function dataForTestEmailLocaleServiceGetLocale(): array
    {
        return [
            ['fr-FR'],
            ['fr_FR'],
            ['fr+FR', ValidationException::class],
            ['fr*FR', ValidationException::class],
            ['fr FR', ValidationException::class],
            ['xx-YY', ValidationException::class],
            ['', ValidationException::class],
            [null, \TypeError::class],
        ];
    }

    /**
     * @param string|null $recipient The email's recipient
     * @param string $expectException
     * @throws \Exception
     * @dataProvider dataForTestEmailLocaleServiceGetLocale
     */
    public function testLocaleBeforeSaveValidation(?string $locale, ?string $expectException = ''): void
    {
        if ($expectException) {
            $this->expectException($expectException);
        }

        $setting = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings')
            ->createOrUpdateSetting(
                UserFactory::make()->persist()->id,
                LocaleService::SETTING_PROPERTY,
                $locale
            );

        $this->assertSame('fr-FR', $setting->get('value'));
    }
}
