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
 * @since         3.9.0
 */

namespace Passbolt\SmtpSettings\Test\TestCase\Middleware;

use Cake\Http\Exception\ForbiddenException;
use Cake\TestSuite\TestCase;
use Passbolt\SmtpSettings\Middleware\SmtpSettingsSecurityMiddleware;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SmtpSettingsSecurityMiddlewareTest extends TestCase
{
    use SmtpSettingsTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->disableSmtpSettingsEndpoints();
    }

    public function testSmtpSettingsSecurityMiddlewareTest_Should_Be_Forbidden_If_Security_Enabled()
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('SMTP settings endpoints disabled.');

        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $handler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        (new SmtpSettingsSecurityMiddleware())->process($request, $handler);
    }
}
