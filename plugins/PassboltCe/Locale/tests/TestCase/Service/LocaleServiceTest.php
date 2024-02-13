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
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\Locale\Test\Lib\DummyTranslationTestTrait;

class LocaleServiceTest extends TestCase
{
    use DummyTranslationTestTrait;
    use TruncateDirtyTables;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
        I18n::setLocale('en_UK');
    }

    /**
     * Staticly check that the supported locales are well defined in the config.
     */
    public function testGetSystemLocales(): void
    {
        $this->assertSame([
            'de-DE',
            'en-UK',
            'es-ES',
            'fr-FR',
            'it-IT',
            'ja-JP',
            'ko-KR',
            'lt-LT',
            'nl-NL',
            'pl-PL',
            'pt-BR',
            'ro-RO',
            'ru-RU',
            'sv-SE',
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

    public function dataProviderForTestLocaleServiceLocaleTranslateString_On_Existing_Locale_English_Default(): array
    {
        return [
            ['fr-FR', 'Courriel envoyé de: admin@passbolt.com'],
            ['fr_FR', 'Courriel envoyé de: admin@passbolt.com'],
            ['en-UK', 'Sending email from: admin@passbolt.com'],
            ['en_UK', 'Sending email from: admin@passbolt.com'],
            ['foo_BAR', 'Sending email from: admin@passbolt.com'],
            ['', 'Sending email from: admin@passbolt.com'],
        ];
    }

    public function dataProviderForTestLocaleServiceLocaleTranslateString_On_Existing_Locale_French_Default(): array
    {
        return [
            ['fr-FR', 'Courriel envoyé de: admin@passbolt.com'],
            ['fr_FR', 'Courriel envoyé de: admin@passbolt.com'],
            ['en-UK', 'Sending email from: admin@passbolt.com'],
            ['en_UK', 'Sending email from: admin@passbolt.com'],
            ['foo_BAR', 'Courriel envoyé de: admin@passbolt.com'],
            ['', 'Courriel envoyé de: admin@passbolt.com'],
        ];
    }

    public function testLocaleServiceLocaleTranslateString_Plain_String()
    {
        $this->setDummyFrenchTranslator();
        $service = new LocaleService();
        $translation = $service->translateString('fr-FR', function () {
            return __('This is an email in english.');
        });

        $this->assertSame($this->getDummyFrenchEmailSentence(), $translation);
        // Ensure that the locale is set to the original one.
        $this->assertSame('en_UK', I18n::getLocale());
    }

    /**
     * @param string $locale locale to translate
     * @param string $expectedSubject expected translation
     * @dataProvider dataProviderForTestLocaleServiceLocaleTranslateString_On_Existing_Locale_English_Default
     */
    public function testLocaleServiceLocaleTranslateString(string $locale, string $expectedSubject)
    {
        $this->setDummyFrenchTranslator();

        $service = new LocaleService();
        $translation = $service->translateString($locale, function () {
            return __('Sending email from: {0}', 'admin@passbolt.com');
        });

        $this->assertSame($expectedSubject, $translation);
        // Ensure that the locale is set to the original one.
        $this->assertSame('en_UK', I18n::getLocale());
    }

    /**
     * @param string $locale locale to translate
     * @param string $expectedSubject expected translation
     * @dataProvider dataProviderForTestLocaleServiceLocaleTranslateString_On_Existing_Locale_French_Default
     */
    public function testLocaleServiceLocaleTranslateString_With_French_Org_Setting(string $locale, string $expectedSubject)
    {
        I18n::setLocale('fr_FR');
        $this->setDummyFrenchTranslator();

        $service = new LocaleService();
        $translation = $service->translateString($locale, function () {
            return __('Sending email from: {0}', 'admin@passbolt.com');
        });

        $this->assertSame($expectedSubject, $translation);
        // Ensure that the locale is set to the original one.
        $this->assertSame('fr_FR', I18n::getLocale());
    }
}
