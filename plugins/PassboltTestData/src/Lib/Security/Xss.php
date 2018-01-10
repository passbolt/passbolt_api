<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace PassboltTestData\Lib\Security;

class Xss
{

    /**
     * Get xss exploits
     *
     * @return array
     */
    public static function getExploits()
    {
        return [
            'xss JavaScript directive quote semicolon' => "javascript:document.write('xss1');",
            'xss JavaScript directive quote no semicolon' => "javascript:document.write('xss2')",
            'xss JavaScript directive double quote' => 'javascript:document.write("XSS3")',
            'xss JavaScript directive case insensitive' => "JaVaScRiPt:document.write('XSS4')",
            'xss Javascript directive HTML entities' => 'javascript:document.write(&quot;XSS5&quot;)',
            'xss Javascript directive fromCharCode' => 'javascript:document.write(String.fromCharCode(88,83,83,54))',
            'xss Decimal HTML character references' => '&#106;&#97;&#118;&#97;&#115;&#99;&#114;&#105;&#112;&#116;&#58;&#100;&#111;&#99;&#117;&#109;&#101;&#110;&#116;&#46;&#119;&#114;&#105;&#116;&#101;&#40;&#39;&#88;&#83;&#83;&#55;&#39;&#41;',
            'xss Decimal HTML character references without trailing semicolons' => '&#0000106;&#0000097;&#0000118;&#0000097;&#0000115;&#0000099;&#0000114;&#0000105;&#0000112;&#0000116;&#0000058;&#0000100;&#0000111;&#0000099;&#0000117;&#0000109;&#0000101;&#0000110;&#0000116;&#0000046;&#0000119;&#0000114;&#0000105;&#0000116;&#0000101;&#0000040;&#0000039;&#0000088;&#0000083;&#0000083;&#0000056;&#0000039;&#0000041;',
            'xss Hexadecimal HTML char references without trailing semicolons' => '&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x64&#x6f&#x63&#x75&#x6d&#x65&#x6e&#x74&#x2e&#x77&#x72&#x69&#x74&#x65&#x27&#x58&#x53&#x53&#x39&#x27&#x29',
            'xss Embedded tab' => "jav        ascript:document.write('XSS10');",
            'xss Embedded Encoded tab' => "jav&#x09;ascript:document.write('XSS11');",
            'xss Embedded carriage return to break up XSS' => "jav&#x0D;ascript:document.write('XSS12');",
            'xss Embedded newline to break up XSS' => "jav&#x0A;ascript:document.write('XSS13');",
            'xss space and meta chars before the javascript' => "&#14;  javascript:document.write('XSS14');",
            'xss Extraneous >' => '"' . "><script>document.write('xss15')</script>",
            'xss Extraneous closing double quote' => '">' . "onclick=document.write('xxs16')",
            'xss & JavaScript includes' =>  "&{document.write('XSS17')}",
            'xss null breaks up javascript directive' => 'java\0script:document.write("XSS18")',
        ];
    }
}
