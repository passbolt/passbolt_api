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
 * @since         4.0.0
 */

namespace App\Test\TestCase\Service\Cookie;

use App\Service\Cookie\DefaultSecureCookieService;
use Cake\TestSuite\TestCase;

class DefaultSecureCookieServiceTest extends TestCase
{
    public function testDefaultSecureCookieService()
    {
        $service = new DefaultSecureCookieService();
        $name = 'Foo';
        $value = 'Bar';
        $path = 'foo-path';
        $cookie = $service->create($name, $value, $path);
        $this->assertSame($name, $cookie->getName());
        $this->assertSame($value, $cookie->getValue());
        $this->assertSame('/' . $path, $cookie->getPath());
    }

    public function testDefaultSecureCookieService_Path_With_Dash()
    {
        $service = new DefaultSecureCookieService();
        $name = 'Foo';
        $value = 'Bar';
        $path = '/foo-path';
        $cookie = $service->create($name, $value, $path);
        $this->assertSame($name, $cookie->getName());
        $this->assertSame($value, $cookie->getValue());
        $this->assertSame($path, $cookie->getPath());
    }

    public function testDefaultSecureCookieService_Expired()
    {
        $service = new DefaultSecureCookieService();
        $name = 'Foo';
        $value = 'Bar';
        $path = 'foo-path';
        $cookie = $service->createExpired($name, $value, $path);
        $this->assertSame($name, $cookie->getName());
        $this->assertSame($value, $cookie->getValue());
        $this->assertSame('/' . $path, $cookie->getPath());
        $this->assertTrue($cookie->isExpired(), $path);
    }
}
