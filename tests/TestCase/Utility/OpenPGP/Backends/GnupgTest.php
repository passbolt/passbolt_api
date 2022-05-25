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
 * @since         2.10.0
 */
namespace App\Test\TestCase\Utility\OpenPGP\Backends;

use App\Utility\OpenPGP\Backends\Gnupg;

class GnupgTest extends OpenPGPBackendTest
{
    public $originalErrorSettings;

    /**
     * @var Gnupg
     */
    public $gnupg;

    public function setUp(): void
    {
        parent::setUp();
        $this->originalErrorSettings = ini_get('error_reporting');
        $this->gnupg = new Gnupg();
    }

    public function tearDown(): void
    {
        $settings = ini_get('error_reporting');
        if ($settings != $this->originalErrorSettings) {
            ini_set('error_reporting', $this->originalErrorSettings);
        }
    }

    /**
     * With PHPStan suspicious on the constant, this test checks that it is well defined and
     * that the error can be ignored.
     */
    public function testGnupgErrorMode(): void
    {
        $this->assertSame(2, \gnupg::ERROR_EXCEPTION, 'This constant is not defined.');
    }
}
