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

use Cake\I18n\I18n;
use Cake\TestSuite\TestCase;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\Locale\Test\Lib\DummyTranslationTestTrait;

class LocaleServiceTest extends TestCase
{
    use DummyTranslationTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale']);
        I18n::setLocale('en_UK');
    }

    /**
     * Staticly check that the supported locales are well defined in the config.
     */
    public function testGetSystemLocales(): void
    {
        $this->assertSame([
            'en-UK',
            'fr-FR',
        ], LocaleService::getSystemLocales());
    }

    public function dataForTestLocaleServiceLocaleIsValid(): array
    {
        return [
            ['en-UK', true],
            ['en_UK', true],
            ['fr_FR', true],
            ['xx-YY', false],
            ['', false],
            [null, false],
        ];
    }

    /**
     * @param string|null $locale
     * @param bool $expected
     * @dataProvider dataForTestLocaleServiceLocaleIsValid
     */
    public function testLocaleServiceLocaleIsValid(?string $locale, bool $expected): void
    {
        $service = new LocaleService();
        $this->assertSame(
            $expected,
            $service->isValidLocale($locale)
        );
    }

    public function dataProviderForTestLocaleServiceLocaleTranslate_On_Existing_Locale()
    {
        return [
            ['fr-FR', 'Courriel envoyé de: admin@passbolt.com'],
            ['fr_FR', 'Courriel envoyé de: admin@passbolt.com'],
            ['foo_BAR', 'Sending email from: admin@passbolt.com'],
            ['', 'Sending email from: admin@passbolt.com'],
        ];
    }

    public function testLocaleServiceLocaleTranslate_Plain_String()
    {
        $this->setDummyFrenchTranslator();
        $service = new LocaleService();
        $translation = $service->translate('fr-FR', $this->getDummyEnglishEmailSentence());

        $this->assertSame($this->getDummyFrenchEmailSentence(), $translation);
        // Ensure that the locale is set to the original one.
        $this->assertSame('en_UK', I18n::getLocale());
    }

    /**
     * @param string $locale locale to translate
     * @param string $expectedSubject expected translation
     * @dataProvider dataProviderForTestLocaleServiceLocaleTranslate_On_Existing_Locale
     */
    public function testLocaleServiceLocaleTranslate(string $locale, string $expectedSubject)
    {
        $this->setDummyFrenchTranslator();
        $service = new LocaleService();

        $msg = 'Sending email from: {0}';
        $username = 'admin@passbolt.com';
        $translation = $service->translate($locale, $msg, $username);

        $this->assertSame($expectedSubject, $translation);
        // Ensure that the locale is set to the original one.
        $this->assertSame('en_UK', I18n::getLocale());
    }
}
