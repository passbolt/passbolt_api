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
 * @since         5.7.0
 */

namespace Passbolt\Locale\Test\TestCase\Service;

use Cake\I18n\I18n;
use Cake\I18n\Parser\PoFileParser;
use Cake\TestSuite\TestCase;
use Exception;
use Passbolt\Locale\Service\LocaleService;

class CrowdinFormatTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
    }

    public static function translator(): array
    {
        return [
            ['default'],
            ['cake'],
        ];
    }

    /**
     * @dataProvider translator
     */
    public function testCrowdinFormatAllLocales(string $translatorType): void
    {
        $stringToTranslate = array_keys((new PoFileParser())->parse(RESOURCES . DS . 'locales' . DS . 'en_UK' . DS . $translatorType . '.po'));
        $errors = [];
        $localesSupportedByPassbolt = LocaleService::getSystemLocales();
        foreach ($localesSupportedByPassbolt as $locale) {
            $translator = I18n::getTranslator($translatorType, $locale);
            foreach ($stringToTranslate as $string) {
                 // Plural translations are prefixed with "p:". We remove it here as it is not part of the sentence to translate
                 $string = trim($string, 'p:');
                 $arguments = $this->seedArgumentsForString($string);
                try {
                    /** @psalm-suppress InternalMethod */
                    $translator->translate($string, $arguments);
                } catch (Exception $e) {
                    $errors[$locale][$string] = $e->getMessage();
                }
            }
        }

        $this->assertSame([], $errors);
    }

    /**
     * @param string $string string to be translated
     * @return array
     */
    private function seedArgumentsForString(string $string): array
    {
        // Count the number of variables in the string to translate, e.g. "Hello Mr. {0} {1}"
        $numberOfArguments = substr_count($string, '{');
        // Create an array of the number of arguments to pass in the translation. E.g. ['foo', 'foo'] will give "Hello Mr. foo foo"

        return array_fill(0, $numberOfArguments, 'foo');
    }
}
