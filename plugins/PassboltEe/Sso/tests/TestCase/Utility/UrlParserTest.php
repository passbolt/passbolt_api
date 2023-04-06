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
 * @since         4.1.0
 */

namespace Passbolt\Sso\Test\TestCase\Utility;

use Passbolt\Sso\Utility\UrlParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Passbolt\Sso\Utility\UrlParser
 */
class UrlParserTest extends TestCase
{
    public function testGetUrlOnly()
    {
        $result = UrlParser::getUrlOnly('https://passbolt.com');
        $this->assertSame('https://passbolt.com', $result);

        $result = UrlParser::getUrlOnly('https://passbolt.com?foo=bar');
        $this->assertSame('https://passbolt.com', $result);

        $result = UrlParser::getUrlOnly('https://passbolt.com/oauth2/v0.0/authorize?response_type=code');
        $this->assertSame('https://passbolt.com/oauth2/v0.0/authorize', $result);

        $result = UrlParser::getUrlOnly('https://passbolt.com#heading-1');
        $this->assertSame('https://passbolt.com', $result);
    }

    public function testParseQueryString()
    {
        $result = UrlParser::parseQueryString('https://passbolt.com');
        $this->assertSame([], $result);

        $result = UrlParser::parseQueryString('https://passbolt.com#heading-1');
        $this->assertSame([], $result);

        $result = UrlParser::parseQueryString('https://passbolt.com?foo=bar');
        $this->assertSame(['foo' => 'bar'], $result);

        $result = UrlParser::parseQueryString('https://passbolt.com?response_type=code&email=ada@passbolt.com');
        $this->assertSame(['response_type' => 'code', 'email' => 'ada@passbolt.com'], $result);
    }
}
