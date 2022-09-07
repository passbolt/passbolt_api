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
 * @since         3.8.0
 */

namespace Passbolt\SmtpSettings\Test\TestCase;

use Cake\Mailer\TransportFactory;
use Cake\TestSuite\TestCase;
use Passbolt\SmtpSettings\Mailer\Transport\SmtpTransport;
use Passbolt\SmtpSettings\Plugin;

/**
 * @covers \Passbolt\SmtpSettings\Plugin
 */
class SmtpSettingsPluginTest extends TestCase
{
    public function testSmtpSettingsPluginTest_mapSmtpTransport()
    {
        (new Plugin())->mapSmtpTransport();

        $className = TransportFactory::getDsnClassMap()['smtp'];
        $this->assertSame(SmtpTransport::class, $className);
    }
}
